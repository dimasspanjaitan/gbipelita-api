<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. User Sistem (Developer)
        $developer = User::factory()->create([
            'username' => 'developer',
            'nickname' => 'Developer',
            'first_name' => 'Dewa',
            'last_name' => 'Developer',
            'email' => 'developer@developer.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $developerRole = Role::where('name', 'Developer')->first();
        if ($developerRole) {
            $developer->assignRole($developerRole);
        }

        // 2. Admin
        $admin = User::factory()->create([
            'username' => 'admin',
            'nickname' => 'Admin',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        // 3. User MULTI-ROLE
        // Jaya (Pastor Youth + Department Head)
        $jaya = User::factory()->create([
            'username' => 'jaya',
            'nickname' => 'Jaya',
            'first_name' => 'Jayanta',
            'last_name' => 'Bangun',
            'email' => 'jaya@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $pastorYouthRole = Role::where('name', 'Youth Pastor')->first();
        $deptHeadRole = Role::where('name', 'Department Head')->first();

        if ($pastorYouthRole && $deptHeadRole) {
            $jaya->assignRole([$pastorYouthRole, $deptHeadRole]);
        }

        // Mahenja (Division Leader + Core Team)
        $mahenja = User::factory()->create([
            'username' => 'mahenja',
            'nickname' => 'Mahenja',
            'first_name' => 'Dimas',
            'last_name' => 'S Panjaitan',
            'email' => 'mahenja@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $divisionLeaderRole = Role::where('name', 'Division Leader')->first();
        $coreTeamRole = Role::where('name', 'Core Team')->first();

        if ($divisionLeaderRole && $coreTeamRole) {
            $mahenja->assignRole([$divisionLeaderRole, $coreTeamRole]);
        }

        // Laora (Volunteer + Core Team)
        $laora = User::factory()->create([
            'username' => 'laora',
            'nickname' => 'Laora',
            'first_name' => 'Laora',
            'last_name' => 'Simanjuntak',
            'email' => 'laora@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $volunteerRole = Role::where('name', 'Volunteer')->first();

        if ($volunteerRole && $coreTeamRole) {
            $laora->assignRole([$volunteerRole, $coreTeamRole]);
        }

        // 4. User Single Role Lainnya
        // Leader
        User::factory()
            ->count(3)
            ->create()
            ->each(function ($user) use ($divisionLeaderRole) {
                if ($divisionLeaderRole) {
                    $user->assignRole($divisionLeaderRole);
                }
            });

        // Coreteam
        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) use ($coreTeamRole) {
                if ($coreTeamRole) {
                    $user->assignRole($coreTeamRole);
                }
            });

        // Volunteer
        User::factory()
            ->count(100)
            ->create()
            ->each(function ($user) use ($volunteerRole) {
                if ($volunteerRole) {
                    $user->assignRole($volunteerRole);
                }
            });
    }
}
