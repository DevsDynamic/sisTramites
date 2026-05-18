<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentFlow;
use App\Models\Tenant\TenantUser;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //dd(tenant(), config('database.connections.tenant.database'));

        $user = auth('tenant')->user();

        $stats = [
            'users' => TenantUser::count(),
            'documents' => Document::count(),
            'flows' => DocumentFlow::count(),
            'pending' => DocumentFlow::where('to_area_id', $user->area_id)
                ->where('status', 'pending')
                ->count(),
            'inbox' => DocumentFlow::where('to_area_id', $user->area_id)
                ->whereIn('status', ['pending', 'received'])
                ->count(),
        ];

        $recentDocuments = Document::latest()->take(5)->get();

        $inbox = DocumentFlow::with('document')
            ->where('to_area_id', $user->area_id)
            ->latest()
            ->take(5)
            ->get();

        return view('tenant.dashboard', compact(
            'stats',
            'recentDocuments',
            'inbox'
        ));
    }
}
