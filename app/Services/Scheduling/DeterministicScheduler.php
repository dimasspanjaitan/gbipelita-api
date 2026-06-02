<?php

namespace App\Services\Scheduling;

class DeterministicScheduler
{
    protected FairnessCalculator $fairness;

    public function __construct(FairnessCalculator $fairness)
    {
        $this->fairness = $fairness;
    }

    public function run(array &$context): array
    {
        $assignments = [];

        foreach ($context['requirements'] as $requirementId => &$req) {

            // Stage 1: normal limit (max_per_week)
            $this->fillRequirement(
                $context,
                $req,
                $assignments,
                $context['period']['max_per_week']
            );

            // Stage 2: overflow limit (max_overflow_per_week)
            if ($req['assigned'] < $req['required_qty']) {
                $this->fillRequirement(
                    $context,
                    $req,
                    $assignments,
                    $context['period']['max_overflow_per_week']
                );
            }
        }

        return $assignments;
    }

    protected function fillRequirement(
        array &$context,
        array &$req,
        array &$assignments,
        int $weeklyLimit
    ): void {

        $attemptGuard = 0;

        while ($req['assigned'] < $req['required_qty']) {

            $candidate = $this->findCandidate(
                $context,
                $req,
                $weeklyLimit
            );

            if (!$candidate) {
                $assignments[] = [
                    'session_id' => $req['session_id'],
                    'requirement_id' => $req['id'],
                    'user_id' => null,
                ];

                $req['assigned']++;
                continue;
            }

            $this->assign($context, $req, $candidate);

            $assignments[] = [
                'session_id' => $req['session_id'],
                'requirement_id' => $req['id'],
                'user_id' => $candidate,
            ];

            // prevent infinite loop in extreme edge case
            if (++$attemptGuard > 1000) {
                break;
            }
        }
    }

    protected function findCandidate(
        array &$context,
        array $req,
        int $weeklyLimit
    ): ?string {

        $sessionId = $req['session_id'];
        $skillId = $req['skill_id'];
        $week = $context['sessions'][$sessionId]['week'];

        $candidates = [];

        foreach ($context['users'] as $userId => $user) {

            // skill check
            if (!isset($user['skills'][$skillId])) {
                continue;
            }

            // already assigned in this session?
            if (in_array(
                $userId,
                $context['tracking']['session_assignments'][$sessionId] ?? []
            )) {
                continue;
            }

            $weeklyLoad = $context['tracking']['weekly_load'][$userId][$week] ?? 0;

            // weekly limit check
            if ($weeklyLoad >= $weeklyLimit) {
                continue;
            }

            // availability logic
            $submitted = $context['user_submission'][$userId] ?? false;

            $isAvailable = $context['availability'][$userId][$sessionId] ?? false;

            if ($submitted && !$isAvailable) {
                // user sengaja tidak pilih session ini
                continue;
            }

            $fairnessScore = $this->fairness->score($context, $userId);

            $priority = $submitted ? 1 : 2;

            $candidates[] = [
                'user_id' => $userId,
                'priority' => $priority,
                'is_primary' => $user['skills'][$skillId]['is_primary'],
                'order' => $user['skills'][$skillId]['order'],
                'weekly_load' => $weeklyLoad,
                'fairness_score' => $fairnessScore,
            ];
        }

        if (empty($candidates)) {
            return null;
        }

        usort($candidates, function ($a, $b) {

            return
                $a['priority'] <=> $b['priority']
                ?: $a['weekly_load'] <=> $b['weekly_load']
                ?: $a['fairness_score'] <=> $b['fairness_score']
                ?: $b['is_primary'] <=> $a['is_primary']
                ?: ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
        });

        return $candidates[0]['user_id'];
    }

    protected function assign(
        array &$context,
        array &$req,
        string $userId
    ): void {

        $sessionId = $req['session_id'];
        $week = $context['sessions'][$sessionId]['week'];

        // increment weekly load
        $context['tracking']['weekly_load'][$userId][$week] =
            ($context['tracking']['weekly_load'][$userId][$week] ?? 0) + 1;

        // mark session assignment
        $context['tracking']['session_assignments'][$sessionId][] = $userId;

        $req['assigned']++;
    }
}
