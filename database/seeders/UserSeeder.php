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
        // User Developer
        User::factory()
            ->withRole('developer')
            ->create([
                'name' => 'Developer',
                'username' => 'developer',
                'email' => 'developer@developer.com',
                'password' => bcrypt('asdfasdf'),
                'status' => 'active'
            ]);

        // Leader
        User::factory()
            ->withRole('leader')
            ->count(5)
            ->create();

        // Coreteam
        User::factory()
            ->withRole('coreteam')
            ->count(10)
            ->create();

        // Volunteer
        User::factory()
            ->withRole('volunteer')
            ->count(20)
            ->create();
    }
}
