<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'key'   => 'app_name',
            'value' => 'Siakad Library'
        ]);
        
        // Bisa tambah setting lain di sini nanti, misal alamat, no telp, logo, dll.
    }
}