@props([
    'icon' => 'ti ti-database-off',
    'title' => 'No existen registros',
    'description' => null,
    'action' => false,
    'actionText' => 'Nuevo',
    'buttonIcon' => 'ti ti-plus',
    'actionModal' => null,
    'actionHref' => null,
])

<div class="col-12">
    <div class="empty">
        <div class="empty-icon">
            <i class="{{ $icon }} fs-1"></i>
        </div>

        <p class="empty-title">
            {{ $title }}
        </p>

        @if ($description)
            <div class="empty-subtitle text-secondary">
                {{ $description }}
            </div>
        @endif

        @if ($action)
            @if ($actionModal)
                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#{{ $actionModal }}">
                    <i class="{{ $buttonIcon }}"></i>
                    {{ $actionText }}
                </button>
            @elseif($actionHref)
                <a href="{{ $actionHref }}" class="btn btn-success mt-3">
                    <i class="{{ $buttonIcon }}"></i>
                    {{ $actionText }}
                </a>
            @endif
        @endif
    </div>
</div>
