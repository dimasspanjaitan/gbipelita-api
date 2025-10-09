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
        $departments = Department::whereIn('alias', ['EW', 'PA'])
            ->get()
            ->keyBy('alias');

        if ($departments->count() < 2) {
            $this->command->error('Department EW dan PA belum tersedia. Jalankan DepartmentSeeder terlebih dahulu.');
            return;
        }

        $deptEW = $departments['EW']->id;
        $deptPA = $departments['PA']->id;

        // Ambil user spesifik
        $mahenja = User::where('username', 'mahenja')->first();
        $laora   = User::where('username', 'laora')->first();

        // Assign manual ke dua departemen
        foreach ([$mahenja, $laora] as $user) {
            if ($user) {
                $user->departments()->syncWithoutDetaching([$deptEW, $deptPA]);
            }
        }

        // Ambil semua user dengan role tertentu
        $roles = ['division_leader', 'core_team', 'volunteer'];

        $users = User::whereHas('roles', fn($q) => $q->whereIn('name', $roles))->get();

        foreach ($users as $user) {
            $user->departments()->syncWithoutDetaching([$deptEW, $deptPA]);
        }

        $this->command->info('UserDepartmentSeeder selesai dijalankan.');
    }
}
