@props([
    'text' => null,
    'icon' => null,
    'active' => null,
    'color' => 'primary',
    'clickable' => false,
    'modal' => null,
    'href' => null,
    'title' => null,
    'dataset' => [],
])

@php
    if (!is_null($active)) {
        $text = $active ? 'Activo' : 'Inactivo';
        $color = $active ? 'success' : 'danger';
        $clickable = true;
    }
@endphp

<span {{ $attributes->class(['badge', "bg-{$color}-lt", $clickable ? 'cursor-pointer status-btn' : null]) }}
    @if ($clickable) role="button" tabindex="0" @endif
    @if ($modal) data-bs-toggle="modal" data-bs-target="#{{ $modal }}" @endif
    @if ($title) data-bs-toggle-tooltip="tooltip" title="{{ $title }}" @endif
    @if ($href) onclick="window.location='{{ $href }}'" @endif
    @foreach ($dataset as $key => $value) data-{{ $key }}="{{ $value }}" @endforeach>
    @if ($icon)
        <i class="{{ $icon }} me-1"></i>
    @endif
    {{ $text }}
</span>
