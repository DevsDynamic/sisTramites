@props([
    'color' => 'primary',
    'icon' => 'ti ti-device-floppy',
    'text' => 'Guardar',
    'id' => null,
])

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
        Cancelar
    </button>

    <x-crud.button-action type="submit" id="{{ $id }}" :color="$color" :icon="$icon" :text="$text" loading />
</div>
