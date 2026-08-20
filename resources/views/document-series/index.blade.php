@extends('layouts.app')

@section('content')

    <x-crud.index>

        <x-slot:header>
            <x-crud.header title="Series" description="Administra las series disponibles por tipo de documento">
                <x-slot:toolbar>
                    <x-crud.button-action color="success" icon="ti ti-plus" text="Nuevo" modal="createModal" />
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        {{-- BUSCAR / FILTRAR --}}
        <x-slot:filters>
            <x-crud.filters>

                {{-- BUSCADOR --}}
                <div class="col-md-3">
                    <x-crud.search placeholder="Buscar prefijo, tipo o área..." />
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

                {{-- TIPO DOCUMENTO --}}
                <div class="col-md-3">
                    <select name="document_type_id" class="form-select" data-crud-filter>
                        <option value="">Todos los tipos</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected((string) request('document_type_id') === (string) $type->id)>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ÁREA --}}
                <div class="col-md-4">
                    <select name="area_id" class="form-select" data-crud-filter>
                        <option value="">Todas las áreas</option>
                        <option value="global" @selected(request('area_id') === 'global')>
                            Global (sin área)
                        </option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @selected((string) request('area_id') === (string) $area->id)>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </x-crud.filters>
        </x-slot:filters>

        {{-- RESULTADOS --}}
        <div id="documentSeriesResults">
            @include('document-series.partials.results')
        </div>

    </x-crud.index>


    {{-- CREAR --}}
    <x-crud.modal-create :action="route('document-series.store')">
        @include('document-series.partials.form', [
            'prefix' => 'create',
        ])
    </x-crud.modal-create>

    {{-- EDITAR --}}
    <x-crud.modal-edit>
        @include('document-series.partials.form', [
            'prefix' => 'edit',
        ])
    </x-crud.modal-edit>

    {{-- ACTIVAR / DESACTIVAR --}}
    <x-crud.modal-active />

    {{-- ELIMINAR --}}
    <x-crud.modal-delete entity="Serie" />

@endsection

@section('module', 'resources/js/modules/document-series.js')
