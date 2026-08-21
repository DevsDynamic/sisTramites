<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->firstOrCreate([], [
            'company_name' => 'Empresa cliente',
            'primary_color' => '#0d6efd',
            'sidebar_color' => '#1e293b',
            'sidebar_text_color' => '#ffffff',
        ]);
    }
}
