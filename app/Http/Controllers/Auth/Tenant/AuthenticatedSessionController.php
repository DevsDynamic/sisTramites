<?php

namespace App\Http\Controllers\Auth\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.tenant.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Forzar conexión al tenant activo
        if (tenancy()->initialized) {
            $dbName = tenant()->database()->getName();
            config(['database.connections.tenant.database' => $dbName]);
            \DB::purge('tenant');
            \DB::reconnect('tenant');
            \DB::setDefaultConnection('tenant');
        }

        if (Auth::guard('tenant')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ]);
    }
    // public function store(Request $request)
    // {
    //     //dd(tenant());
    //     $credentials = $request->validate([
    //         'email'    => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::guard('tenant')->attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('/dashboard');
    //     }

    //     return back()->withErrors([
    //         'email' => 'Credenciales incorrectas.',
    //     ]);
    // }

    public function destroy(Request $request)
    {
        Auth::guard('tenant')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
