@props([
    'color' => 'primary',
    'icon' => null,
    'text' => null,
    'title' => null,
    'modal' => null,
    'size' => null,
    'outline' => false,
    'rounded' => false,
    'loading' => true,
    'dataset' => [],
    'type' => 'button',
    'permission' => null,
    'href' => null,
])

@php $buttonClass = $outline ? "btn-outline-$color" : "btn-$color"; @endphp

@can($permission)
    @if($href)
        <a href="{{ $href }}" {{ $attributes->class(['btn', $buttonClass, "btn-$size", $rounded ? 'rounded-pill' : null]) }}
            @if ($title) data-bs-toggle-tooltip="tooltip" title="{{ $title }}" @endif>
            <i class="{{ $icon }}"></i>{{ $text }}{{ $slot }}
        </a>
    @else
        <button type="{{ $type }}" {{ $attributes->class(['btn', $buttonClass, "btn-$size", $rounded ? 'rounded-pill' : null]) }}
            @if ($modal) data-bs-toggle="modal" data-bs-target="#{{ $modal }}" @endif
            @if ($title) data-bs-toggle-tooltip="tooltip" title="{{ $title }}" @endif
            @foreach ($dataset as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach>
            <span class="btn-content">
                @if ($icon)<i class="{{ $icon }}"></i>@endif
                {{ $text }}{{ $slot }}
            </span>
            @if ($loading)<span class="btn-loading d-none"><i class="ti ti-loader-2 ti-spin"></i></span>@endif
        </button>
    @endif
@endcan
