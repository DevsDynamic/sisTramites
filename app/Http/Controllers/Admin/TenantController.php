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
        // La validación va FUERA del try-catch
        // Si falla, Laravel redirige automáticamente con errores y withInput()
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
}
