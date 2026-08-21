@extends('layouts.app')

@section('content')
<x-crud.index>
    <x-slot:header>
        <x-crud.header title="Flujos documentales" description="Configura etapas, responsables, firmas y tiempos de atención.">
            <x-slot:toolbar>
                @can('flows.create')
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createWorkflowModal"><i class="ti ti-plus me-1"></i>Nuevo flujo</button>
                @endcan
            </x-slot:toolbar>
        </x-crud.header>
    </x-slot:header>

    <div class="row row-cards">
        @forelse($templates as $template)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2 mb-3">
                            <div>
                                <div class="fw-bold fs-3">{{ $template->name }}</div>
                                <div class="text-secondary small">{{ $template->documentType?->name ?? 'Todos los tipos' }} · Origen: {{ $template->originArea?->name ?? 'cualquier área' }}</div>
                            </div>
                            <span class="badge bg-{{ $template->active ? 'success' : 'secondary' }}-lt">{{ $template->active ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                        <p class="text-secondary">{{ $template->description ?: 'Sin descripción.' }}</p>
                        <div class="border-top pt-3 small">
                            @foreach($template->steps as $step)
                                <div class="d-flex gap-2 mb-2">
                                    <span class="badge bg-blue-lt">{{ $step->step_order }}</span>
                                    <span>
                                        <strong>{{ $step->name }}</strong><br>
                                        <span class="text-secondary">
                                            {{ $step->responsibleArea?->name }} · {{ $step->action === 'signature' ? 'Firma' : ($step->action === 'approval' ? 'Aprobación' : 'Revisión') }}
                                            @if ($step->sla_hours)
                                                · SLA {{ $step->sla_hours }} h
                                            @endif
                                        </span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <x-crud.empty icon="ti ti-route" title="No hay flujos configurados" description="Crea el primer flujo para estandarizar tus aprobaciones." />
        @endforelse
    </div>
</x-crud.index>

@can('flows.create')
<x-crud.modal id="createWorkflowModal" size="xl" :scrollable="true">
    <form method="POST" action="{{ route('workflow-templates.store') }}" x-data="{ steps: [{name:'Revisión', action:'review', responsible_area_id:'', responsible_role_id:'', requires_signature:false, sla_hours:'', warning_before_hours:''}] }">
        @csrf
        <x-crud.modal-header title="Nuevo flujo documental" subtitle="Define la ruta, los responsables y los tiempos de atención." />
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Nombre <span class="text-danger">*</span></label><input name="name" class="form-control" required placeholder="Ej.: Aprobación de resoluciones"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Tipo de documento</label><select name="document_type_id" class="form-select"><option value="">Todos los tipos</option>@foreach($types as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach</select></div>
                <div class="col-12 mb-3"><label class="form-label">Descripción</label><textarea name="description" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3"><h4 class="mb-0">Etapas</h4><button type="button" class="btn btn-outline-primary btn-sm" @click="steps.push({name:'', action:'review', responsible_area_id:'', responsible_role_id:'', requires_signature:false, sla_hours:'', warning_before_hours:''})"><i class="ti ti-plus"></i> Agregar etapa</button></div>
            <template x-for="(step, index) in steps" :key="index">
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between"><strong x-text="'Etapa ' + (index + 1)"></strong><button type="button" class="btn btn-link text-danger p-0" x-show="steps.length > 1" @click="steps.splice(index, 1)">Quitar</button></div>
                    <div class="row mt-2">
                        <div class="col-md-4 mb-2"><label class="form-label small">Nombre <span class="text-danger">*</span></label><input :name="`steps[${index}][name]`" x-model="step.name" class="form-control" required placeholder="Nombre de etapa"></div>
                        <div class="col-md-2 mb-2"><label class="form-label small">Acción</label><select :name="`steps[${index}][action]`" x-model="step.action" class="form-select"><option value="review">Revisión</option><option value="approval">Aprobación</option><option value="signature">Firma</option></select></div>
                        <div class="col-md-3 mb-2"><label class="form-label small">Área responsable <span class="text-danger">*</span></label><select :name="`steps[${index}][responsible_area_id]`" x-model="step.responsible_area_id" class="form-select" required><option value="">Seleccionar</option>@foreach($areas as $area)<option value="{{ $area->id }}">{{ $area->name }}</option>@endforeach</select></div>
                        <div class="col-md-3 mb-2"><label class="form-label small">Rol</label><select :name="`steps[${index}][responsible_role_id]`" x-model="step.responsible_role_id" class="form-select"><option value="">Cualquier rol</option>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->name }}</option>@endforeach</select></div>
                        <div class="col-md-6 mb-2"><label class="form-label small">SLA (horas)</label><input type="number" min="1" max="8760" :name="`steps[${index}][sla_hours]`" x-model="step.sla_hours" class="form-control" placeholder="Sin límite"></div>
                        <div class="col-md-6 mb-2"><label class="form-label small">Avisar antes (horas)</label><input type="number" min="1" :name="`steps[${index}][warning_before_hours]`" x-model="step.warning_before_hours" class="form-control" placeholder="Ej.: 4"></div>
                    </div>
                    <div class="form-hint">El SLA comienza al activar la etapa; sus responsables recibirán un aviso antes del vencimiento.</div>
                </div>
            </template>
            <input type="hidden" name="active" value="1">
        </div>
        <x-crud.modal-footer color="success" text="Guardar flujo" icon="ti ti-device-floppy" />
    </form>
</x-crud.modal>
@endcan
@endsection
