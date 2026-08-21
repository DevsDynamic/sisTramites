<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Plan;
use App\Services\PlanLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    public function welcome(Request $request)
    {
        $this->ensurePermission($request, 'settings.view');

        return view('onboarding.welcome', [
            'settings' => Setting::firstOrFail(),
        ]);
    }

    public function company(Request $request)
    {
        $this->ensurePermission($request, 'settings.view');

        return view('onboarding.company', [
            'settings' => Setting::firstOrFail(),
        ]);
    }

    public function companyStore(Request $request)
    {
        $this->ensurePermission($request, 'settings.edit');

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        if (filled($data['website'] ?? null) && ! preg_match('#^https?://#i', $data['website'])) {
            $data['website'] = 'https://' . $data['website'];
        }

        Setting::firstOrFail()->update($data);

        return redirect()
            ->route('onboarding.welcome')
            ->with('success', 'Información de la empresa actualizada correctamente.');
    }

    public function branding(Request $request)
    {
        $this->ensurePermission($request, 'settings.view');

        return view('onboarding.branding', [
            'settings' => Setting::firstOrFail(),
        ]);
    }

    public function brandingStore(Request $request)
    {
        $this->ensurePermission($request, 'settings.edit');

        $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'login_background' => ['nullable', 'image', 'max:4096'],
            'primary_color' => ['nullable', 'string'],
            'sidebar_color' => ['nullable', 'string'],
            'sidebar_text_color' => ['nullable', 'string'],
        ]);

        $settings = Setting::firstOrFail();
        $data = [
            'primary_color' => $request->input('primary_color', '#206bc4'),
            'sidebar_color' => $request->input('sidebar_color', '#111827'),
            'sidebar_text_color' => $request->input('sidebar_text_color', '#ffffff'),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('settings/logo', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('settings/favicon', 'public');
        }

        if ($request->hasFile('login_background')) {
            $data['login_background'] = $request->file('login_background')->store('settings/backgrounds', 'public');
        }

        $settings->update($data);

        return redirect()
            ->route('onboarding.welcome')
            ->with('success', 'Identidad visual actualizada correctamente.');
    }

    public function completed(Request $request)
    {
        $this->ensurePermission($request, 'settings.view');

        $settings = Setting::firstOrFail();

        if (! $settings->onboarding_completed) {
            $settings->update(['onboarding_completed' => true]);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Configuración inicial completada.');
    }

    public function license(Request $request, PlanLimitService $planLimits)
    {
        abort_unless($request->user()->isSystemOwner(), 403);
        $settings = Setting::firstOrFail();
        $plans = Plan::query()
            ->where(function ($query) use ($settings) {
                $query->where('active', true);

                if ($settings->plan_id) {
                    $query->orWhere('id', $settings->plan_id);
                }
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('onboarding.license', [
            'settings' => $settings,
            'plans' => $plans,
            'usage' => $planLimits->usage(),
            'planUsage' => $plans->mapWithKeys(fn ($plan) => [$plan->id => $planLimits->usage($plan)]),
        ]);
    }

    public function licenseStore(Request $request, PlanLimitService $planLimits)
    {
        abort_unless($request->user()->isSystemOwner(), 403);
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'license_starts_at' => ['required', 'date'],
            'license_cycle' => ['required', Rule::in(['monthly', 'quarterly', 'semiannual', 'annual', 'custom'])],
            'license_custom_days' => ['nullable', 'required_if:license_cycle,custom', 'integer', 'min:1', 'max:3650'],
        ]);
        $plan = Plan::findOrFail($data['plan_id']);
        $planLimits->ensureCanAssign($plan);

        $startsAt = Carbon::parse($data['license_starts_at']);
        $expiresAt = match ($data['license_cycle']) {
            'monthly' => $startsAt->copy()->addMonth(),
            'quarterly' => $startsAt->copy()->addMonths(3),
            'semiannual' => $startsAt->copy()->addMonths(6),
            'annual' => $startsAt->copy()->addYear(),
            'custom' => $startsAt->copy()->addDays((int) $data['license_custom_days']),
        };

        Setting::firstOrFail()->update($data + [
            'plan_name' => $plan->name,
            'license_expires_at' => $expiresAt,
        ]);
        return redirect()->route('onboarding.welcome')->with('success', 'Licencia actualizada correctamente.');
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless(
            $request->user()->isSystemOwner()
                || $request->user()->can($permission),
            403
        );
    }
}
