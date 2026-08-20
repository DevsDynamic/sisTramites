@php
    $primaryRgb = hex_to_rgb(primary_color());
@endphp

<style>
    :root {

        --primary: {{ primary_color() }};
        --primary-rgb: {{ $primaryRgb }};
        --sidebar: {{ sidebar_color() }};
        --sidebar-text: {{ sidebar_text_color() }};

    }
</style>