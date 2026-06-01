@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row g-4">

        {{-- DOCUMENTOS --}}
        <div class="col-md-3">
            <div class="card p-4">
                <div class="text-secondary mb-2">Documentos</div>

                <div class="display-5 fw-bold text-primary">
                    {{ $stats['documents'] }}
                </div>
            </div>
        </div>

        {{-- USUARIOS (mantienes tu estilo original) --}}
        <div class="col-md-3">
            <div class="card p-4">
                <div class="text-secondary mb-2">Usuarios</div>

                <div class="display-5 fw-bold">
                    {{ $stats['users'] }}
                </div>
            </div>
        </div>

        {{-- FLUJOS --}}
        <div class="col-md-3">
            <div class="card p-4">
                <div class="text-secondary mb-2">Flujos</div>

                <div class="display-5 fw-bold text-primary">
                    {{ $stats['flows'] }}
                </div>
            </div>
        </div>

        {{-- PENDIENTES --}}
        <div class="col-md-3">
            <div class="card p-4">
                <div class="text-secondary mb-2">Pendientes</div>

                <div class="display-5 fw-bold text-warning">
                    {{ $stats['pending'] }}
                </div>
            </div>
        </div>

    </div>

    {{-- SEGUNDA FILA --}}
    <div class="row mt-4">

        {{-- INBOX --}}
        <div class="col-md-6">
            <div class="card p-4">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold">📥 Mi Bandeja</h5>

                    <a href="{{ route('documents.inbox') }}" class="btn btn-sm btn-primary">
                        Ver todo
                    </a>
                </div>

                @foreach ($inbox as $flow)
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <div class="fw-bold">
                                {{ optional($flow->document)->code }}
                            </div>

                            <div class="text-secondary small">
                                {{ optional($flow->document)->subject }}
                            </div>
                        </div>

                        <span class="badge bg-{{ $flow->sla_badge }}">
                            {{ $flow->status }}
                        </span>
                        @if ($flow->sla_expired)
                            <span class="badge bg-danger">
                                SLA Vencido
                            </span>
                        @elseif($flow->sla_deadline)
                            <span class="badge bg-{{ $flow->sla_badge }}">
                                {{ now()->diffInHours($flow->sla_deadline, false) }}h
                            </span>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>

        {{-- ACTIVIDAD --}}
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">📄 Documentos Recientes</h5>
                @foreach ($recentDocuments as $doc)
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <div class="fw-bold">
                                {{ $doc->code }}
                            </div>

                            <div class="text-secondary small">
                                {{ $doc->subject }}
                            </div>
                        </div>

                        <span class="badge bg-primary">
                            {{ $doc->status }}
                        </span>

                    </div>
                @endforeach

            </div>
        </div>

    </div>

@endsection
