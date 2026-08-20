@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Usuarios" description="Administra las cuentas, roles y áreas del sistema">
                <x-slot:toolbar>
                    <x-crud.button-action permission="users.create" color="success" icon="ti ti-plus" text="Nuevo" modal="createModal" />
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <x-slot:filters>
            <x-crud.filters>
                <div class="col-md-4">
                    <x-crud.search placeholder="Buscar por nombre o correo..." />
                </div>

                <div class="col-md-2">
                    <select name="active" class="form-select" data-crud-filter>
                        <option value="1" @selected(request('active', '1') === '1')>Activos</option>
                        <option value="0" @selected(request('active') === '0')>Inactivos</option>
                        <option value="all" @selected(request('active') === 'all')>Todos</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="role" class="form-select" data-crud-filter>
                        <option value="">Todos los roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="area_id" class="form-select" data-crud-filter>
                        <option value="">Todas las áreas</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @selected((string) request('area_id') === (string) $area->id)>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </x-crud.filters>
        </x-slot:filters>

        <div id="usersResults">
            @include('users.partials.results')
        </div>
    </x-crud.index>

    <x-crud.modal-create :action="route('users.store')">
        @include('users.partials.form', ['prefix' => 'create'])
    </x-crud.modal-create>

    <x-crud.modal-edit>
        @include('users.partials.form', ['prefix' => 'edit'])
    </x-crud.modal-edit>

    <x-crud.modal-active />
    <x-crud.modal-delete entity="Usuario" />
@endsection

@section('module', 'resources/js/modules/users.js')
