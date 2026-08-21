<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentWorkflowStep;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DocumentAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => Document::count(),
            'pending' => DocumentWorkflowStep::where('status', 'active')->count(),
            'approved' => Document::where('status', 'approved')->count(),
            'rejected' => Document::where('status', 'rejected')->count(),
        ];

        // 📊 documentos últimos 7 días
        $last7Days = Document::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->get();

        // 🧑‍💼 carga por área
        $byArea = DocumentWorkflowStep::with('responsibleArea:id,name')
            ->selectRaw('responsible_area_id, COUNT(*) as total')
            ->groupBy('responsible_area_id')
            ->get();

        // ⏱ promedio de atención (simple)
        $avgTime = DocumentWorkflowStep::whereNotNull('acted_at')
            ->get()
            ->avg(function ($step) {
                return Carbon::parse($step->created_at)
                    ->diffInHours($step->acted_at);
            });

        return view('analytics.index', compact(
            'stats',
            'last7Days',
            'byArea',
            'avgTime'
        ));
    }
}
