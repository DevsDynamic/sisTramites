<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WELCOME
    |--------------------------------------------------------------------------
    */

    public function welcome()
    {
        return view('tenant.onboarding.welcome');
    }

    /*
    |--------------------------------------------------------------------------
    | COMPANY
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return view('tenant.onboarding.company');
    }

    public function companyStore(Request $request)
    {
        $tenant = tenant();

        $tenant->update([
            'business_name' => $request->business_name,
            'trade_name' => $request->trade_name,
            'ruc' => $request->ruc,
            'phone' => $request->phone,
        ]);

        return redirect()
            ->route('tenant.onboarding.branding');
    }

    /*
    |--------------------------------------------------------------------------
    | BRANDING
    |--------------------------------------------------------------------------
    */

    public function branding()
    {
        return view('tenant.onboarding.branding');
    }

    public function brandingStore(Request $request)
    {
        $tenant = tenant();

        /* VALIDAR */
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'login_background' => 'nullable|image|max:4096',
            'primary_color' => 'nullable|string',
            'sidebar_color' => 'nullable|string',
        ]);

        /* SETTINGS */
        $settings = $tenant->settings ?? [];

        /* BRANDING ARRAY */
        $branding = $settings['branding'] ?? [];

        /* LOGO */
        if ($request->hasFile('logo')) {

            $branding['logo'] = $request
                ->file('logo')
                ->store('tenants/logos', 'public');
        }

        /* FAVICON */
        if ($request->hasFile('favicon')) {
            $branding['favicon'] = $request
                ->file('favicon')
                ->store('tenants/favicons', 'public');
        }

        /* LOGIN BACKGROUND */
        if ($request->hasFile('login_background')) {

            $branding['login_background'] = $request
                ->file('login_background')
                ->store('tenants/backgrounds', 'public');
        }

        /* COLOR */
        $branding['primary_color'] =
            $request->primary_color ?? '#206bc4';
        $branding['sidebar_color'] =
            $request->sidebar_color ?? '#111827';

        /* SAVE */
        $settings['branding'] = $branding;
        $tenant->settings = $settings;
        $tenant->save();
        return redirect()
            ->route('tenant.onboarding.sunat');
    }

    /*
    |--------------------------------------------------------------------------
    | SUNAT
    |--------------------------------------------------------------------------
    */

    public function sunat()
    {
        return view('tenant.onboarding.sunat');
    }

    public function sunatStore(Request $request)
    {
        $tenant = tenant();
        $tenant->update([
            'sunat_user' => $request->sunat_user,
            'sunat_password' => encrypt($request->sunat_password),
            'onboarding_completed' => true,
        ]);
        return redirect()
            ->route('tenant.onboarding.completed');
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETED
    |--------------------------------------------------------------------------
    */

    public function completed()
    {
        return view('tenant.onboarding.completed');
    }
}
