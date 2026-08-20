@props(['title', 'value', 'icon' => null, 'color' => 'primary', 'col' => 'col-md-3'])

<div class="{{ $col }}">
    <div class="card h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <div class="text-secondary">
                    {{ $title }}
                </div>

                <div class="display-6 fw-bold">
                    {{ $value }}
                </div>
            </div>

            @if ($icon)
                <div class="avatar avatar-lg bg-{{ $color }}-lt">
                    <i class="{{ $icon }}"></i>
                </div>
            @endif
        </div>
    </div>
</div>
