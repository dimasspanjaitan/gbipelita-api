<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Services\UserVolunteerService;
use Database\Factories\UserFactory;
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
            'first_name' => 'Dimas',
            'last_name' => 'S Panjaitan',
            'email' => 'developer@developer.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active',
            'phone' => '081381174410',
            'address' => 'Jakarta Pusat',
            'birth_date' => '2001-10-23'
        ]);
        $laora = User::factory()->create([
            'username' => 'laora',
            'nickname' => 'Laora',
            'first_name' => 'Laora',
            'last_name' => 'Simanjuntak',
            'email' => 'laora@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active',
            'phone' => '081300000000',
            'address' => 'Medan Timur',
            'birth_date' => '2000-01-01'
        ]);
        $hani = User::factory()->create([
            'username' => 'hani',
            'nickname' => 'Hani',
            'first_name' => 'Yohania',
            'last_name' => 'Gultom',
            'email' => 'hani@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active',
            'phone' => '081311112222',
            'address' => 'Medan',
            'birth_date' => '1997-04-17'
        ]);
        $odde = User::factory()->create([
            'username' => 'odde',
            'nickname' => 'Odde',
            'first_name' => 'Odde',
            'last_name' => 'Hondro',
            'email' => 'odde@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active',
            'phone' => '081344445555',
            'address' => 'Medan Timur',
            'birth_date' => '1997-02-01'
        ]);
        $meli = User::factory()->create([
            'username' => 'meli',
            'nickname' => 'Meli',
            'first_name' => 'Meliyanti',
            'last_name' => 'Sibagariang',
            'email' => 'meli@gbipelita4.com',
            'password' => Hash::make('asdfasdf'),
            'status' => 'active',
            'phone' => '081355554444',
            'address' => 'Medan Timur',
            'birth_date' => '1998-09-28'
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

        $developerRole = Role::query()->where('name', 'Developer')->first();
        $adminRole = Role::query()->where('name', 'Admin')->first();
        $deptHeadRole = Role::query()->where('name', 'Department Head')->first();
        $divisionLeaderRole = Role::query()->where('name', 'Division Leader')->first();
        $volunteerRole = Role::query()->where('name', 'Volunteer')->first();

        // Developer
        if ($developerRole) {
            $developer->assignRole($developerRole);
        }

        // Admin + Volunteer
        if ($adminRole && $volunteerRole) {
            $laora->assignRole([$adminRole, $volunteerRole]);
        }

        //  Department Head + Division Leader + Volunteer
        if ($deptHeadRole && $divisionLeaderRole && $volunteerRole) {
            $hani->assignRole([$deptHeadRole, $divisionLeaderRole, $volunteerRole]);
        }

        // Division Leader + Volunteer
        if ($divisionLeaderRole && $volunteerRole) {
            $odde->assignRole([$divisionLeaderRole, $volunteerRole]);
            $meli->assignRole([$divisionLeaderRole, $volunteerRole]);
            $dennis->assignRole([$divisionLeaderRole, $volunteerRole]);
            $dandi->assignRole([$divisionLeaderRole, $volunteerRole]);
        }

        UserFactory::resetNicknames();

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
