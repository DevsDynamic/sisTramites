@extends('layouts.app')

@section('content')

    <x-crud.index>

        <x-slot:header>
            <x-crud.header title="Firmas Digitales" description="Gestión de certificados y firmas visuales">
                <x-slot:toolbar>
                    <x-crud.button-action permission="signature.create" color="success" icon="ti ti-plus" text="Nuevo" modal="createModal" />
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        {{-- BUSCAR / FILTRAR --}}
        <x-slot:filters>
            <x-crud.filters>

                {{-- BUSCADOR --}}
                <div class="col-md-4">
                    <x-crud.search placeholder="Buscar FIR, usuario, titular, DNI o correo..." />
                </div>

                {{-- ESTADO --}}
                <div class="col-md-2">
                    <select name="active" class="form-select" data-crud-filter>
                        <option value="1" @selected(request('active', '1') === '1')>
                            Activos
                        </option>
                        <option value="0" @selected(request('active') === '0')>
                            Inactivos
                        </option>
                        <option value="all" @selected(request('active') === 'all')>
                            Todas
                        </option>
                    </select>
                </div>

                {{-- TIPO --}}
                <div class="col-md-3">
                    <select name="type" class="form-select" data-crud-filter>
                        <option value="">Todos los tipos</option>
                        <option value="official" @selected(request('type') === 'official')>
                            Certificado digital
                        </option>
                        <option value="visual" @selected(request('type') === 'visual')>
                            Firma visual
                        </option>
                    </select>
                </div>

                @if ($canManageAll)
                    <div class="col-md-3">
                        <select name="user_id" class="form-select" data-crud-filter>
                            <option value="">Todos los usuarios</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

            </x-crud.filters>
        </x-slot:filters>

        {{-- RESULTADOS --}}
        <div id="signaturesResults">
            @include('signatures.partials.results')
        </div>

    </x-crud.index>

    <x-crud.modal-create :action="route('signatures.store')">
        @include('signatures.partials.form', ['prefix' => 'create', 'canManageAll' => $canManageAll])
    </x-crud.modal-create>

    <x-crud.modal-edit>
        @include('signatures.partials.form', ['prefix' => 'edit', 'canManageAll' => $canManageAll])
    </x-crud.modal-edit>

    <x-crud.modal-active />

    <x-crud.modal-delete entity="Firma" />

@endsection

@section('module', 'resources/js/modules/signatures.js')
