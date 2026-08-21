@extends('layouts.app')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Nuevo documento" description="Registra un documento y adjunta su archivo PDF.">
                <x-slot:toolbar>
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Volver
                    </a>
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Nuevo documento
                </h3>
            </div>

            <form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('documents.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        {{-- SUBJECT --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    Asunto <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                            </div>
                        </div>

                        {{-- TYPE --}}
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label mb-0">Tipo <span class="text-danger" aria-hidden="true">*</span></label>
                                    @can('document-types.create')
                                        <button type="button" class="context-create-button context-create-button-inline" data-bs-toggle="modal"
                                            data-bs-target="#quickDocumentTypeModal">
                                            <i class="ti ti-plus"></i><span>Nuevo</span>
                                        </button>
                                    @endcan
                                </div>
                                <select name="document_type_id" id="document_type_id" class="form-select" required>
                                    <option value="">
                                        Seleccionar
                                    </option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3 position-relative">
                                <label class="form-label">
                                    Área origen <span class="text-danger" aria-hidden="true">*</span>
                                </label>

                                <select name="area_id" id="area_id" class="form-select" required>

                                    <option value="">
                                        Seleccionar
                                    </option>

                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">
                                            {{ $area->name }}
                                        </option>
                                    @endforeach

                                </select>
                                @can('areas.create')
                                    <button type="button" class="context-create-button context-create-button-floating" data-bs-toggle="modal"
                                        data-bs-target="#quickAreaModal">
                                        <i class="ti ti-plus"></i><span>Nueva &aacute;rea</span>
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div class="col-12">
                            <div id="seriesPreview" class="alert alert-info d-none mt-2 mb-4" role="status">
                                <div class="d-flex align-items-start gap-2">
                                    <i id="seriesPreviewIcon" class="ti ti-list-numbers fs-2"></i>
                                    <div>
                                        <div id="seriesPreviewTitle" class="fw-bold">Correlativo listo para asignar</div>
                                        <div id="seriesPreviewMessage">
                                            Se asignar&aacute; el correlativo <strong id="seriesPreviewCode"></strong>
                                            <span id="seriesPreviewScope" class="ms-1"></span>
                                        </div>
                                    </div>
                                </div>
                                @can('document-series.create')
                                    <div id="seriesPreviewActions" class="mt-3 d-none">
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#quickSeriesModal">
                                            <i class="ti ti-plus me-1"></i>Crear serie para esta combinaci&oacute;n
                                        </button>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Flujo de aprobación <span class="text-secondary small">(opcional)</span></label>
                                <select name="workflow_template_id" id="workflow_template_id" class="form-select">
                                    <option value="">Sin flujo · gestión directa</option>
                                    @foreach ($workflowTemplates as $workflowTemplate)
                                        <option value="{{ $workflowTemplate->id }}">
                                            {{ $workflowTemplate->name }}
                                            @if ($workflowTemplate->documentType)
                                                · {{ $workflowTemplate->documentType->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-hint">El flujo reemplaza la asignación puntual: define responsables, vistos buenos, aprobaciones y firmas.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Acción de firma inicial</label>
                                <select name="signature_mode" id="signature_mode" class="form-select">
                                    <option value="none">Sin firma por ahora</option>
                                    <option value="self">Firmaré yo mismo después de guardar</option>
                                    <option value="request">Solicitar firma a un usuario</option>
                                </select>
                                <div class="form-hint">Si eliges firmarte a ti mismo, al guardar se abrirá el documento para elegir tu firma, visto bueno y ubicación.</div>
                            </div>
                        </div>

                        <div class="col-md-6 d-none" id="signerField">
                            <div class="mb-3">
                                <label class="form-label">Usuario que debe firmar <span class="text-danger" aria-hidden="true">*</span></label>
                                <select name="signer_user_id" id="signer_user_id" class="form-select">
                                    @if ($signers->isEmpty())
                                        <option value="">No hay otros usuarios con firma activa</option>
                                    @else
                                        <option value="">Seleccionar</option>
                                        @foreach ($signers as $signer)
                                            <option value="{{ $signer->id }}">{{ $signer->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($signers->isEmpty())
                                    <div class="form-hint text-warning">Registra y activa una firma para el usuario que debe firmar.</div>
                                @endif
                            </div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Descripción
                                </label>
                                <textarea name="content" rows="4" class="form-control">{{ old('content') }}</textarea>
                            </div>
                        </div>

                        {{-- FILE --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Documento PDF <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <input id="document_file" type="file" name="file" accept="application/pdf" class="form-control" required>
                                <div id="selectedFileInfo" class="alert alert-info d-none mt-3 mb-0" role="status">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="ti ti-file-description fs-2"></i>
                                        <div class="min-w-0">
                                            <div id="selectedFileName" class="fw-bold text-truncate"></div>
                                            <div id="selectedFileMeta" class="small"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="selectedFilePreview" class="d-none mt-3 border rounded overflow-hidden bg-body-tertiary">
                                    <iframe id="selectedFileFrame" title="Vista previa del PDF seleccionado" class="w-100 border-0" style="height: 460px"></iframe>
                                </div>
                                <div class="form-hint">Solo PDF. Tamaño máximo permitido: 100 MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        <i class="ti ti-device-floppy"></i>
                        Guardar documento
                    </button>

                </div>

            </form>

        </div>

    </x-crud.index>

    @can('document-types.create')
        <x-crud.modal id="quickDocumentTypeModal" size="lg">
            <form id="quickDocumentTypeForm" method="POST" action="{{ route('document-types.store') }}">
                @csrf
                @include('document-types.partials.form', ['prefix' => 'quickDocumentType'])
            </form>
        </x-crud.modal>
    @endcan

    @can('areas.create')
        <x-crud.modal id="quickAreaModal" size="lg" :scrollable="true">
            <form id="quickAreaForm" method="POST" action="{{ route('areas.store') }}">
                @csrf
                @include('areas.partials.form', ['prefix' => 'quickArea'])
            </form>
        </x-crud.modal>
    @endcan

    @can('document-series.create')
        <x-crud.modal id="quickSeriesModal" size="lg" :scrollable="true">
            <form id="quickSeriesForm" method="POST" action="{{ route('document-series.store') }}">
                @csrf
                @include('document-series.partials.form', ['prefix' => 'quickSeries'])
            </form>
        </x-crud.modal>
    @endcan
@endsection

@section('module', 'resources/js/modules/documents.js')
