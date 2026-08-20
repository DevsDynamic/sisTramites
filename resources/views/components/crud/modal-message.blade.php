@props([
    'id' => 'messageModal',
])

<x-crud.modal :id="$id" size="md">
    <div class="modal-body text-center py-5">
        <i id="messageIcon" style="font-size:70px">
        </i>

        <h3 id="messageTitle" class="mt-3">
        </h3>

        <div id="messageText" class="text-secondary mt-2">
        </div>
    </div>

    <div id="messageFooter" class="modal-footer">
        <button class="btn btn-primary" data-bs-dismiss="modal">
            Aceptar
        </button>
    </div>
</x-crud.modal>
