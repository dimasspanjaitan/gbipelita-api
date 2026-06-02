<?php

namespace App\Services\Scheduling;

class GeneticScheduler
{
    public function run(array $context): array
    {
        // baseline dari deterministic
        $deterministic = app(DeterministicScheduler::class);
        $baseAssignments = $deterministic->run($context);

        $population = $this->initialPopulation($context, $baseAssignments);

        for ($gen = 0; $gen < 49; $gen++) {

            $fitness = [];

            foreach ($population as $i => $chromosome) {
                $fitness[$i] = $this->fitness($chromosome, $context);
            }

            $elite = $this->eliteSelection($population, $fitness, 10);

            $population = $this->reproduce($elite, $context, 50);
        }

        $best = $this->best($population, $context);

        return $this->toAssignments($best, $context);
    }

    protected function initialPopulation(array $context, array $baseAssignments): array
    {
        $base = $this->assignmentToChromosome($baseAssignments);
        $population = [$base];

        for ($i = 0; $i < 49; $i++) {
            $copy = $base;

            $population[] = $this->mutateChromosome($copy, $context, 1);
        }

        return $population;
    }

    protected function assignmentToChromosome(array $assignments): array
    {
        $chromosome = [];

        foreach ($assignments as $assignment) {
            $chromosome[] = [
                'requirement_id' => $assignment['requirement_id'],
                'user_id' => $assignment['user_id']
            ];
        }

        return $chromosome;
    }

    protected function mutateChromosome(
        array $chromosome,
        array $context,
        int $changes = 1
    ): array {
        $userLoads = $this->calculateUserLoads($chromosome);

        for ($i = 0; $i < $changes; $i++) {

            $geneIndex = array_rand($chromosome);

            $reqId =
                $chromosome[$geneIndex]['requirement_id'];

            $currentUser =
                $chromosome[$geneIndex]['user_id'];


            $ranked = $this->getRankedCandidates(
                $context,
                $context['requirements'][$reqId],
                $userLoads
            );

            if (count($ranked) <= 1) {
                continue;
            }

            $sessionId =
                $context['requirements'][$reqId]['session_id'];

            $usedUsersInSession =
                $this->getUsedUsersInSession(
                    $chromosome,
                    $context,
                    $sessionId,
                    $geneIndex
                );

            $alternatives = array_values(
                array_filter(
                    $ranked,
                    fn($userId) => $userId !== $currentUser && !in_array($userId, $usedUsersInSession, true)
                )
            );

            if (empty($alternatives)) {
                continue;
            }

            $topN = array_slice($alternatives, 0, min(3, count($alternatives)));

            $newUserId =
                $topN[array_rand($topN)];

            $chromosome[$geneIndex]['user_id'] =
                $newUserId;

            $userLoads[$currentUser] = ($userLoads[$currentUser] ?? 0) - 1;
            $userLoads[$newUserId] = ($userLoads[$newUserId] ?? 0) + 1;
        }

        return $chromosome;
    }

    protected function getUsedUsersInSession(
        array $chromosome,
        array $context,
        string $sessionId,
        ?int $excludeGeneIndex = null
    ): array {

        $usedUsers = [];

        foreach ($chromosome as $index => $gene) {

            if ($excludeGeneIndex !== null && $index === $excludeGeneIndex) {
                continue;
            }

            $geneReqId = $gene['requirement_id'];

            $geneSessionId =
                $context['requirements'][$geneReqId]['session_id'];

            if ($geneSessionId !== $sessionId) {
                continue;
            }

            $usedUsers[] = $gene['user_id'];
        }

        return array_unique($usedUsers);
    }

    protected function getRankedCandidates(
        array $context,
        array $req,
        array $userLoads = []
    ): array {

        $sessionId = $req['session_id'];

        $candidates = [];

        foreach ($context['users'] as $userId => $user) {

            if (!isset($user['skills'][$req['skill_id']])) {
                continue;
            }

            $submitted =
                $context['user_submission'][$userId] ?? false;

            $available =
                $context['availability'][$userId][$sessionId] ?? false;

            $priority = 0;
            if ($submitted && $available) {
                $priority = 1;
            } elseif (!$submitted) {
                $priority = 2;
            } else {
                continue;
            }

            $candidates[] = [
                'user_id' => $userId,
                'priority' => $priority,
                'submitted' => $submitted,
                'available' => $available,
                'current_load' => $userLoads[$userId] ?? 0,
                'is_primary' => $user['skills'][$req['skill_id']]['is_primary'],
                'order' => $user['skills'][$req['skill_id']]['order'],
            ];
        }

        usort($candidates, function ($a, $b) {

            return
                $a['priority'] <=> $b['priority']
                ?: $a['current_load'] <=> $b['current_load']
                ?: $b['is_primary'] <=> $a['is_primary']
                ?: ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
        });

        return array_column($candidates, 'user_id');
    }

