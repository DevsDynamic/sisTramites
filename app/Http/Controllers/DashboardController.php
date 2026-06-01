<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFlow;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $stats = [
            'users' => User::count(),
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

        return view('dashboard', compact(
            'stats',
            'recentDocuments',
            'inbox'
        ));
    }
}
