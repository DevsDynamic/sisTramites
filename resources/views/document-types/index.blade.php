@extends('layouts.app')

@section('content')

    <x-crud.index>

        <x-slot:header>
            <x-crud.header title="Tipos de documento" description="Administra los tipos de documentos del sistema">
                <x-slot:toolbar>
                    <x-crud.button-action color="success" icon="ti ti-plus" text="Nuevo" modal="createModal" />
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        {{-- BUSCAR / FILTRAR --}}
        <x-slot:filters>
            <x-crud.filters>

                {{-- BUSCADOR --}}
                <div class="col-md-8">
                    <x-crud.search placeholder="Buscar por nombre o código (ej.: TDOC-0001)..." />
                </div>

                {{-- ESTADO --}}
                <div class="col-md-4">
                    <select name="active" class="form-select" data-crud-filter>
                        <option value="1" @selected(request('active', '1') === '1')>
                            Activos
                        </option>
                        <option value="0" @selected(request('active') === '0')>
                            Inactivos
                        </option>
                        <option value="all" @selected(request('active') === 'all')>
                            Todos
                        </option>
                    </select>
                </div>

            </x-crud.filters>
        </x-slot:filters>

        {{-- RESULTADOS --}}
        <div id="documentTypesResults">
            @include('document-types.partials.results')
        </div>

    </x-crud.index>

    <x-crud.modal-create :action="route('document-types.store')">
        @include('document-types.partials.form', ['prefix' => 'create'])
    </x-crud.modal-create>

    <x-crud.modal-edit>
        @include('document-types.partials.form', ['prefix' => 'edit'])
    </x-crud.modal-edit>

    <x-crud.modal-active />

    <x-crud.modal-delete entity="Tipo de documento" />

@endsection

@section('module', 'resources/js/modules/document-types.js')
