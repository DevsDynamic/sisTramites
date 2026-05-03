<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;

class AdminController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('domains')->get();
        $plans = Plan::all();

        return view('admin.dashboard', compact('tenants', 'plans'));
    }
}