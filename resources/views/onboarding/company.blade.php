@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Información de la empresa" description="Datos institucionales que identifican esta instalación.">
                <x-slot:toolbar>
                    <a href="{{ route('onboarding.welcome') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Volver
                    </a>
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <div class="card">
            <form method="POST" action="{{ route('onboarding.company.store') }}">
                @csrf

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre de la empresa</label>
                            <input type="text" name="company_name" class="form-control"
                                value="{{ old('company_name', $settings->company_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $settings->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $settings->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sitio web</label>
                            <input type="url" name="website" class="form-control" placeholder="https://miempresa.com"
                                value="{{ old('website', $settings->website) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="address" class="form-control"
                                value="{{ old('address', $settings->address) }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </x-crud.index>
@endsection
