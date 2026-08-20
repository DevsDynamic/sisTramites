@props([
    'id' => 'statusModal',
    'formId' => 'statusForm',
])

<x-crud.modal :id="$id" size="md">
    <form id="{{ $formId }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body text-center py-5">
            <i id="statusIcon" style="font-size:70px">
            </i>

            <h3 id="statusTitle" class="mt-3">
            </h3>

            <p id="statusMessage" class="text-secondary mb-0">
            </p>
        </div>
        <x-crud.modal-footer color="primary" icon="ti ti-check" text="Confirmar" />
    </form>
</x-crud.modal>
