@extends('layouts.app')

@section('title', 'Documentos')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Documentos" description="Consulta y gestiona los documentos de tu alcance.">
                <x-slot:toolbar>
                    @can('documents.create')
                        <a href="{{ route('documents.create') }}" class="btn btn-success"><i class="ti ti-plus me-1"></i>Nuevo documento</a>
                    @endcan
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <x-slot:filters>
            <x-crud.filters>
                <div class="col-lg-4">
                    <x-crud.search name="search" placeholder="Buscar por correlativo o asunto..." />
                </div>
                <div class="col-lg-2 col-md-4">
                    <select name="status" class="form-select" data-crud-filter>
                        <option value="">Todos los estados</option>
                        @foreach (\App\Enums\DocumentStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <select name="document_type_id" class="form-select" data-crud-filter>
                        <option value="">Todos los tipos</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected((string) request('document_type_id') === (string) $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <select name="area_id" class="form-select" data-crud-filter>
                        <option value="">Todas las áreas</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @selected((string) request('area_id') === (string) $area->id)>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <select name="sort" class="form-select" data-crud-filter>
                        <option value="activity" @selected(request('sort', 'activity') === 'activity')>Última actividad</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Más recientes</option>
                        <option value="code" @selected(request('sort') === 'code')>Correlativo</option>
                    </select>
                </div>
            </x-crud.filters>
        </x-slot:filters>

        <div id="documentsResults">@include('documents.partials.results')</div>
    </x-crud.index>
@endsection

@section('module', 'resources/js/modules/documents.js')
