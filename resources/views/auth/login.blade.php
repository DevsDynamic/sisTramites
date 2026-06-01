@extends('layouts.auth')

@section('title', 'Login Cliente')

@section('content')

    <div class="login">
        {{-- LEFT SIDE --}}
        <div class="login-left"
            style="background-image:
                    linear-gradient(
                    rgba(0,0,0,.55),
                    rgba(0,0,0,.55)
                    ),
                    url('{{ setting()?->login_background ? asset('storage/' . setting()->login_background) : 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1974' }}');
                    background-size:cover;
                    background-position:center;">
                <div class="overlay-content">
                    <img src="{{ setting()?->logo ? asset('storage/' . setting()->logo) : 'https://placehold.co/200x200?text=LOGO' }}"
                        class="login-logo">
                    <h1>
                        @if (setting()?->company_name)
                            {{ setting()->company_name }}
                        @endif
                    </h1>
                    <p>
                        Plataforma inteligente de trámite documentario.
                    </p>
                </div>
            </div>
            {{-- RIGHT SIDE --}}
            <div class="login-right">
                <div class="login-card">
                    <h2 class="mb-2">
                        Bienvenido
                    </h2>
                    <p class="text-secondary mb-4">
                        Inicia sesión para continuar
                    </p>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                Correo
                            </label>
                            @error('email')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control form-control-lg">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">
                                Contraseña
                            </label>
                            @error('password')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="input-group">
                                <input id="password" type="password" name="password" class="form-control form-control-lg">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                    <i class="ti ti-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            function togglePassword() {
                const field = document.getElementById('password');

                field.type =
                    field.type === 'password' ?
                    'text' :
                    'password';
            }
        </script>
    @endpush
