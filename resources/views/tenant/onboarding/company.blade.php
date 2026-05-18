@extends('layouts.tenant.onboarding.app')

@section('content')
    <form method="POST" action="{{ route('tenant.onboarding.company.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Razón Social
                </label>
                <input type="text" name="business_name" class="form-control" value="{{ tenant('business_name') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Nombre comercial
                </label>
                <input type="text" name="trade_name" class="form-control" value="{{ tenant('trade_name') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    RUC
                </label>
                <input type="text" name="ruc" class="form-control" value="{{ tenant('ruc') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Teléfono
                </label>
                <input type="text" name="phone" class="form-control" value="{{ tenant('phone') }}">
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                Guardar y Continuar
            </button>
        </div>
    </form>
@endsection