    protected function fitness(
        array $chromosome,
        array $context
    ): int {

        $score = 0;

        $sessionAssignments = [];
        $weeklyLoad = [];
        $userAssignments = [];

        foreach ($chromosome as $gene) {
            $reqId = $gene['requirement_id'];
            $userId = $gene['user_id'];
            $req = $context['requirements'][$reqId];
            $sessionId = $req['session_id'];
            $week = $context['sessions'][$sessionId]['week'];

            $user = $context['users'][$userId] ?? null;

            // jika user dari deterministic itu null, karna masuk kondisi terakhir
            if ($userId === null) {
                $score -= 5000;
                continue;
            }

            if (!$user) {
                $score -= 100000;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Skill Check
            |--------------------------------------------------------------------------
            */
            if (!isset($user['skills'][$req['skill_id']])) {
                $score -= 100000;
                continue;
            }

            $skill = $user['skills'][$req['skill_id']];

            /*
            |--------------------------------------------------------------------------
            | Double Assignment in Same Session
            |--------------------------------------------------------------------------
            */
            if (in_array(
                $userId,
                $sessionAssignments[$sessionId] ?? []
            )) {
                return -9999999;
            }

            /*
            |--------------------------------------------------------------------------
            | Availability
            |--------------------------------------------------------------------------
            */
            $submitted =
                $context['user_submission'][$userId] ?? false;

            $isAvailable =
                $context['availability'][$userId][$sessionId] ?? false;

            if ($submitted) {
                if (!$isAvailable) {
                    // submit tapi tidak available, langsung buang
                    return -9999999;
                } else {
                    // submit + available
                    $score += 200;
                }
                // reward submit
                $score += 100;
            }

            /*
            |--------------------------------------------------------------------------
            | Primary Skill
            |--------------------------------------------------------------------------
            */
            if ($skill['is_primary']) {
                $score += 30;
            } else {
                /*
                |--------------------------------------------------------------------------
                | Skill Order
                |--------------------------------------------------------------------------
                */
                $score += max(0, 20 - ($skill['order'] * 2));
            }

            /*
            |--------------------------------------------------------------------------
            | Weekly Load
            |--------------------------------------------------------------------------
            */
            $weeklyLoad[$userId][$week] =
                ($weeklyLoad[$userId][$week] ?? 0) + 1;

            $limit = $context['period']['max_per_week'];

            if ($weeklyLoad[$userId][$week] > $limit) {

                $overflow =
                    $weeklyLoad[$userId][$week] - $limit;

                $score -= $overflow * 25000;
            }

            /*
            |--------------------------------------------------------------------------
            | Tracking
            |--------------------------------------------------------------------------
            */
            $userAssignments[$userId] =
                ($userAssignments[$userId] ?? 0) + 1;

            $sessionAssignments[$sessionId][] = $userId;
        }

        /*
        |--------------------------------------------------------------------------
        | Fairness
        |--------------------------------------------------------------------------
        */
        if (!empty($userAssignments)) {
            $fairnessPenalty = 0;

            $totalAssignments = count(
                array_filter(
                    $chromosome,
                    fn($gene) => $gene['user_id'] !== null
                )
            );

            $totalUsers = count($context['users']);

            $targetLoad =
                $totalAssignments / max(1, $totalUsers);

            foreach ($context['users'] as $userId => $user) {

                $load =
                    $userAssignments[$userId] ?? 0;

                $fairnessPenalty += abs(
                    $load - $targetLoad
                );
            }

            $score -= $fairnessPenalty * 20;
        }

        return $score;
    }

    protected function calculateUserLoads(
        array $chromosome
    ): array {

        $loads = [];

        foreach ($chromosome as $gene) {

            $userId = $gene['user_id'];

            $loads[$userId] =
                ($loads[$userId] ?? 0) + 1;
        }

        return $loads;
    }

    protected function eliteSelection(
        array $population,
        array $fitness,
        int $eliteCount = 10
    ): array {

        arsort($fitness);

        $elite = [];

        foreach (array_keys($fitness) as $i) {

            $elite[] = $population[$i];

            if (count($elite) >= $eliteCount) {
                break;
            }
        }

        return $elite;
    }

    protected function reproduce(
        array $elite,
        array $context,
        int $populationSize = 50
    ): array {

        $population = $elite;

        while (count($population) < $populationSize) {

            $parent =
                $elite[array_rand($elite)];

            $child =
                $this->mutateChromosome(
                    $parent,
                    $context,
                    rand(1, 3)
                );

            $population[] = $child;
        }

        return $population;
    }

    protected function best(array $population, array $context): array
    {
        $best = null;
        $bestScore = -INF;

        foreach ($population as $chromosome) {
            $score = $this->fitness($chromosome, $context);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $chromosome;
            }
        }

        return $best;
    }

    protected function toAssignments(array $chromosome, array $context): array
    {
        $assignments = [];

        foreach ($chromosome as $gene) {
            $reqId = $gene['requirement_id'];

            $assignments[] = [
                'session_id' => $context['requirements'][$reqId]['session_id'],
                'requirement_id' => $reqId,
                'user_id' => $gene['user_id'],
            ];
        }

        return $assignments;
    }
}
