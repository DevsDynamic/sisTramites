@props(['title', 'subtitle' => null])

<div class="modal-header">
    <div>
        <h3 class="modal-title mb-0">
            {{ $title }}
        </h3>

        @if ($subtitle)
            <div class="text-secondary small">
                {{ $subtitle }}
            </div>
        @endif
    </div>

    <button type="button" class="btn-close" data-bs-dismiss="modal">
    </button>
</div>
