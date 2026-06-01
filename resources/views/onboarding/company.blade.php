@extends('layouts.onboarding.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('onboarding.company.store') }}">
        @csrf

        <div class="row">

            {{-- EMPRESA --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">
                    Nombre de la empresa
                </label>

                <input
                    type="text"
                    name="company_name"
                    class="form-control"
                    value="{{ old('company_name', setting()->company_name) }}"
                    required>
            </div>

            {{-- EMAIL --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', setting()->email) }}">
            </div>

            {{-- TELÉFONO --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Teléfono
                </label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone', setting()->phone) }}">
            </div>

            {{-- WEB --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Sitio web
                </label>

                <input
                    type="text"
                    name="website"
                    class="form-control"
                    placeholder="https://miempresa.com"
                    value="{{ old('website', setting()->website) }}">
            </div>

            {{-- DIRECCIÓN --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Dirección
                </label>

                <input
                    type="text"
                    name="address"
                    class="form-control"
                    value="{{ old('address', setting()->address) }}">
            </div>

        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                Guardar y Continuar
            </button>
        </div>

    </form>

@endsection