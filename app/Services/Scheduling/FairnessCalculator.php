<?php

namespace App\Services\Scheduling;

class FairnessCalculator
{
    public function score(array $context, string $userId): int
    {
        return array_sum(
            $context['tracking']['weekly_load'][$userId] ?? []
        );
    }
}
