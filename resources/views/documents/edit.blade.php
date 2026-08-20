@extends('layouts.app')

@section('title', 'Editar documento')

@section('content')
    <x-crud.index>
        <x-slot:header>
            <x-crud.header title="Editar borrador" description="Solo se pueden modificar los datos de un documento antes de iniciar su flujo.">
                <x-slot:toolbar>
                    <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Volver
                    </a>
                </x-slot:toolbar>
            </x-crud.header>
        </x-slot:header>

        <div class="card">
            <form method="POST" action="{{ route('documents.update', $document) }}">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" value="{{ $document->code }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de documento</label>
                            <input type="text" class="form-control" value="{{ $document->type?->name }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Área de origen</label>
                            <input type="text" class="form-control" value="{{ $document->area?->name }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Asunto</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject', $document->subject) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="content" rows="6" class="form-control">{{ old('content', $document->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-edit me-1"></i>Actualizar borrador
                    </button>
                </div>
            </form>
        </div>
    </x-crud.index>
@endsection
