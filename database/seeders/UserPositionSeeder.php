<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPosition;
use Illuminate\Database\Seeder;

class UserPositionSeeder extends Seeder
{
    public function run(): void
    {
        $departmentHeadRole = Role::query()->where('name', 'Department Head')->firstOrFail();
        $divisionLeaderRole = Role::query()->where('name', 'Division Leader')->firstOrFail();

        $ewDepartment = Department::query()->where('alias', 'EW')->firstOrFail();

        $musicDivision = Division::query()->where('name', 'Musik')->firstOrFail();
        $multimediaDivision = Division::query()->where('name', 'Multimedia')->firstOrFail();
        $vocalDivision = Division::query()->where('name', 'Vocal')->firstOrFail();
        $choirDivision = Division::query()->where('name', 'Choir')->firstOrFail();
        $soundSystemDivision = Division::query()->where('name', 'Sound System')->firstOrFail();

        $hani = User::query()->where('username', 'hani')->firstOrFail();
        $odde = User::query()->where('username', 'odde')->firstOrFail();
        $meli = User::query()->where('username', 'meli')->firstOrFail();
        $dennis = User::query()->where('username', 'dennis')->firstOrFail();
        $dandi = User::query()->where('username', 'dandi')->firstOrFail();

        UserPosition::updateOrCreate(
            [
                'user_id' => $hani->id,
                'role_id' => $departmentHeadRole->id,
                'department_id' => $ewDepartment->id,
            ],
            [
                'division_id' => null,
            ]
        );

        UserPosition::updateOrCreate(
            [
                'user_id' => $hani->id,
                'role_id' => $divisionLeaderRole->id,
                'division_id' => $musicDivision->id,
            ],
            [
                'department_id' => null,
            ]
        );

        UserPosition::updateOrCreate(
            [
                'user_id' => $odde->id,
                'role_id' => $divisionLeaderRole->id,
                'division_id' => $vocalDivision->id,
            ],
            [
                'department_id' => null,
            ]
        );

        UserPosition::updateOrCreate(
            [
                'user_id' => $meli->id,
                'role_id' => $divisionLeaderRole->id,
                'division_id' => $choirDivision->id,
            ],
            [
                'department_id' => null,
            ]
        );

        UserPosition::updateOrCreate(
            [
                'user_id' => $dennis->id,
                'role_id' => $divisionLeaderRole->id,
                'division_id' => $multimediaDivision->id,
            ],
            [
                'department_id' => null,
            ]
        );

        UserPosition::updateOrCreate(
            [
                'user_id' => $dandi->id,
                'role_id' => $divisionLeaderRole->id,
                'division_id' => $soundSystemDivision->id,
            ],
            [
                'department_id' => null,
            ]
        );
    }
}
