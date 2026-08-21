<?php

namespace App\Http\Controllers;

use App\Models\WorkflowTemplate;
use App\Models\Area;
use App\Models\DocumentType;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\PlanLimitService;

class WorkflowTemplateController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize($request, 'flows.view');

        return view('workflow-templates.index', [
            'templates' => WorkflowTemplate::with(['steps.responsibleArea', 'steps.responsibleRole', 'documentType', 'originArea'])->latest()->get(),
            'types' => DocumentType::active()->orderBy('name')->get(),
            'areas' => Area::active()->orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, PlanLimitService $planLimits)
    {
        $this->authorize($request, 'flows.create');
        $planLimits->ensureAvailable('workflows');
        $data = $this->validated($request);

        $template = WorkflowTemplate::create([
            ...collect($data)->except('steps')->all(),
            'created_by' => $request->user()->id,
            'active' => $request->boolean('active', true),
        ]);

        $this->syncSteps($template, $data['steps']);

        if (! $request->expectsJson()) return redirect()->route('workflow-templates.index')->with('success', 'Flujo creado correctamente.');

        return response()->json(['message' => 'Flujo creado correctamente.', 'item' => $template->load('steps')], 201);
    }

    public function update(Request $request, WorkflowTemplate $workflowTemplate)
    {
        $this->authorize($request, 'flows.edit');
        $data = $this->validated($request, $workflowTemplate);

        $workflowTemplate->update([
            ...collect($data)->except('steps')->all(),
            'active' => $request->boolean('active', true),
        ]);
        $workflowTemplate->steps()->delete();
        $this->syncSteps($workflowTemplate, $data['steps']);

        return response()->json(['message' => 'Flujo actualizado correctamente.', 'item' => $workflowTemplate->load('steps')]);
    }

    private function validated(Request $request, ?WorkflowTemplate $template = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'document_type_id' => ['nullable', 'exists:document_types,id'],
            'origin_area_id' => ['nullable', 'exists:areas,id'],
            'active' => ['nullable', 'boolean'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.name' => ['required', 'string', 'max:120'],
            'steps.*.action' => ['required', Rule::in(['review', 'approval', 'signature'])],
            'steps.*.responsible_area_id' => ['required', 'exists:areas,id'],
            'steps.*.responsible_role_id' => ['nullable', 'exists:roles,id'],
            'steps.*.requires_signature' => ['nullable', 'boolean'],
            'steps.*.sla_hours' => ['nullable', 'integer', 'min:1', 'max:8760'],
            'steps.*.warning_before_hours' => ['nullable', 'integer', 'min:1', 'max:8759'],
        ]);

        foreach ($data['steps'] as $index => $step) {
            if (! empty($step['warning_before_hours'])
                && (! isset($step['sla_hours']) || $step['warning_before_hours'] >= $step['sla_hours'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "steps.$index.warning_before_hours" => 'El aviso debe ser menor que el SLA de la etapa.',
                ]);
            }
        }

        return $data;
    }

    private function syncSteps(WorkflowTemplate $template, array $steps): void
    {
        foreach (array_values($steps) as $index => $step) {
            $template->steps()->create([
                ...$step,
                'step_order' => $index + 1,
                'requires_signature' => (bool) ($step['requires_signature'] ?? false),
                'sla_hours' => $step['sla_hours'] ?? null,
                'warning_before_hours' => $step['warning_before_hours'] ?? null,
            ]);
        }
    }

    private function authorize(Request $request, string $permission): void
    {
        abort_unless($request->user()->isSystemOwner() || $request->user()->can($permission), 403);
    }
}
