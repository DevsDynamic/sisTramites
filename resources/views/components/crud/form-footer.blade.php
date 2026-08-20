@props([
    'submit' => 'Guardar',

    'submitColor' => 'primary',

    'submitIcon' => 'ti ti-device-floppy',

    'cancel' => true,

    'cancelText' => 'Cancelar',
])

<div class="d-flex justify-content-end gap-2 mt-4">

    @if ($cancel)
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">

            {{ $cancelText }}

        </button>
    @endif

    <x-crud.button-action type="submit" :color="$submitColor" :icon="$submitIcon" :text="$submit" loading />

</div>
