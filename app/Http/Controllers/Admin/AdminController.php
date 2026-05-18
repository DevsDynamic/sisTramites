<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $months = [];
        $tenantCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');
            $tenantCounts[] = Tenant::whereMonth(
                'created_at',
                $month->month
            )->count();
        }

        return view('admin.dashboard', [
            'tenants' => Tenant::count(),
            'plans' => Plan::count(),
            'activeTenants' => Tenant::where('status', 'active')->count(),
            'expiredTenants' => Tenant::where('status', 'expired')->count(),
            'months' => $months,
            'tenantCounts' => $tenantCounts,
        ]);
    }
}
