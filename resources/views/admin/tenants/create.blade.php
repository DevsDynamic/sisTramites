@extends('layouts.admin.app')

@section('title', 'Nuevo Cliente')

@section('content')
    <h2 class="mb-4">Nuevo Cliente</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tenants.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">

                    {{-- RAZÓN SOCIAL --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Razón Social</label>
                        <input type="text"
                               name="business_name"
                               value="{{ old('business_name') }}"
                               class="form-control @error('business_name') is-invalid @enderror"
                               required>
                        @error('business_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NOMBRE COMERCIAL --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre Comercial</label>
                        <input type="text"
                               name="trade_name"
                               value="{{ old('trade_name') }}"
                               class="form-control @error('trade_name') is-invalid @enderror">
                        @error('trade_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- RUC --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">RUC</label>
                        <input type="text"
                               name="ruc"
                               value="{{ old('ruc') }}"
                               class="form-control @error('ruc') is-invalid @enderror"
                               maxlength="11"
                               required>
                        @error('ruc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email Empresa</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TELÉFONO --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SUBDOMINIO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subdominio</label>
                        <div class="input-group">
                            <input type="text"
                                   name="domain"
                                   value="{{ old('domain') }}"
                                   class="form-control @error('domain') is-invalid @enderror"
                                   required>
                            <span class="input-group-text">.{{ config('saas.central_domain') }}</span>
                            @error('domain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- PLAN --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plan</label>
                        <select name="plan_id"
                                class="form-select @error('plan_id') is-invalid @enderror"
                                required>
                            <option value="">Seleccionar</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - S/ {{ $plan->price }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ADMIN EMAIL --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usuario Administrador</label>
                        <input type="email"
                               name="admin_email"
                               value="{{ old('admin_email') }}"
                               class="form-control @error('admin_email') is-invalid @enderror"
                               required>
                        @error('admin_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('tenants.index') }}" class="btn btn-secondary me-2">
                    Cancelar
                </a>
                <button class="btn btn-primary">
                    Crear Cliente
                </button>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
@endsection