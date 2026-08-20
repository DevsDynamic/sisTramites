@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
    'status' => null,
])

<div class="avatar avatar-{{ $size }}">
    @if ($src)
        <img src="{{ $src }}">
    @else
        {{ strtoupper(substr($name, 0, 1)) }}
    @endif

    @if ($status)
        <span class="avatar-status bg-{{ $status }}"></span>
    @endif
</div>
