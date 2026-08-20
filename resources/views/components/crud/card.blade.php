@props([
    'col' => 'col-md-4',
    'stretch' => true,
])

<div {{ $attributes->class([$col]) }}>
    <div @class(['card', 'h-100' => $stretch])>

        @isset($header)
            <div class="card-header">
                {{ $header }}
            </div>
        @endisset

        @if (trim($slot))
            <div class="card-body">
                {{ $slot }}
            </div>
        @endif

        @isset($footer)
            <div class="card-footer">
                {{ $footer }}
            </div>
        @endisset

    </div>
</div>
