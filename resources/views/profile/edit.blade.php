@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header><x-crud.header title="Mi perfil" description="Administra tu información personal, avatar y seguridad de acceso." /></x-slot:header>

        <div class="row row-cards">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Información personal</h3></div>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">@csrf @method('PATCH')
                        <div class="card-body"><div class="row g-3">
                            <div class="col-12 d-flex align-items-center gap-3">
                                <span class="avatar avatar-xl bg-primary-lt text-primary" id="avatarPreviewBox">
                                    <img id="avatarPreview" src="{{ $user->avatar_path ? route('profile.avatar') : '' }}" alt="Tu avatar" @class(['d-none' => ! $user->avatar_path])>
                                    <i id="avatarPlaceholder" class="ti ti-user fs-1 {{ $user->avatar_path ? 'd-none' : '' }}"></i>
                                </span>
                                <div class="flex-fill"><label class="form-label">Avatar</label><input type="file" name="avatar" id="avatarInput" class="form-control" accept="image/png,image/jpeg,image/webp"><div class="form-hint">JPG, PNG o WEBP. Máximo 2 MB. La imagen se muestra antes de guardar.</div></div>
                            </div>
                            <div class="col-md-6"><label class="form-label">Nombre</label><input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-6"><label class="form-label">Correo</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div></div>
                        <div class="card-footer text-end"><button class="btn btn-success"><i class="ti ti-device-floppy me-1"></i>Guardar cambios</button></div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Cambiar contraseña</h3></div>
                    <form method="POST" action="{{ route('profile.password.update') }}">@csrf @method('PUT')
                        <div class="card-body"><div class="row g-3">
                            <div class="col-12"><label class="form-label">Contraseña actual</label><div class="input-group"><input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password"><button type="button" class="btn btn-outline-secondary password-toggle" aria-label="Mostrar contraseña"><i class="ti ti-eye"></i></button></div>@error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                            <div class="col-12"><label class="form-label">Nueva contraseña</label><div class="input-group"><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password"><button type="button" class="btn btn-outline-secondary password-toggle" aria-label="Mostrar contraseña"><i class="ti ti-eye"></i></button></div>@error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
                            <div class="col-12"><label class="form-label">Confirmar nueva contraseña</label><div class="input-group"><input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password"><button type="button" class="btn btn-outline-secondary password-toggle" aria-label="Mostrar contraseña"><i class="ti ti-eye"></i></button></div></div>
                        </div></div>
                        <div class="card-footer text-end"><button class="btn btn-warning"><i class="ti ti-lock me-1"></i>Actualizar contraseña</button></div>
                    </form>
                </div>
            </div>
        </div>
    </x-crud.index>
@endsection

@push('scripts')
    <script>
        document.getElementById('avatarInput')?.addEventListener('change', event => {
            const file = event.target.files?.[0];
            if (!file) return;

            const preview = document.getElementById('avatarPreview');
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            document.getElementById('avatarPlaceholder').classList.add('d-none');
        });

        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.previousElementSibling;
                const visible = input.type === 'text';
                input.type = visible ? 'password' : 'text';
                button.querySelector('i').className = visible ? 'ti ti-eye' : 'ti ti-eye-off';
                button.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
            });
        });
    </script>
@endpush
