<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentFlow;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DocumentAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => Document::count(),
            'pending' => DocumentFlow::where('status', 'pending')->count(),
            'approved' => Document::where('status', 'approved')->count(),
            'rejected' => Document::where('status', 'rejected')->count(),
        ];

        // 📊 documentos últimos 7 días
        $last7Days = Document::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->get();

        // 🧑‍💼 carga por área
        $byArea = DocumentFlow::selectRaw('to_area_id, COUNT(*) as total')
            ->groupBy('to_area_id')
            ->get();

        // ⏱ promedio de atención (simple)
        $avgTime = DocumentFlow::whereNotNull('received_at')
            ->get()
            ->avg(function ($flow) {
                return Carbon::parse($flow->sent_at)
                    ->diffInHours($flow->received_at);
            });

        return view('analytics.index', compact(
            'stats',
            'last7Days',
            'byArea',
            'avgTime'
        ));
    }
}