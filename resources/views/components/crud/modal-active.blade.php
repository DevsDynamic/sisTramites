@props([
    'id' => 'activeModal',
    'formId' => 'activeForm',
])

<x-crud.modal id="{{ $id }}" size="md">
    <form id="{{ $formId }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="modal-body text-center py-5">
            <i id="activeIcon" style="font-size:70px">
            </i>

            <h3 id="activeTitle" class="mt-3">
            </h3>

            <div class="text-secondary">
                ¿Está seguro que desea
                <strong id="activeActionText">
                </strong>
                <strong id="activeEntityName">
                </strong>?
            </div>
        </div>

        {{-- <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancelar
            </button>
            <button id="activeSubmit" class="btn">
            </button>
        </div> --}}

        <x-crud.modal-footer id="activeSubmit" />
    </form>
</x-crud.modal>
