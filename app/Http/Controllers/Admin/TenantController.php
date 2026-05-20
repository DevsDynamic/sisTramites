<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('plan')
            ->latest()
            ->paginate(10);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $plans = Plan::where('is_active', true)
            ->get();

        return view(
            'admin.tenants.create',
            compact('plans')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'ruc'           => 'required|digits:11|unique:tenants,ruc',
            'email'         => 'required|email|unique:tenants,email',
            'domain'        => ['required', 'alpha_dash', function ($attr, $value, $fail) {
                $domain = $value . '.' . config('saas.central_domain');
                if (\App\Models\Domain::where('domain', $domain)->exists()) {
                    $fail('El subdominio ya está en uso.');
                }
            }],
            'plan_id'     => 'required|exists:plans,id',
            'admin_email' => 'required|email',
            'password'    => 'required|min:8',
        ]);

        // Solo el proceso de creación va dentro del try-catch
        try {
            $plan = Plan::findOrFail($request->plan_id);

            $tenant = Tenant::create([
                'business_name' => $request->business_name,
                'trade_name'    => $request->trade_name,
                'ruc'           => $request->ruc,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'plan_id'       => $plan->id,
                'status'        => 'active',
                'starts_at'     => now(),
                'expires_at'    => now()->addDays($plan->duration_days),
                'settings'      => [
                    'admin_email'   => $request->admin_email,
                    'temp_password' => bcrypt($request->password),
                ],
            ]);

            $tenant->domains()->create([
                'domain' => $request->domain . '.' . config('saas.central_domain'),
            ]);

            return redirect()
                ->route('tenants.index')
                ->with('success', 'Cliente creado correctamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Tenant $tenant)
    {
        $plans = Plan::where(
            'is_active',
            true
        )->get();

        return view(
            'admin.tenants.edit',
            compact(
                'tenant',
                'plans'
            )
        );
    }

    public function update(
        Request $request,
        Tenant $tenant
    ) {

        $request->validate([
            'business_name' => 'required|string|max:255',

            'ruc' => [
                'required',
                'digits:11',
                'unique:tenants,ruc,' . $tenant->id
            ],

            'email' => [
                'required',
                'email',
                'unique:tenants,email,' . $tenant->id
            ],

            'phone' => 'nullable|string|max:20',

            'plan_id' => 'required|exists:plans,id',

            'status' => 'required|in:active,suspended,expired',

            'expires_at' => 'nullable|date',
        ]);

        try {

            $tenant->update([
                'business_name' => $request->business_name,
                'trade_name'    => $request->trade_name,
                'ruc'           => $request->ruc,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'plan_id'       => $request->plan_id,
                'status'        => $request->status,
                'expires_at'    => $request->expires_at,
            ]);

            return redirect()
                ->route('tenants.index')
                ->with(
                    'success',
                    'Cliente actualizado correctamente.'
                );
        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No se pudo actualizar el cliente.'
                );
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('plan', 'domains');

        return view(
            'admin.tenants.show',
            compact('tenant')
        );
    }

    public function destroy(Tenant $tenant)
    {
        try {

            /*
        |----------------------------------
        | ELIMINAR DOMINIOS
        |----------------------------------
        */
            $tenant->domains()->delete();

            /*
        |----------------------------------
        | ELIMINAR TENANT
        |----------------------------------
        */
            $tenant->delete();

            return back()->with(
                'success',
                'Cliente eliminado correctamente.'
            );
        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'No se pudo eliminar el cliente.'
            );
        }
    }
}
