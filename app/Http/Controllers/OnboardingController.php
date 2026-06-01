<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /* WELCOME */
    public function welcome()
    {
        return view('onboarding.welcome');
    }

    /* COMPANY */
    public function company()
    {
        return view('onboarding.company');
    }

    public function companyStore(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'website'      => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:500',
        ]);

        $settings = Setting::firstOrFail();

        $settings->update([
            'company_name' => $request->company_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'website'      => $request->website,
            'address'      => $request->address,
        ]);

        return redirect()
            ->route('onboarding.branding')
            ->with(
                'success',
                'Información de empresa guardada correctamente.'
            );
    }

    /* BRANDING */
    public function branding()
    {
        return view('onboarding.branding');
    }

    public function brandingStore(Request $request)
    {
        $request->validate([
            'logo'               => 'nullable|image|max:2048',
            'favicon'            => 'nullable|image|max:1024',
            'login_background'   => 'nullable|image|max:4096',
            'primary_color'      => 'nullable|string',
            'sidebar_color'      => 'nullable|string',
            'sidebar_text_color' => 'nullable|string',
        ]);

        $settings = Setting::firstOrFail();

        $data = [
            'primary_color' => $request->primary_color ?? '#206bc4',
            'sidebar_color' => $request->sidebar_color ?? '#111827',
            'sidebar_text_color' => $request->sidebar_text_color ?? '#ffffff',
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request
                ->file('logo')
                ->store('settings/logo', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request
                ->file('favicon')
                ->store('settings/favicon', 'public');
        }

        if ($request->hasFile('login_background')) {
            $data['login_background'] = $request
                ->file('login_background')
                ->store('settings/backgrounds', 'public');
        }

        $settings->update($data);

        return redirect()
            ->route('onboarding.completed')
            ->with(
                'success',
                'Configuración visual guardada correctamente.'
            );
    }

    /* COMPLETED */
    public function completed()
    {
        $settings = Setting::firstOrFail();

        if (!$settings->onboarding_completed) {

            $settings->update([
                'onboarding_completed' => true,
            ]);
        }

        return view('onboarding.completed');
    }
}
