@props([
    'title' => null,
    'icon' => null,
])

<x-crud.card>
    @if ($title)
        <x-slot:header>
            <div class="d-flex align-items-center gap-2">
                @if ($icon)
                    <i class="{{ $icon }}"></i>
                @endif

                <span class="fw-semibold">
                    {{ $title }}
                </span>
            </div>
        </x-slot:header>
    @endif

    {{ $slot }}

    @isset($footer)
        <x-slot:footer>
            {{ $footer }}
        </x-slot:footer>
    @endisset
</x-crud.card>
