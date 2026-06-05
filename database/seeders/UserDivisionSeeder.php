<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\DB;

class UserDivisionSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus dulu pivot biar gak duplicate
        DB::table('user_division')->truncate();

        // Ambil semua divisi yang dibutuhkan
        $divisions = Division::query()
            ->where('status', 'active')
            ->whereIn('name', [
                'Music',
                'Multimedia',
                'Vocal',
                'Choir',
                'Sound System'
            ])->get()->keyBy('name');

        // Validasi kalau ada yang belum ada
        foreach (['Music', 'Multimedia', 'Vocal', 'Choir', 'Sound System'] as $name) {
            if (!isset($divisions[$name])) {
                $this->command->error("Division '{$name}' tidak ditemukan. Pastikan DivisionSeeder sudah dijalankan.");
                return;
            }
        }

        // === 1. Laora ke divisi Multimedia ===
        $laora = User::query()->where('username', 'laora')->first();
        if ($laora) {
            $laora->divisions()->syncWithoutDetaching([
                $divisions['Multimedia']->id => ['priority' => 1],
            ]);
        }

        $userDivisionCount = [];
        $maxDivisionPerUser = 2;
        $users = User::role('Volunteer')->get()->shuffle();

        // Pastikan cukup user
        if ($users->count() < 100) {
            $this->command->error('Jumlah user volunteer kurang dari 100.');
            return;
        }

        // Mapping kebutuhan
        $distribution = collect([
            'Music' => 60,
            'Vocal' => 40,
            'Multimedia' => 20,
            'Choir' => 20,
            'Sound System' => 4,
        ])->sortDesc();

        // Semua user wajib punya minimal 1 division
        foreach ($users as $user) {
            $randomDivision = $divisions->random();

            $user->divisions()->syncWithoutDetaching([
                $randomDivision->id => ['priority' => 1],
            ]);
        }

        foreach ($distribution as $divisionName => $total) {
            $eligibleUsers = $users->filter(function ($user) use ($userDivisionCount, $maxDivisionPerUser) {
                return ($userDivisionCount[$user->id] ?? 0) < $maxDivisionPerUser;
            });

            if ($eligibleUsers->count() < $total) {
                $this->command->warn("User tidak cukup untuk {$divisionName}, mengambil sisanya tanpa batas.");
                $eligibleUsers = $users;
            }

            $selectedUsers = $eligibleUsers->shuffle()->take($total);

            foreach ($selectedUsers as $user) {
                $user->divisions()->syncWithoutDetaching([
                    $divisions[$divisionName]->id => ['priority' => 1],
                ]);

                $userDivisionCount[$user->id] = ($userDivisionCount[$user->id] ?? 0) + 1;
            }
        }

        $this->command->info('UserDivision seeding completed successfully.');
    }
}
