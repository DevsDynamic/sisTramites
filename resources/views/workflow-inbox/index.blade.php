@extends('layouts.app')

@section('content')
<x-crud.index>
    <x-slot:header><x-crud.header title="Bandeja de flujo" description="Etapas documentales pendientes de tu área." /></x-slot:header>
    <div class="row row-cards">
        @forelse($steps as $step)
            <div class="col-md-6 col-xl-4"><div class="card h-100"><div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <span class="badge bg-blue-lt">{{ $step->action === 'signature' ? 'Firma' : ($step->action === 'approval' ? 'Aprobación' : 'Revisión') }}</span>
                    <div class="text-end"><div class="text-secondary small">Etapa {{ $step->step_order }}</div>
                    @if($step->overdue_at || ($step->due_at && $step->due_at->isPast()))
                        <span class="badge bg-danger-lt">SLA vencido</span>
                    @elseif($step->due_at)
                        <span class="badge bg-warning-lt">Vence {{ $step->due_at->format('d/m H:i') }}</span>
                    @endif</div>
                </div>
                <div class="fw-bold fs-3">{{ $step->workflow->document->code }}</div><div class="mb-2">{{ $step->workflow->document->subject }}</div><div class="text-secondary small mb-3">{{ $step->name }} · {{ $step->responsibleArea?->name }}</div>
                @if($step->action === 'signature')
                    <div class="alert alert-info small"><i class="ti ti-signature me-1"></i>Esta etapa se completa al aplicar una firma digital oficial o un visto bueno (VB) desde el documento.</div>
                @else
                    <form method="POST" action="{{ route('workflow-inbox.complete', $step) }}">@csrf<textarea name="comment" class="form-control mb-2" rows="2" placeholder="Comentario opcional"></textarea><button class="btn btn-success w-100"><i class="ti ti-check me-1"></i>Completar etapa</button></form>
                @endif
                <a class="btn btn-outline-secondary w-100 mt-2" href="{{ route('documents.show', $step->workflow->document) }}">Ver documento</a>
            </div></div></div>
        @empty
            <x-crud.empty icon="ti ti-inbox" title="No tienes etapas pendientes" description="Las nuevas asignaciones aparecerán aquí." />
        @endforelse
    </div>
</x-crud.index>
@endsection
