<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Plan;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ruc' => 'required',
            'domain' => 'required|unique:domains,domain',
            'plan_id' => 'required|exists:plans,id',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $tenant = Tenant::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'ruc' => $request->ruc,
            'email' => $request->email,
            'plan_id' => $request->plan_id,
            'starts_at' => now(),
            'status' => 'active',
        ]);

        $tenant->domains()->create([
            'domain' => $request->domain,
        ]);

        // 🔥 Inicializar tenant
        tenancy()->initialize($tenant);

        // migraciones
        \Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id]
        ]);

        // 👇 crear usuario con datos del formulario
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        tenancy()->end();

        return back()->with('success', 'Cliente creado correctamente');
    }
}
