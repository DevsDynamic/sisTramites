import { ajaxFormSubmit } from '../core/ajax';

document.addEventListener('DOMContentLoaded', () => {

    const createForm =
        document.getElementById('createForm');

    if (!createForm) {
        return;
    }

    console.log('Módulo Firmas cargado');

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    createForm.addEventListener('submit', async (e) => {

        e.preventDefault();

        await ajaxFormSubmit(
            createForm,
            {
                modalId: 'createModal',
                reload: false,
                redirect: false,
                callback: refreshEntidad
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | EDIT BUTTON
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', (e) => {

        const btn =
            e.target.closest('.edit-btn');

        if (!btn) {
            return;
        }

        document.getElementById('edit_user_id').value =
            btn.dataset.userId ?? '';

        document.getElementById('edit_type').value =
            btn.dataset.type ?? 'official';

        document.getElementById('editForm').action =
            `/signatures/${btn.dataset.id}`;

        toggleSignatureType(
            'edit',
            btn.dataset.type
        );
    });

    /*
    |--------------------------------------------------------------------------
    | EDIT SUBMIT
    |--------------------------------------------------------------------------
    */

    const editForm =
        document.getElementById('editForm');

    editForm?.addEventListener('submit', async (e) => {

        e.preventDefault();

        await ajaxFormSubmit(
            editForm,
            {
                modalId: 'editModal',
                reload: false,
                redirect: false,
                callback: refreshEntidad
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', (e) => {

        const btn =
            e.target.closest('.delete-btn');

        if (!btn) {
            return;
        }

        document.getElementById('deleteForm').action =
            `/signatures/${btn.dataset.id}`;
    });

    const deleteForm =
        document.getElementById('deleteForm');

    deleteForm?.addEventListener('submit', async (e) => {

        e.preventDefault();

        await ajaxFormSubmit(
            deleteForm,
            {
                modalId: 'deleteModal',
                reload: false,
                redirect: false,
                callback: refreshEntidad
            }
        );
    });

    /*
    |--------------------------------------------------------------------------
    | TYPE CHANGE
    |--------------------------------------------------------------------------
    */

    ['create', 'edit'].forEach(prefix => {

        document
            .getElementById(`${prefix}_type`)
            ?.addEventListener('change', function () {

                toggleSignatureType(
                    prefix,
                    this.value
                );
            });

        toggleSignatureType(
            prefix,
            document.getElementById(`${prefix}_type`)?.value
        );
    });

    /*
    |--------------------------------------------------------------------------
    | IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    ['create', 'edit'].forEach(prefix => {

        document
            .getElementById(`${prefix}_signature_image`)
            ?.addEventListener('change', function (e) {

                const file =
                    e.target.files[0];

                if (!file) {
                    return;
                }

                const reader =
                    new FileReader();

                reader.onload = function (ev) {

                    const preview =
                        document.getElementById(
                            `${prefix}_signaturePreview`
                        );

                    preview.src =
                        ev.target.result;

                    preview.classList.remove(
                        'd-none'
                    );
                };

                reader.readAsDataURL(file);
            });
    });

    /*
    |--------------------------------------------------------------------------
    | RESET CREATE
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('createModal')
        ?.addEventListener(
            'hidden.bs.modal',
            () => {

                createForm.reset();

                toggleSignatureType(
                    'create',
                    'official'
                );

                const preview =
                    document.getElementById(
                        'create_signaturePreview'
                    );

                if (preview) {

                    preview.src = '';

                    preview.classList.add(
                        'd-none'
                    );
                }
            }
        );
});

/*
|--------------------------------------------------------------------------
| TOGGLE TYPE
|--------------------------------------------------------------------------
*/

function toggleSignatureType(
    prefix,
    type
) {

    const official =
        document.getElementById(
            `${prefix}_officialFields`
        );

    const visual =
        document.getElementById(
            `${prefix}_visualFields`
        );

    if (!official || !visual) {
        return;
    }

    if (type === 'official') {

        official.style.display = '';

        visual.style.display = 'none';

    } else {

        official.style.display = 'none';

        visual.style.display = '';
    }
}

/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

async function refreshEntidad() {
    const response =
        await axios.get('/signatures/cards');

    document
        .getElementById('signaturesContainer')
        .outerHTML =
        response.data;
}