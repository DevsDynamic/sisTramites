@props(['id', 'size' => 'lg', 'centered' => true, 'scrollable' => false])

<div class="modal modal-blur fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div
        class="
        modal-dialog
        modal-{{ $size }}
        {{ $centered ? 'modal-dialog-centered' : '' }}
        {{ $scrollable ? 'modal-dialog-scrollable' : '' }}
    ">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
