@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Roles" description="Administra los roles y permisos del sistema">
                <x-slot:toolbar>
                    <x-crud.button-action permission="roles.create" color="primary" icon="ti ti-plus" text="Nuevo rol" modal="createModal" />
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <x-slot:filters>
            <x-crud.filters>
                <div class="col-md-7">
                    <x-crud.search placeholder="Buscar rol..." />
                </div>

                <div class="col-md-5">
                    <select name="module" class="form-select" data-crud-filter>
                        <option value="">Todos los módulos</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module }}" @selected(request('module') === $module)>
                                {{ \App\Models\Permission::moduleLabel($module) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </x-crud.filters>
        </x-slot:filters>

        <div id="rolesResults">
            @include('roles.partials.results')
        </div>
    </x-crud.index>

    <x-crud.modal-create :action="route('roles.store')" size="xl" :scrollable="true">
        @include('roles.partials.form', ['prefix' => 'create'])
    </x-crud.modal-create>

    <x-crud.modal-edit size="xl" :scrollable="true">
        @include('roles.partials.form', ['prefix' => 'edit'])
    </x-crud.modal-edit>

    <x-crud.modal-delete entity="Rol" />
@endsection

@section('module', 'resources/js/modules/roles.js')
