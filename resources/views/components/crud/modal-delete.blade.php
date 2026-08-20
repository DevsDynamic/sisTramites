@props([
    'id' => 'deleteModal',
    'formId' => 'deleteForm',
])

<x-crud.modal :id="$id" size="md">
    <form id="{{ $formId }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body text-center py-5">
            <i class="ti ti-trash text-danger" style="font-size:70px">
            </i>
            <h3 class="mt-3">
                Eliminar
                <span id="deleteEntity"></span>
            </h3>

            <div class="text-secondary">
                Se eliminará
                <strong id="deleteName"></strong>
                de forma permanente.
            </div>
        </div>

        <x-crud.modal-footer color="danger" icon="ti ti-trash" text="Eliminar" />
    </form>
</x-crud.modal>
