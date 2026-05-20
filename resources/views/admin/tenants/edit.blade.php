@extends('layouts.admin.app')

@section('title', 'Editar Cliente')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="page-title mb-1">
            Editar Cliente
        </h1>

        <div class="text-secondary">
            {{ $tenant->business_name }}
        </div>
    </div>

    <a href="{{ route('tenants.index') }}"
        class="btn btn-outline-secondary">

        <i class="ti ti-arrow-left"></i>
        Volver
    </a>

</div>

<form method="POST"
    action="{{ route('tenants.update', $tenant) }}">

    @csrf
    @method('PUT')

    @include(
        'admin.tenants.partials.form',
        [
            'tenant' => $tenant
        ]
    )

</form>

@endsection