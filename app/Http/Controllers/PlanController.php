<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSystemOwner($request);

        return view('plans.index', [
            'plans' => Plan::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request)
    {
        $this->ensureSystemOwner($request);

        return view('plans.form', ['plan' => new Plan()]);
    }

    public function store(Request $request)
    {
        $this->ensureSystemOwner($request);

        Plan::create($this->validatedData($request) + ['is_custom' => true]);

        return redirect()
            ->route('plans.index')
            ->with('success', 'Plan personalizado creado correctamente.');
    }

    public function edit(Request $request, Plan $plan)
    {
        $this->ensureSystemOwner($request);

        return view('plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $this->ensureSystemOwner($request);

        $plan->update($this->validatedData($request, $plan));

        return redirect()
            ->route('plans.index')
            ->with('success', 'Plan actualizado correctamente.');
    }

    private function validatedData(Request $request, ?Plan $plan = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:plans,name' . ($plan ? ',' . $plan->id : '')],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'max_users' => ['nullable', 'integer', 'min:1'],
            'max_areas' => ['nullable', 'integer', 'min:1'],
            'max_signatures' => ['nullable', 'integer', 'min:1'],
            'max_documents' => ['nullable', 'integer', 'min:1'],
            'max_workflows' => ['nullable', 'integer', 'min:1'],
            'max_storage_mb' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'features_text' => ['nullable', 'string', 'max:2000'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $data['features'] = collect(preg_split('/\r\n|\r|\n/', $data['features_text'] ?? ''))
            ->map(fn ($feature) => trim($feature))
            ->filter()
            ->values()
            ->all();
        unset($data['features_text']);

        return $data;
    }

    private function ensureSystemOwner(Request $request): void
    {
        abort_unless($request->user()->isSystemOwner(), 403);
    }
}
