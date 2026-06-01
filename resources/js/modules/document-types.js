import { ajaxFormSubmit } from '../core/ajax';

document.addEventListener('DOMContentLoaded', () => {

    const createForm =
        document.getElementById('createForm');

    if (!createForm) {
        return;
    }

    console.log('Módulo Tipos de Documento cargado');

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

        document.getElementById('edit_name').value =
            btn.dataset.name ?? '';

        document.getElementById('edit_code').value =
            btn.dataset.code ?? '';

        document.getElementById('edit_active').checked =
            btn.dataset.active == 1;

        document.getElementById('editForm').action =
            `/document-types/${btn.dataset.id}`;
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
            `/document-types/${btn.dataset.id}`;
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

async function refreshEntidad()
{
    const response =
        await axios.get('/document-types/cards');

    document
        .getElementById('documentTypesContainer')
        .outerHTML =
        response.data;
}