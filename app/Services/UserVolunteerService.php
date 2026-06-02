<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserVolunteerService
{
    public function getDepartmentVolunteers(
        string $departmentId
    ): Collection {

        return User::query()
            ->role('volunteer')
            ->where('status', 'active')
            ->whereRelation(
                'departments',
                'departments.id',
                $departmentId
            )
            ->get();
    }
}