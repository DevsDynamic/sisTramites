<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Esencial',
                'description' => 'Para equipos pequeños que inician su gestión documentaria.',
                'price' => 0,
                'max_users' => 5,
                'max_areas' => 3,
                'max_signatures' => 3,
                'max_documents' => 500,
                'max_workflows' => 2,
                'max_storage_mb' => 2048,
                'features' => ['Gestión documentaria', 'Firmas digitales', 'Flujos básicos'],
                'sort_order' => 10,
            ],
            [
                'name' => 'Profesional',
                'description' => 'Para áreas que requieren automatización y trazabilidad completa.',
                'price' => 0,
                'max_users' => 25,
                'max_areas' => 15,
                'max_signatures' => 15,
                'max_documents' => 5000,
                'max_workflows' => 15,
                'max_storage_mb' => 20480,
                'features' => ['Gestión documentaria', 'Firmas digitales', 'Flujos de aprobación', 'Reportes y panel de control'],
                'sort_order' => 20,
            ],
            [
                'name' => 'Empresa',
                'description' => 'Para organizaciones con alto volumen y múltiples áreas.',
                'price' => 0,
                'max_users' => 100,
                'max_areas' => 50,
                'max_signatures' => 60,
                'max_documents' => 50000,
                'max_workflows' => 100,
                'max_storage_mb' => 102400,
                'features' => ['Todas las funciones Profesional', 'Mayor capacidad', 'Flujos avanzados', 'Soporte prioritario'],
                'sort_order' => 30,
            ],
        ];

        foreach ($plans as $data) {
            Plan::updateOrCreate(['name' => $data['name']], $data);
        }

        $settings = Setting::first();
        if ($settings && ! $settings->plan_id) {
            $plan = Plan::where('name', 'Profesional')->first();
            $settings->update([
                'plan_id' => $plan?->id,
                'plan_name' => $plan?->name ?? $settings->plan_name,
            ]);
        }
    }
}
