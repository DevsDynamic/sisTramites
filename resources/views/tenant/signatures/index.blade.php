@extends('layouts.tenant.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">
                Firmas Digitales
            </h1>
            <div class="text-secondary">
                Gestión de certificados y firmas visuales
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="ti ti-plus"></i>
            Nueva Firma
        </button>
    </div>

    <div class="row row-cards">
        @forelse($signatures as $signature)
            <div class="col-md-4">
                <div class="card tenant-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-bold">
                                    {{ $signature->user->name }}
                                </div>
                                <div class="text-secondary">
                                    {{ strtoupper($signature->type) }}
                                </div>
                            </div>

                            <span class="badge bg-success-lt">
                                Activa
                            </span>
                        </div>

                        <div class="mt-3">
                            @if ($signature->signature_image)
                                <img src="{{ asset('storage/' . $signature->signature_image) }}" class="img-fluid rounded">
                            @endif
                        </div>

                        <div class="mt-3 text-secondary">
                            Expira:
                            {{ optional($signature->expires_at)->format('d/m/Y') ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty">
                    <div class="empty-icon">
                        <i class="ti ti-signature fs-1"></i>
                    </div>
                    <p class="empty-title">
                        No hay tipos de documentos registrados
                    </p>
                </div>
            </div>
        @endforelse
    </div>
    @include('tenant.signatures.modals.create')
@endsection

@push('scripts')
    @include('tenant.signatures.scripts')
@endpush