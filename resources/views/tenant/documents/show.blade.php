{{-- @extends('layouts.tenant.app')

@section('content')
    <div class="container-xl">

        <div class="card mb-3">
            <div class="card-body">
                <h2>{{ $document->subject }}</h2>
                <div>
                    <span class="badge bg-blue">{{ $document->code }}</span>
                    <span class="badge bg-green">{{ $document->status }}</span>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- TIMELINE
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Flujo del Documento</h3>
                    </div>

                    <div class="card-body">
                        <ul class="list list-timeline">

                            @foreach ($document->flows as $flow)
                                <li class="list-timeline-item">
                                    <div class="list-timeline-icon bg-blue"></div>

                                    <div class="list-timeline-content">
                                        <div class="text-muted">
                                            {{ $flow->created_at }}
                                        </div>

                                        <div>
                                            De Área {{ $flow->from_area_id }}
                                            → Área {{ $flow->to_area_id }}
                                        </div>

                                        <div>
                                            Estado: {{ $flow->status }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

            {{-- LOGS
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Auditoría</h3>
                    </div>

                    <div class="card-body">

                        <ul class="list-group">

                            @foreach ($document->logs as $log)
                                <li class="list-group-item">
                                    <strong>{{ $log->action }}</strong>
                                    <br>
                                    {{ $log->description }}
                                    <br>
                                    <small>{{ $log->created_at }}</small>
                                </li>
                            @endforeach

                        </ul>

                    </div>
                </div>
            </div>

        </div>

    </div>


    <div class="tenant-card p-4">

        <h4>Versiones</h4>

        @foreach ($document->versions as $version)
            <div class="border rounded p-3 mb-2">

                <div class="d-flex justify-content-between">

                    <div>
                        <strong>v{{ $version->version }}</strong>
                        <div class="text-secondary">
                            {{ $version->file_name }}
                        </div>
                    </div>

                    <a href="{{ Storage::url($version->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                        Descargar
                    </a>

                </div>

            </div>
        @endforeach

    </div>
@endsection --}}


@extends('layouts.tenant.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="page-title mb-1">
            {{ $document->subject }}
        </h1>

        <div class="text-secondary">
            {{ $document->type?->name }}
        </div>

    </div>

    <span class="badge bg-blue-lt fs-5">
        {{ strtoupper($document->status) }}
    </span>

</div>

<div class="row">

    {{-- LEFT --}}
    <div class="col-lg-8">

        {{-- INFO --}}
        <div class="card tenant-card mb-4">

            <div class="card-header">

                <h3 class="card-title">
                    Información
                </h3>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <div class="text-secondary small">
                        Descripción
                    </div>

                    <div>
                        {{ $document->description }}
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Creado por
                        </div>

                        <div class="fw-bold">
                            {{ $document->creator?->name }}
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="text-secondary small">
                            Fecha
                        </div>

                        <div class="fw-bold">
                            {{ $document->created_at->format('d/m/Y H:i') }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ATTACHMENTS --}}
        <div class="card tenant-card">

            <div class="card-header">

                <h3 class="card-title">
                    Archivos adjuntos
                </h3>

            </div>

            <div class="card-body">

                @foreach($document->attachments as $file)

                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-bold">
                                    {{ $file->original_name }}
                                </div>

                                <div class="text-secondary small">
                                    {{ number_format($file->file_size / 1024, 2) }} KB
                                </div>

                            </div>

                            <a
                                href="{{ asset('storage/' . $file->file_path) }}"
                                target="_blank"
                                class="btn btn-primary btn-sm">

                                <i class="ti ti-download"></i>
                                Descargar
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">

        {{-- SIGN --}}
        <div class="card tenant-card mb-4">

            <div class="card-header">

                <h3 class="card-title">
                    Firmar documento
                </h3>

            </div>

            <div class="card-body">

                @forelse($signatures as $signature)

                    <div class="border rounded p-3 mb-3">

                        <div class="fw-bold">
                            {{ strtoupper($signature->type) }}
                        </div>

                        <div class="text-secondary small mb-3">
                            {{ $signature->is_default ? 'Predeterminada' : 'Firma disponible' }}
                        </div>

                        <form
                            method="POST"
                            action="{{ route('tenant.documents.sign', $document) }}">

                            @csrf

                            <input
                                type="hidden"
                                name="signature_id"
                                value="{{ $signature->id }}">

                            <button
                                class="btn btn-primary w-100">

                                <i class="ti ti-signature"></i>
                                Firmar documento

                            </button>

                        </form>

                    </div>

                @empty

                    <div class="alert alert-warning">

                        No tiene firmas registradas.

                    </div>

                @endforelse

            </div>

        </div>

        {{-- TIMELINE --}}
        <div class="card tenant-card">

            <div class="card-header">

                <h3 class="card-title">
                    Historial
                </h3>

            </div>

            <div class="card-body">

                <div class="timeline">

                    <div class="timeline-item">

                        <div class="timeline-marker bg-blue"></div>

                        <div class="timeline-content">

                            <div class="fw-bold">
                                Documento creado
                            </div>

                            <div class="text-secondary small">
                                {{ $document->created_at->diffForHumans() }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection