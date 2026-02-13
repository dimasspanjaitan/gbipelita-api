<?php

namespace App\Services\Scheduling;

class FairnessCalculator
{
    public function score(array $context, string $userId): float
    {
        $deptId = $context['users'][$userId]['department_id'];

        $departmentLoads = $context['tracking']['department_load'][$deptId];

        $totalSlots = array_sum($departmentLoads);
        $userCount = count($departmentLoads);

        if ($userCount === 0) {
            return 0;
        }

        $target = $totalSlots / $userCount;

        $userLoad = $departmentLoads[$userId] ?? 0;

        return abs($userLoad - $target);
    }
}
