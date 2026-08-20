<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

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
            ->route('onboarding.welcome')
            ->with('success', 'Configuración inicial completada.');
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
