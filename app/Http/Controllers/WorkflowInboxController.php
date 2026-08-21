<?php

namespace App\Http\Controllers;

use App\Models\DocumentStatusLog;
use App\Models\DocumentWorkflowStep;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowInboxController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize($request);
        $areaIds = $request->user()->areas()->pluck('areas.id');

        $steps = DocumentWorkflowStep::with(['workflow.document.type', 'responsibleArea'])
            ->where('status', 'active')
            ->whereIn('responsible_area_id', $areaIds)
            ->where(function ($query) use ($request) {
                $query->whereNull('responsible_role_id')
                    ->orWhereHas('responsibleRole.users', fn($users) => $users->whereKey($request->user()->id));
            })
            ->latest()
            ->get();

        return view('workflow-inbox.index', compact('steps'));
    }

    public function complete(Request $request, DocumentWorkflowStep $step, NotificationService $notifications)
    {
        $this->authorize($request);
        abort_unless($step->status === 'active', 422, 'Esta etapa ya fue atendida.');
        abort_unless($request->user()->areas()->whereKey($step->responsible_area_id)->exists(), 403);

        $request->validate(['comment' => ['nullable', 'string', 'max:1000']]);

        DB::transaction(fn () => app(\App\Services\WorkflowExecutionService::class)
            ->completeStep($step, $request->user(), $request->input('comment')));

        return back()->with('success', 'Etapa atendida correctamente.');
    }

    private function authorize(Request $request): void
    {
        abort_unless($request->user()->isSystemOwner() || $request->user()->can('documents.view'), 403);
    }
}
