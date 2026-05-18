@extends('layouts.auth')

@section('title', 'Login Admin')

@section('content')

<div class="card card-md">

    <div class="card-body">

        <h2 class="h2 text-center mb-4">

            Panel Administrativo

        </h2>

        <form method="POST"
              action="{{ route('login') }}">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Correo
                </label>

                <input type="email"
                       name="email"
                       class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Contraseña
                </label>

                <input type="password"
                       name="password"
                       class="form-control">

            </div>

            <div class="form-footer">

                <button type="submit"
                        class="btn btn-primary w-100">

                    Ingresar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection