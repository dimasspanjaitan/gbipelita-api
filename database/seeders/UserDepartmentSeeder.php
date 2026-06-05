<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;

class UserDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Department yang dibutuhkan
        $department = Department::query()
            ->where('alias', 'EW')
            ->first();

        if (!$department) {
            $this->command->error('Department EW belum tersedia. Jalankan DepartmentSeeder terlebih dahulu.');
            return;
        }

        $laora   = User::query()->where('username', 'laora')->first();
        if ($laora) {
            $laora->departments()->syncWithoutDetaching($department->id);
        }

        // Ambil semua user dengan role tertentu
        $roles = ['Division Leader', 'Volunteer'];
        $users = User::query()
            ->whereHas('roles', fn($q) => $q->whereIn('name', $roles))
            ->where('status', 'active')
            ->get();

        foreach ($users as $user) {
            $user->departments()->syncWithoutDetaching($department->id);
        }
    }
}
