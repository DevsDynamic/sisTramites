<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Trial',
            'price' => 0,
            'max_users' => 1,
        ]);

        Plan::create([
            'name' => 'Básico',
            'price' => 49.90,
            'max_users' => 3,
        ]);

        Plan::create([
            'name' => 'Premium',
            'price' => 99.90,
            'max_users' => 10,
        ]);
    }
}
