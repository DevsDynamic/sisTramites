@extends('layouts.admin.app')

@section('title', 'Nuevo Cliente')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title mb-1">
                Nuevo Cliente
            </h1>

            <div class="text-secondary">
                Registrar nueva empresa SaaS
            </div>
        </div>

        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">

            <i class="ti ti-arrow-left"></i>
            Volver
        </a>

    </div>

    <form method="POST" action="{{ route('tenants.store') }}">

        @csrf

        @include('admin.tenants.partials.form', [
            'tenant' => null,
        ])

    </form>

@endsection

@push('scripts')
    @include('admin.tenants.scripts')
@endpush
