<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. User Sistem (Developer)
        User::factory()
            ->withRole('developer')
            ->create([
                'username' => 'developer',
                'nickname' => 'Developer',
                'first_name' => 'Dewa',
                'last_name' => 'Developer',
                'email' => 'developer@developer.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ]);

        User::factory()
            ->withRole('admin')
            ->create([
                'username' => 'admin',
                'nickname' => 'Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@gbipelita4.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ]);

        // 2. User MULTI-ROLE
        // Jaya (Pastor Youth + Department Head)
        User::factory()
            ->create([
                'username' => 'jaya',
                'nickname' => 'Jaya',
                'first_name' => 'Jayanta',
                'last_name' => 'Bangun',
                'email' => 'jaya@gbipelita4.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ])
            ->assignRole(['pastor_youth', 'department_head']);

        // Mahenja (Division Leader + Core Team)
        User::factory()
            ->create([
                'username' => 'mahenja',
                'nickname' => 'Mahenja',
                'first_name' => 'Dimas',
                'last_name' => 'S Panjaitan',
                'email' => 'mahenja@gbipelita4.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ])
            ->assignRole(['division_leader', 'core_team']);

        // Mahenja (Division Leader + Core Team)
        User::factory()
            ->create([
                'username' => 'laora',
                'nickname' => 'Laora',
                'first_name' => 'Laora',
                'last_name' => 'Simanjuntak',
                'email' => 'laora@gbipelita4.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ])
            ->assignRole(['volunteer', 'core_team']);

        // 3. User Single Role Lainnya
        // Leader
        User::factory()
            ->withRole('division_leader')
            ->count(3)
            ->create();

        // Coreteam
        User::factory()
            ->withRole('core_team')
            ->count(5)
            ->create();

        // Volunteer
        User::factory()
            ->withRole('volunteer')
            ->count(20)
            ->create();
    }
}
