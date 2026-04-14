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

        for ($gen = 0; $gen < 20; $gen++) {

            $fitness = [];

            foreach ($population as $i => $chromosome) {
                $fitness[$i] = $this->fitness($chromosome, $context);
            }

            $selected = $this->selection($population, $fitness);

            $offspring = $this->crossover($selected);

            $population = $this->mutation($offspring, $context);
        }

        $best = $this->best($population, $context);

        return $this->toAssignments($best, $context);
    }
    
    protected function initialPopulation(array $context, array $baseAssignments): array
    {
        $population = [];

        // convert deterministic ke chromosome
        $population[] = $this->assignmentToChromosome($baseAssignments);

        // sisanya random
        for ($i = 0; $i < 9; $i++) {
            $population[] = $this->randomChromosome($context);
        }

        return $population;
    }

    protected function assignmentToChromosome(array $assignments): array
    {
        $chromosome = [];

        foreach ($assignments as $a) {
            $chromosome[$a['requirement_id']] = $a['user_id'];
        }

        return $chromosome;
    }

    protected function randomChromosome(array $context): array
    {
        $chromosome = [];

        foreach ($context['requirements'] as $reqId => $req) {

            $candidates = $this->getCandidates($context, $req);

            if (!empty($candidates)) {
                $chromosome[$reqId] = $candidates[array_rand($candidates)];
            }
        }

        return $chromosome;
    }

    protected function getCandidates(array $context, array $req): array
    {
        $candidates = [];

        foreach ($context['users'] as $userId => $user) {

            if (!isset($user['skills'][$req['skill_id']])) {
                continue;
            }

            $candidates[] = $userId;
        }

        return $candidates;
    }

    protected function fitness(array $chromosome, array $context): int
    {
        $score = 0;

        $sessionAssignments = [];
        $weeklyLoad = [];

        foreach ($chromosome as $reqId => $userId) {

            $req = $context['requirements'][$reqId];
            $sessionId = $req['session_id'];
            $week = $context['sessions'][$sessionId]['week'];

            // ❌ skill
            if (!isset($context['users'][$userId]['skills'][$req['skill_id']])) {
                $score -= 100;
                continue;
            }

            // ❌ double session
            if (in_array($userId, $sessionAssignments[$sessionId] ?? [])) {
                $score -= 50;
            }

            // ❌ availability
            $submitted = $context['user_submission'][$userId] ?? false;
            $isAvailable = $context['availability'][$userId][$sessionId] ?? false;

            if ($submitted && !$isAvailable) {
                $score -= 100;
            }

            // ❌ weekly overload
            $weeklyLoad[$userId][$week] =
                ($weeklyLoad[$userId][$week] ?? 0) + 1;

            if ($weeklyLoad[$userId][$week] > 2) {
                $score -= 20;
            }

            // ✔ reward
            $score += 10;

            $sessionAssignments[$sessionId][] = $userId;
        }

        return $score;
    }

    protected function selection(array $population, array $fitness): array
    {
        arsort($fitness);

        $selected = [];

        foreach (array_keys($fitness) as $i) {
            $selected[] = $population[$i];
            if (count($selected) >= 5) break;
        }

        return $selected;
    }

    protected function crossover(array $parents): array
    {
        $offspring = [];

        for ($i = 0; $i < count($parents); $i += 2) {

            $a = $parents[$i];
            $b = $parents[$i + 1] ?? $parents[0];

            $child = [];

            foreach ($a as $reqId => $userId) {
                $child[$reqId] = rand(0, 1) ? $a[$reqId] : ($b[$reqId] ?? $userId);
            }

            $offspring[] = $child;
        }

        return $offspring;
    }

    protected function mutation(array $population, array $context): array
    {
        foreach ($population as &$chromosome) {

            if (rand(0, 100) < 20) {

                $reqId = array_rand($chromosome);

                $candidates = $this->getCandidates(
                    $context,
                    $context['requirements'][$reqId]
                );

                if (!empty($candidates)) {
                    $chromosome[$reqId] = $candidates[array_rand($candidates)];
                }
            }
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

        foreach ($chromosome as $reqId => $userId) {

            $assignments[] = [
                'session_id' => $context['requirements'][$reqId]['session_id'],
                'requirement_id' => $reqId,
                'user_id' => $userId,
            ];
        }

        return $assignments;
    }
}
