@extends('layouts.auth')

@section('title', 'Login Cliente')

@section('content')

<div class="tenant-login">

    {{-- LEFT SIDE --}}

    <div class="tenant-login-left"
         style="
            background-image:
            linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
            url('{{ tenant_login_background() }}');
         ">
        <div class="tenant-overlay-content">
            <img src="{{ tenant_logo() }}"
                 class="tenant-login-logo">
            <h1>
                {{ tenant('business_name') }}
            </h1>
            <p>
                Plataforma inteligente de trámite documentario.
            </p>
        </div>
    </div>
    {{-- RIGHT SIDE --}}
    <div class="tenant-login-right">
        <div class="tenant-login-card">
            {{-- <img src="{{ tenant_logo() }}"
                 class="tenant-login-logo"> --}}
            <h2 class="mb-2">
                Bienvenido
            </h2>
            <p class="text-secondary mb-4">
                Inicia sesión para continuar
            </p>
            <form method="POST"
                  action="{{ route('tenant.login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">
                        Correo
                    </label>
                    <input type="email"
                           name="email"
                           class="form-control form-control-lg">
                </div>
                <div class="mb-4">
                    <label class="form-label">
                        Contraseña
                    </label>
                    <input type="password"
                           name="password"
                           class="form-control form-control-lg">
                </div>
                <button type="submit"
                        class="btn btn-lg w-100 text-white"
                        style="
                            background:
                            {{ tenant_primary_color() }};
                            border:none;
                        ">
                    Ingresar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection