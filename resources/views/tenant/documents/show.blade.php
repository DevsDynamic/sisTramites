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
        <span class="badge bg-{{ $document->status->color() }}-lt fs-5">
            <i class="{{ $document->status->icon() }}"></i>
            {{ $document->status->label() }}
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

                    <div class="row mb-4">

                        <div class="col-md-6">

                            <div class="text-secondary small">
                                Código
                            </div>

                            <div class="fw-bold fs-3">
                                {{ $document->code }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="text-secondary small">
                                Estado
                            </div>

                            <div class="fw-bold">

                                <span class="badge bg-{{ $document->status->color() }}-lt">
                                    <i class="{{ $document->status->icon() }}"></i>

                                    {{ $document->status->label() }}
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="mb-4">

                        <div class="text-secondary small mb-2">
                            Contenido
                        </div>

                        <div class="border rounded p-3 bg-body-tertiary">

                            {!! nl2br(e($document->content)) !!}

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
                    <h3 class="card-title">Archivos adjuntos</h3>
                </div>
                <div class="card-body">
                    @foreach ($document->attachments as $file)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold">
                                            {{ $file->original_name ?? $file->file_name }}
                                        </div>
                                        @if ($file->is_signed)
                                            <span class="badge bg-pink-lt">
                                                <i class="ti ti-signature"></i> Firmado
                                            </span>
                                        @else
                                            <span class="badge bg-blue-lt">
                                                Original
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-secondary small">
                                        @if ($file->file_size)
                                            {{ number_format($file->file_size / 1024, 2) }} KB
                                        @else
                                            Tamaño no disponible
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                    class="btn btn-primary btn-sm">
                                    <i class="ti ti-download"></i> Descargar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">
            {{-- FIRMAR - solo si no está firmado --}}
            <div class="card tenant-card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Firmar documento</h3>
                </div>
                <div class="card-body">
                    @if ($document->status === \App\Enums\DocumentStatus::SIGNED)
                        <div class="alert alert-success">
                            <i class="ti ti-check"></i>
                            Este documento ya fue firmado.
                        </div>
                    @elseif($signatures->isEmpty())
                        <div class="alert alert-warning">
                            No tiene firmas registradas.
                        </div>
                    @else
                        @foreach ($signatures as $signature)
                            <div class="border rounded p-3 mb-3">
                                <div class="fw-bold">{{ strtoupper($signature->type ?? '') }}</div>
                                <div class="text-secondary small mb-3">
                                    {{ $signature->is_default ? 'Predeterminada' : 'Firma disponible' }}
                                </div>
                                <form method="POST" action="{{ route('tenant.documents.sign', $document) }}">
                                    @csrf
                                    <input type="hidden" name="signature_id" value="{{ $signature->id }}">
                                    <button class="btn btn-primary w-100">
                                        <i class="ti ti-signature"></i> Firmar documento
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- HISTORIAL --}}
            <div class="card tenant-card">
                <div class="card-header">
                    <h3 class="card-title">Historial</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($document->statusLogs as $log)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-blue"></div>
                                <div class="timeline-content">
                                    <div class="fw-bold">{{ $log->description }}</div>
                                    <div class="text-secondary small">
                                        {{ $log->user?->name }} —
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-secondary">Sin historial.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
