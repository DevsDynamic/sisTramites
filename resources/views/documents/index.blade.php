@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title mb-1">
            Documentos
        </h1>
        <div class="text-secondary">
            Gestión documental
        </div>
    </div>

    <a href="{{ route('documents.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i>
        Nuevo documento
    </a>
</div>

<div class="row row-cards">
    @forelse($documents as $document)
        <div class="col-md-4">
            <div class="card card h-100">
                {{-- HEADER --}}
                <div class="card-header">
                    <div>
                        <div class="fw-bold fs-3">
                            {{ $document->subject }}
                        </div>
                        <div class="text-secondary">
                            {{ $document->type?->name }}
                        </div>
                    </div>
                    <span class="badge bg-blue-lt">
                        {{-- {{ strtoupper($document->status) }} --}}
                    </span>
                </div>

                {{-- BODY --}}
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-secondary small">
                            Descripción
                        </div>
                        <div>
                            {{ Str::limit($document->description, 120) }}
                        </div>
                    </div>
                    <div class="row text-center">
                        {{-- FILES --}}
                        <div class="col-6">
                            <div class="text-secondary small">
                                Adjuntos
                            </div>
                            <div class="fw-bold">
                                {{ $document->attachments->count() }}
                            </div>
                        </div>
                        {{-- DATE --}}
                        <div class="col-6">
                            <div class="text-secondary small">
                                Fecha
                            </div>
                            <div class="fw-bold">
                                {{ $document->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0">
                    <a
                        href="{{ route('documents.show', $document) }}"
                        class="btn btn-primary w-100">
                        <i class="ti ti-eye"></i>
                        Ver documento
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty">
                <div class="empty-icon">
                    <i class="ti ti-file fs-1"></i>
                </div>
                <p class="empty-title">
                    No hay documentos
                </p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $documents->links() }}
</div>

@endsection

@push('module-js')
    @vite('resources/js/modules/documents.js')
@endpush