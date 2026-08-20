@props(['title', 'description' => null])

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
    <div>
        <h1 class="page-title mb-1">
            {{ $title }}
        </h1>

        @if ($description)
            <div class="text-secondary">
                {{ $description }}
            </div>
        @endif
    </div>

    @isset($toolbar)
        <x-crud.toolbar>
            {{ $toolbar }}
        </x-crud.toolbar>
    @endisset
</div>
