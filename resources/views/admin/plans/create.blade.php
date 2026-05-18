@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">
        Nuevo Plan
    </h2>

    <form method="POST" action="{{ route('plans.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nombre
                        </label>

                        <input type="text" name="name" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Precio
                        </label>
                        <input type="number" step="0.01" name="price" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Duración
                        </label>
                        <input type="number" name="duration_days" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Usuarios
                        </label>
                        <input type="number" name="max_users" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Firmas
                        </label>
                        <input type="number" name="max_signatures" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Documentos
                        </label>
                        <input type="number" name="max_documents" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <button class="btn btn-primary">
                    Guardar
                </button>
            </div>
        </div>
    </form>
@endsection
