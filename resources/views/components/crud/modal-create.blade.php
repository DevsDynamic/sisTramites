@props([
    'id' => 'createModal',
    'formId' => 'createForm',
    'action',
    'size' => 'lg',
    'scrollable' => false,
])

<x-crud.modal id="{{ $id }}" :size="$size" :scrollable="$scrollable">
    <form id="{{ $formId }}" method="POST" action="{{ $action }}">
        @csrf
        {{ $slot }}
    </form>
</x-crud.modal>
