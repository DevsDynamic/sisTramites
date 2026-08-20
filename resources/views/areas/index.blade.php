@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Áreas" description="Administra la estructura organizacional">
                <x-slot:toolbar>
                    <x-crud.button-action permission="areas.create" color="success" icon="ti ti-plus" text="Nueva área" modal="createModal" />
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <x-slot:filters>
            <x-crud.filters>
                <div class="col-md-8">
                    <x-crud.search placeholder="Buscar por nombre, código o descripción..." />
                </div>

                <div class="col-md-4">
                    <select name="active" class="form-select" data-crud-filter>
                        <option value="1" @selected(request('active', '1') === '1')>Activas</option>
                        <option value="0" @selected(request('active') === '0')>Inactivas</option>
                        <option value="all" @selected(request('active') === 'all')>Todas</option>
                    </select>
                </div>
            </x-crud.filters>
        </x-slot:filters>

        <div id="areasResults">
            @include('areas.partials.results')
        </div>
    </x-crud.index>

    <x-crud.modal-create :action="route('areas.store')">
        @include('areas.partials.form', ['prefix' => 'create'])
    </x-crud.modal-create>

    <x-crud.modal-edit>
        @include('areas.partials.form', ['prefix' => 'edit'])
    </x-crud.modal-edit>

    <x-crud.modal-active />
    <x-crud.modal-delete entity="Área" />
@endsection

@section('module', 'resources/js/modules/areas.js')
