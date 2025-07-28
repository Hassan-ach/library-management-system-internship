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
        //
        Setting::create([
            'DUREE_EMPRUNT_MAX' => 8,
            'NOMBRE_EMPRUNTS_MAX' => 1,
            'DUREE_RESERVATION' => 3,
        ]);
    }
}
