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
        $divisions = Division::whereIn('name', [
            'Musik',
            'Multimedia',
            'LICC Youth',
        ])->get()->keyBy('name');

        // Validasi kalau ada yang belum ada
        foreach (['Musik', 'Multimedia', 'LICC Youth'] as $name) {
            if (!isset($divisions[$name])) {
                $this->command->error("Division '{$name}' tidak ditemukan. Pastikan DivisionSeeder sudah dijalankan.");
                return;
            }
        }

        // === 1. Mahenja ke divisi Musik ===
        $mahenja = User::where('username', 'mahenja')->first();
        if ($mahenja) {
            $mahenja->divisions()->syncWithoutDetaching([
                $divisions['Musik']->id => ['priority' => 1],
                $divisions['LICC Youth']->id => ['priority' => 1],
            ]);
        }

        // === 2. Laora ke divisi Multimedia ===
        $laora = User::where('username', 'laora')->first();
        if ($laora) {
            $laora->divisions()->syncWithoutDetaching([
                $divisions['Multimedia']->id => ['priority' => 1],
                $divisions['LICC Youth']->id => ['priority' => 1],
            ]);
        }

        // === 3. User dengan role tertentu (random assignment) ===
        $roles = ['division_leader', 'core_team', 'volunteer'];

        // Ambil user yang punya salah satu dari role di atas
        $users = User::role($roles)->get();

        // Ambil semua divisi di bawah departemen "EW"
        $divisionsEW = Division::whereHas('department', fn($q) => $q->where('alias', 'EW'))->get();

        if ($divisionsEW->isEmpty()) {
            $this->command->error('Tidak ada division di bawah department EW.');
            return;
        }

        foreach ($users as $user) {
            // Pilih satu divisi random dari EW
            $randomDivision = $divisionsEW->random();

            $user->divisions()->syncWithoutDetaching([
                $randomDivision->id => ['priority' => 1],
                $divisions['LICC Youth']->id => ['priority' => 1],
            ]);
        }

        $this->command->info('UserDivision seeding completed successfully.');
    }
}
