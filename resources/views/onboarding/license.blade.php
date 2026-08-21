@extends('layouts.app')

@section('content')
<x-crud.index>
    <x-slot:header><x-crud.header title="Licencia y plan" description="Configuración reservada al propietario técnico del sistema." /></x-slot:header>

    <div class="card" id="licensePanel">
        <form method="POST" action="{{ route('onboarding.license.store') }}">@csrf
            <div class="card-body"><div class="row g-3">
                <div class="col-md-6"><label class="form-label">Plan contratado</label><select name="plan_id" id="licensePlan" class="form-select" required>@foreach($plans as $plan)<option value="{{ $plan->id }}" @selected(old('plan_id', $settings->plan_id) == $plan->id)>{{ $plan->name }}{{ $plan->is_custom ? ' · Personalizado' : '' }}</option>@endforeach</select><div class="form-hint">Al cambiarlo se validan los registros que ya existen.</div>@error('plan_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
                <div class="col-md-3"><label class="form-label">Inicio de vigencia</label><input type="date" name="license_starts_at" id="licenseStart" class="form-control" value="{{ old('license_starts_at', $settings->license_starts_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
                <div class="col-md-3"><label class="form-label">Renovación</label><select name="license_cycle" id="licenseCycle" class="form-select"><option value="monthly" @selected(old('license_cycle', $settings->license_cycle) === 'monthly')>Mensual</option><option value="quarterly" @selected(old('license_cycle', $settings->license_cycle) === 'quarterly')>Trimestral</option><option value="semiannual" @selected(old('license_cycle', $settings->license_cycle) === 'semiannual')>Semestral</option><option value="annual" @selected(old('license_cycle', $settings->license_cycle ?? 'annual') === 'annual')>Anual</option><option value="custom" @selected(old('license_cycle', $settings->license_cycle) === 'custom')>Personalizada</option></select></div>
                <div class="col-md-3 d-none" id="customDaysWrap"><label class="form-label">Días de vigencia</label><input type="number" min="1" name="license_custom_days" id="customDays" class="form-control" value="{{ old('license_custom_days', $settings->license_custom_days) }}"></div>
                <div class="col-md-9"><div class="alert alert-info py-2 mb-0"><i class="ti ti-calendar-event me-1"></i>Vencimiento calculado: <strong id="expiryPreview">—</strong>. La frecuencia de pago es independiente de las capacidades del plan.</div></div>
            </div></div>
            <div class="card-footer d-flex justify-content-between"><a href="{{ route('plans.index') }}" class="btn btn-outline-primary"><i class="ti ti-packages me-1"></i>Administrar planes</a><button class="btn btn-success" id="saveLicense"><i class="ti ti-device-floppy me-1"></i>Guardar licencia</button></div>
        </form>
    </div>

    <div class="card mt-4"><div class="card-header"><h3 class="card-title" id="planPreviewTitle">Plan seleccionado</h3></div><div class="card-body"><div class="row g-3" id="planUsagePreview"></div><div class="d-flex flex-wrap gap-2 mt-3" id="planFeaturesPreview"></div><div class="alert alert-danger d-none mt-3 mb-0" id="planNotAllowed"><i class="ti ti-alert-triangle me-1"></i>El uso actual excede este plan. Reduce los registros o selecciona un plan con mayor capacidad.</div></div></div>
</x-crud.index>
@endsection

@push('scripts')
<script>
const plans = @json($plans->mapWithKeys(fn($plan) => [$plan->id => ['name' => $plan->name, 'features' => $plan->features ?? []]]));
const planUsage = @json($planUsage);
const planSelect = document.getElementById('licensePlan');
const usageTarget = document.getElementById('planUsagePreview');
const blocked = document.getElementById('planNotAllowed');
const saveButton = document.getElementById('saveLicense');

function renderPlan() {
    const plan = plans[planSelect.value];
    const usage = planUsage[planSelect.value] || [];
    document.getElementById('planPreviewTitle').textContent = `Capacidades y uso: ${plan.name}`;
    let invalid = false;
    usageTarget.innerHTML = usage.map(item => {
        const exceeds = item.limit_raw !== null && item.used_raw > item.limit_raw;
        invalid = invalid || exceeds;
        return `<div class="col-md-4"><div class="border rounded p-3 h-100 ${exceeds ? 'border-danger bg-danger-lt' : ''}"><div class="text-secondary small">${item.label}</div><div class="fw-bold fs-3">${item.used}${item.suffix} <span class="text-secondary fs-5">/ ${item.limit}${item.suffix}</span></div></div></div>`;
    }).join('');
    document.getElementById('planFeaturesPreview').innerHTML = plan.features.map(feature => `<span class="badge bg-azure-lt">${feature}</span>`).join('');
    blocked.classList.toggle('d-none', !invalid);
    saveButton.disabled = invalid;
}

function renderExpiry() {
    const start = new Date(`${document.getElementById('licenseStart').value}T00:00:00`);
    const cycle = document.getElementById('licenseCycle').value;
    const custom = document.getElementById('customDaysWrap');
    custom.classList.toggle('d-none', cycle !== 'custom');
    if (Number.isNaN(start.getTime())) return;
    if (cycle === 'monthly') start.setMonth(start.getMonth() + 1);
    if (cycle === 'quarterly') start.setMonth(start.getMonth() + 3);
    if (cycle === 'semiannual') start.setMonth(start.getMonth() + 6);
    if (cycle === 'annual') start.setFullYear(start.getFullYear() + 1);
    if (cycle === 'custom') start.setDate(start.getDate() + Number(document.getElementById('customDays').value || 0));
    document.getElementById('expiryPreview').textContent = start.toLocaleDateString('es-PE');
}
planSelect.addEventListener('change', renderPlan);
['licenseStart', 'licenseCycle', 'customDays'].forEach(id => document.getElementById(id).addEventListener('input', renderExpiry));
renderPlan(); renderExpiry();
</script>
@endpush
