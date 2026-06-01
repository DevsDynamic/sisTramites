import { ajaxFormSubmit } from '../core/ajax';

document.addEventListener('DOMContentLoaded', () => {

    const createForm =
        document.getElementById('createForm');

    if (!createForm) {
        return;
    }

    console.log('Módulo Roles cargado');

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
    | RESET CREATE
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('createModal')
        ?.addEventListener('show.bs.modal', () => {

            createForm.reset();

            createForm
                .querySelectorAll(
                    'input[type="checkbox"]'
                )
                .forEach(el => {

                    el.checked = false;
                });
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

        const editForm =
            document.getElementById('editForm');

        editForm.action =
            `/roles/${btn.dataset.id}`;

        document.getElementById('edit_name')
            .value = btn.dataset.name;

        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        editForm
            .querySelectorAll(
                'input[type="checkbox"]'
            )
            .forEach(el => {

                el.checked = false;

                el.closest('.card')
                    ?.classList.remove(
                        'selected-card'
                    );
            });

        /*
        |--------------------------------------------------------------------------
        | MARK PERMISSIONS
        |--------------------------------------------------------------------------
        */

        const permissions =
            JSON.parse(
                btn.dataset.permissions ?? '[]'
            );

        permissions.forEach(permission => {

            const checkbox =
                editForm.querySelector(
                    `input[value="${permission}"]`
                );

            if (!checkbox) {
                return;
            }

            checkbox.checked = true;

            checkbox.closest('.card')
                ?.classList.add(
                    'selected-card'
                );
        });
    });

    /*
    |--------------------------------------------------------------------------
    | EDIT SUBMIT
    |--------------------------------------------------------------------------
    */

    const editForm =
        document.getElementById('editForm');

    editForm?.addEventListener(
        'submit',
        async (e) => {

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
        }
    );

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

        document
            .getElementById('deleteForm')
            .action =
            `/roles/${btn.dataset.id}`;
    });

    /*
    |--------------------------------------------------------------------------
    | DELETE SUBMIT
    |--------------------------------------------------------------------------
    */

    const deleteForm =
        document.getElementById('deleteForm');

    deleteForm?.addEventListener(
        'submit',
        async (e) => {

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
        }
    );

    /*
    |--------------------------------------------------------------------------
    | SELECT ALL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'change',
        (e) => {

            if (
                !e.target.classList.contains(
                    'select-all-permissions'
                )
            ) {
                return;
            }

            const checked =
                e.target.checked;

            document
                .querySelectorAll(
                    '.permission-checkbox'
                )
                .forEach(permission => {

                    permission.checked =
                        checked;
                });

            document
                .querySelectorAll(
                    '.module-checkbox'
                )
                .forEach(module => {

                    module.checked =
                        checked;
                });
        }
    );

    /*
    |--------------------------------------------------------------------------
    | MODULE CHECKBOX
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'change',
        (e) => {

            if (
                !e.target.classList.contains(
                    'module-checkbox'
                )
            ) {
                return;
            }

            const module =
                e.target.dataset.module;

            document
                .querySelectorAll(
                    `.permission-module-${module}`
                )
                .forEach(permission => {

                    permission.checked =
                        e.target.checked;
                });
        }
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
        await axios.get('/roles/cards');

    document
        .getElementById('rolesContainer')
        .outerHTML =
        response.data;
}
