import { ajaxFormSubmit } from '../core/ajax';

document.addEventListener('DOMContentLoaded', () => {

    const createForm =
        document.getElementById('createForm');

    if (!createForm) {
        return;
    }

    console.log('Módulo Series Documentales cargado');

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

        document.getElementById(
            'edit_document_type_id'
        ).value =
            btn.dataset.document_type_id ?? '';

        document.getElementById(
            'edit_area_id'
        ).value =
            btn.dataset.area_id ?? '';

        document.getElementById(
            'edit_prefix'
        ).value =
            btn.dataset.prefix ?? '';

        document.getElementById(
            'edit_current_number'
        ).value =
            btn.dataset.current_number ?? '';

        document.getElementById(
            'edit_padding'
        ).value =
            btn.dataset.padding ?? '';

        document.getElementById(
            'edit_reset_yearly'
        ).checked =
            btn.dataset.reset_yearly == 1;

        document.getElementById(
            'edit_active'
        ).checked =
            btn.dataset.active == 1;

        document.getElementById(
            'editForm'
        ).action =
            `/document-series/${btn.dataset.id}`;
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
    | DELETE BUTTON
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', (e) => {

        const btn =
            e.target.closest('.delete-btn');

        if (!btn) {
            return;
        }

        document.getElementById('deleteForm').action =
            `/document-series/${btn.dataset.id}`;
    });

    /*
    |--------------------------------------------------------------------------
    | DELETE SUBMIT
    |--------------------------------------------------------------------------
    */

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
    | RESET CREATE
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('createModal')
        ?.addEventListener(
            'hidden.bs.modal',
            () => createForm.reset()
        );
});

/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

async function refreshEntidad() {
    const response =
        await axios.get('/document-series/cards');

    document
        .getElementById('documentSeriesContainer')
        .outerHTML =
        response.data;
}