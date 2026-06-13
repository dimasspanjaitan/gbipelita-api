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
            'app_name' => 'GBI Pelita Medan',
            'app_title' => 'GBI Pelita Medan',
            'app_description' => '2026: Hidup Berdampak Melalui Kebiasaan Baik',
            'app_logo' => '',
            'app_logo_alternative' => '',
            'app_favicon' => '',
            'phone' => '081375722746',
            'phone_name' => 'Pdt. Jayanta Bangun',
            'email' => 'gbipelita4medan@gmail.com',
            'address' => 'Jl. Pelita IV No.81, Sidorame Bar. II, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20233',
            'address_link' => 'https://www.google.com/maps/place/GBI+Pelita+MEDAN/@3.6077495,98.6828095,17z/data=!3m1!4b1!4m6!3m5!1s0x30313193d47f59c7:0xd5fd05c18e4d0f63!8m2!3d3.6077441!4d98.6853898!16s%2Fg%2F11b88tfnv5?entry=ttu&g_ep=EgoyMDI2MDYxMC4wIKXMDSoASAFQAw%3D%3D',
            'address_embed_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.911404792684!2d98.68280951150832!3d3.60774945014527!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30313193d47f59c7%3A0xd5fd05c18e4d0f63!2sGBI%20Pelita%20MEDAN!5e0!3m2!1sen!2sid!4v1781243184797!5m2!1sen!2sid',
            'whatsapp' => '081375722746',
            'whatsapp_name' => 'Pdt. Jayanta Bangun',
            'youtube' => 'https://www.youtube.com/@GBIPELITA',
            'instagram' => 'https://www.instagram.com/gbipelita4medan/',
            'facebook' => 'https://www.facebook.com/gbi.pelita/',
            'tiktok' => 'https://www.tiktok.com/',
            'visi' => 'Building Significant People',
            'misi' => 'Intimacy with God, i Care, Discipleship',
            'motto' => 'SMILE (Simplicity, Move Forward, Integrity, Love, Excellence)',
            'history' => 'GBI Pelita Medan adalah sebuah organisasi Gereja di bawah Sinode GBI sedunia. GBI Pelita Medan digembalakan oleh Pdt. dr. Suheri P. Gultom, M.A., M.Kes.',
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
            ],
            'operationals' => [
                ['name' => 'Senin - Sabtu', 'time' => '09.00 - 16.00 WIB'],
                ['name' => 'Minggu', 'time' => '09.00 - 21.30 WIB'],
            ]
        ]);
    }
}
