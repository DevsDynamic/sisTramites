/*
|--------------------------------------------------------------------------
| Preview automático
|--------------------------------------------------------------------------
*/

export function initImagePreview() {
    document.addEventListener(
        'change',
        e => {

            const input =
                e.target.closest('[data-preview-target]');

            if (!input) {
                return;
            }

            const preview =
                document.getElementById(
                    input.dataset.previewTarget
                );

            showImagePreview(input, preview);

        }
    );
}

/*
|--------------------------------------------------------------------------
| Mostrar imagen
|--------------------------------------------------------------------------
*/

export function showImagePreview(input, preview) {
    if (
        !preview ||
        !input.files.length
    ) {
        return;
    }

    preview.src =
        URL.createObjectURL(
            input.files[0]
        );

    preview.classList.remove('d-none');
}

/*
|--------------------------------------------------------------------------
| Limpiar previews
|--------------------------------------------------------------------------
*/

export function clearPreview(form) {
    form
        .querySelectorAll('[data-preview]')
        .forEach(img => {

            img.src = '';

            img.classList.add('d-none');

        });
}