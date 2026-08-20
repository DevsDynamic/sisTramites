@props(['title', 'subtitle' => null])

<div class="d-flex justify-content-between align-items-start w-100">
    <div class="flex-fill pe-3" style="min-width:0">
        <div class="fw-bold fs-3 text-truncate" title="{{ $title }}">
            {{ $title }}
        </div>

        @if ($subtitle)
            <div class="text-secondary">
                {{ $subtitle }}
            </div>
        @endif
    </div>

    @isset($badge)
        <div class="ms-2 flex-shrink-0">
            {{ $badge }}
        </div>
    @endisset
</div>
