@props([
    'id' => 'editModal',
    'formId' => 'editForm',
    'size' => 'lg',
    'scrollable' => false,
])

<x-crud.modal id="{{ $id }}" :size="$size" :scrollable="$scrollable">
    <form id="{{ $formId }}" method="POST">
        @csrf
        @method('PUT')
        {{ $slot }}
    </form>
</x-crud.modal>
