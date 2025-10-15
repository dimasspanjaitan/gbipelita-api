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
            'app_name' => 'GBI AJA',
            'app_title' => 'GBI Pelita Medan',
            'app_description' => '2025: Hidup Berdampak Melalui Kebiasaan Baik',
            'app_logo' => '#',
            'app_logo_alternative' => '#',
            'app_favicon' => '#',
            // 'address' => 'Jl. Pelita IV No. 59/81, Sidorame Barat II, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20233',
            'address' => 'Jl. Paling capek seduia',
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
        ]);
    }
}
