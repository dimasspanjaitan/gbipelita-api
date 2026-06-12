<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            // 'app_name' => 'GBI Pelita Medan',
            'app_name' => 'GBI Pelita',
            'app_title' => 'GBI Pelita Medan',
            'app_description' => '2026: Hidup Berdampak Melalui Kebiasaan Baik',
            'app_logo' => '/logo.png',
            'app_logo_alternative' => '/logo.png',
            'app_favicon' => '/favicon.ico',
            'address' => 'Jl. Pelita IV No. 59/81, Sidorame Barat II, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20233',
            'phone' => '081375722746',
            'email' => 'gbipelita4medan@gmail.com',
            'youtube' => 'https://www.youtube.com/@GBIPELITA',
            'instagram' => 'https://www.instagram.com/gbipelita4medan/',
            'facebook' => 'https://www.facebook.com/gbi.pelita/',
            'whatsapp' => '081375722746',
            'visi' => 'Building Significant People',
            'misi' => 'Intimacy with God, i Care, Discipleship',
            'motto' => 'SMILE (Simplicity, Move Forward, Integrity, Love, Excellence)',
            'history' => 'GBI Pelita Medan adalah sebuah organisasi Gereja di bawah Sinode GBI sedunia. GBI Pelita Medan digembalakan oleh Pdt. dr. Suheri P. Gultom, M.A., M.Kes.',
            'data' => [
                'main' => [
                    'location' => 'Main Hall',
                    'services' => [
                        ['name' => 'Ibadah 1', 'time' => '09.00 - 10.30 WIB'],
                        ['name' => 'Ibadah 2', 'time' => '11.00 - 12.30 WIB'],
                        ['name' => 'Ibadah 3 (LICC Youth)', 'time' => '14.00 - 15.30 WIB'],
                        ['name' => 'Ibadah 4', 'time' => '16.00 - 17.30 WIB'],
                        ['name' => 'Ibadah 5', 'time' => '18.00 - 19.30 WIB'],
                        ['name' => 'Ibadah 6 (LICC Pro)', 'time' => '20.00 - 21.30 WIB'],
                    ]
                ],
                'nextgen' => [
                    'location' => 'Gedung Starkids Lt. 4',
                    'services' => [
                        ['name' => 'Ibadah 1', 'time' => '11.00 - 12.30 WIB'],
                    ]
                ],
                'starkids' => [
                    'location' => 'Gedung Starkids',
                    'services' => [
                        ['name' => 'Ibadah 1', 'time' => '09.00 - 10.30 WIB'],
                        ['name' => 'Ibadah 2', 'time' => '11.00 - 12.30 WIB'],
                        ['name' => 'Ibadah 4', 'time' => '16.00 - 17.30 WIB'],
                        ['name' => 'Ibadah 5', 'time' => '18.00 - 19.30 WIB'],
                    ]
                ],
                'family_cell' => [
                    ['name' => 'KELUARGA', 'time' => 'Hub. Leader GA'],
                    ['name' => 'MILENIAL', 'time' => 'Hub. Leader GA'],
                    ['name' => 'LICC PRO', 'time' => 'Kamis, 19.30 - 21.00 WIB'],
                    ['name' => 'LICC YOUTH', 'time' => 'Kamis, 19.30 - 21.00 WIB'],
                    ['name' => 'LICC NEXTGEN', 'time' => 'Sabtu, 19.00 - 20.30 WIB'],
                    ['name' => 'LICC STARTKIDS', 'time' => 'Rabu, 17.00 - 18.30 WIB'],
                ]
            ],
        ]);
    }
}
