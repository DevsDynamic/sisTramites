@extends('layouts.tenant.onboarding.app')

@section('content')

<form method="POST"
      action="{{ route('tenant.onboarding.sunat.store') }}">

    @csrf

    {{-- USER SOL --}}

    <div class="mb-4">

        <label class="form-label">

            Usuario SOL

        </label>

        <input type="text"
               name="sunat_user"
               class="form-control"

               value="{{ old('sunat_user', tenant('sunat_user')) }}"

               placeholder="Ingrese usuario SOL">

    </div>

    {{-- PASSWORD SOL --}}

    <div class="mb-4">

        <label class="form-label">

            Clave SOL

        </label>

        <div class="input-group">

            <input type="password"

                   name="sunat_password"

                   id="sunatPassword"

                   class="form-control"

                   value="{{ tenant('sunat_password')
                        ? decrypt(tenant('sunat_password'))
                        : '' }}"

                   placeholder="Ingrese clave SOL">

            <button type="button"
                    class="btn btn-outline-secondary"
                    onclick="togglePassword()">

                <i class="ti ti-eye"
                   id="eyeIcon"></i>

            </button>

        </div>

        <div class="form-hint mt-2">

            Credenciales usadas para conexión SUNAT.

        </div>

    </div>

    {{-- ACTIONS --}}

    <div class="d-flex justify-content-between mt-5">

        <a href="{{ route('tenant.onboarding.branding') }}"
           class="btn btn-outline-secondary">

            Atrás

        </a>

        <button type="submit"
                class="btn btn-success">

            Guardar y Finalizar

        </button>

    </div>

</form>

{{-- PASSWORD TOGGLE --}}

<script>

    function togglePassword()
    {
        const input =
            document.getElementById('sunatPassword');

        const icon =
            document.getElementById('eyeIcon');

        /*
        |--------------------------------------------------------------------------
        | SHOW
        |--------------------------------------------------------------------------
        */

        if (input.type === 'password') {

            input.type = 'text';

            icon.classList.remove('ti-eye');

            icon.classList.add('ti-eye-off');

        } else {

            /*
            |--------------------------------------------------------------------------
            | HIDE
            |--------------------------------------------------------------------------
            */

            input.type = 'password';

            icon.classList.remove('ti-eye-off');

            icon.classList.add('ti-eye');
        }
    }

</script>

@endsection