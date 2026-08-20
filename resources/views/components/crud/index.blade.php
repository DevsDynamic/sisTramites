<div class="crud-index">

    @if (isset($header))
        {{ $header }}
    @endif

    @if (isset($filters))
        {{ $filters }}
    @endif

    <div class="crud-content">
        {{ $slot }}
    </div>

    @if (isset($pagination))
        {{ $pagination }}
    @endif

</div>
