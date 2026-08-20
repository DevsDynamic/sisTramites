@props(['action', 'method' => 'POST', 'id' => null, 'ajax' => true, 'files' => false])

<form id="{{ $id }}" action="{{ $action }}" method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}"
    @if ($files) enctype="multipart/form-data" @endif
    @if ($ajax) data-ajax="true" @endif>

    @if (!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    @csrf

    {{ $slot }}

</form>
