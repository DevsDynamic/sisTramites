@extends('layouts.tenant.app')

@section('content')
    <div class="container-xl">

        <div class="card tenant-card">

            <div class="card-header">
                <h3 class="card-title">
                    Nuevo documento
                </h3>
            </div>

            <form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('tenant.documents.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        {{-- SUBJECT --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    Asunto
                                </label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>
                        </div>

                        {{-- TYPE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    Tipo
                                </label>
                                <select name="document_type_id" class="form-select" required>
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

                        {{-- DESCRIPTION --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Descripción
                                </label>
                                <textarea name="description" rows="4" class="form-control"></textarea>
                            </div>
                        </div>

                        {{-- FILE --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Documento PDF
                                </label>
                                <input type="file" name="file" accept="application/pdf" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i>
                        Guardar documento
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
