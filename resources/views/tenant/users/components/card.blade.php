<div class="card h-100 border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <div class="fw-bold fs-3">
                    {{ $area->name }}
                </div>
                <div class="text-secondary">
                    {{ $area->code }}
                </div>
            </div>
            <div>
                @if ($area->active)
                    <span class="badge bg-success-lt">
                        Activo
                    </span>
                @else
                    <span class="badge bg-danger-lt">
                        Inactivo
                    </span>
                @endif
            </div>
        </div>
        <div class="mt-3 text-secondary">
            {{ $area->description }}
        </div>
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#editArea{{ $area->id }}">
                <i class="ti ti-edit"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                data-bs-target="#deleteArea{{ $area->id }}">
                <i class="ti ti-trash"></i>
            </button>
        </div>
    </div>
</div>