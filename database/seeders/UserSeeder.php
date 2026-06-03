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

        $developerRole = Role::query()->where('name', 'Developer')->first();
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

        $adminRole = Role::query()->where('name', 'Admin')->first();
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

        // Samuel (Department Head)
        $samuel = User::factory()->create([
            'username' => 'samuel',
            'nickname' => 'Samuel',
            'first_name' => 'Samuel',
            'last_name' => 'Sembiring',
            'email' => 'samuel@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $pastorYouthRole = Role::query()->where('name', 'Youth Pastor')->first();
        $deptHeadRole = Role::query()->where('name', 'Department Head')->first();

        if ($pastorYouthRole && $deptHeadRole) {
            $jaya->assignRole([$pastorYouthRole, $deptHeadRole]);
            $samuel->assignRole($deptHeadRole);
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

        // Division Leader
        $hani = User::factory()->create([
            'username' => 'hani',
            'nickname' => 'Hani',
            'first_name' => 'Yohania',
            'last_name' => 'Gultom',
            'email' => 'hani@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);
        $odde = User::factory()->create([
            'username' => 'odde',
            'nickname' => 'Odde',
            'first_name' => 'Odde',
            'last_name' => 'Hondro',
            'email' => 'odde@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);
        $meli = User::factory()->create([
            'username' => 'meli',
            'nickname' => 'Meli',
            'first_name' => 'Meliyanti',
            'last_name' => 'Sibagariang',
            'email' => 'meli@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);
        $dennis = User::factory()->create([
            'username' => 'dennis',
            'nickname' => 'Dennis',
            'first_name' => 'Dennis',
            'last_name' => 'Marpaung',
            'email' => 'dennis@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);
        $dandi = User::factory()->create([
            'username' => 'dandi',
            'nickname' => 'Dandi',
            'first_name' => 'Dandi',
            'last_name' => 'Steven',
            'email' => 'dandi@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active'
        ]);

        $divisionLeaderRole = Role::query()->where('name', 'Division Leader')->first();
        $coreTeamRole = Role::query()->where('name', 'Core Team')->first();

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

        $volunteerRole = Role::query()->where('name', 'Volunteer')->first();

        if($divisionLeaderRole && $volunteerRole) {
            $hani->assignRole([$divisionLeaderRole, $volunteerRole]);
            $odde->assignRole([$divisionLeaderRole, $volunteerRole]);
            $meli->assignRole([$divisionLeaderRole, $volunteerRole]);
            $dennis->assignRole([$divisionLeaderRole, $volunteerRole]);
            $dandi->assignRole([$divisionLeaderRole, $volunteerRole]);
        }

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
