<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
                'name' => 'Developer',
                'username' => 'developer',
                'email' => 'developer@developer.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ]);

        // 2. User MULTI-ROLE
        // Jaya (Pastor Youth + Department Head)
        User::factory()
            ->create([
                'name' => 'Jaya',
                'username' => 'jaya',
                'email' => 'jaya@church.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ])
            ->assignRole(['pastor_youth', 'department_head']);
            
        // Mahenja (Division Leader + Core Team)
        User::factory()
            ->create([
                'name' => 'Mahenja',
                'username' => 'mahenja',
                'email' => 'mahenja@church.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ])
            ->assignRole(['division_leader', 'core_team']);

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
            ->count(10)
            ->create();
    }
}
