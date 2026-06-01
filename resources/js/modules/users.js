import { ajaxFormSubmit } from '../core/ajax';

document.addEventListener('DOMContentLoaded', () => {

    const createForm =
        document.getElementById('createForm');

    if (!createForm) {
        return;
    }

    console.log('Módulo Usuarios cargado');

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

        document.getElementById('editForm').action =
            `/users/${btn.dataset.id}`;

        document.getElementById('edit_name').value =
            btn.dataset.name ?? '';

        document.getElementById('edit_email').value =
            btn.dataset.email ?? '';

        document.getElementById('edit_password').value =
            '';

        /*
        |--------------------------------------------------------------------------
        | RESET CHECKBOXES
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '#editForm input[type="checkbox"]'
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
        | ROLES
        |--------------------------------------------------------------------------
        */

        const roles =
            JSON.parse(
                btn.dataset.roles ?? '[]'
            );

        roles.forEach(role => {

            const checkbox =
                document.querySelector(
                    `#editForm input[name="roles[]"][value="${role}"]`
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

        /*
        |--------------------------------------------------------------------------
        | AREAS
        |--------------------------------------------------------------------------
        */

        const areas =
            JSON.parse(
                btn.dataset.areas ?? '[]'
            );

        areas.forEach(area => {

            const checkbox =
                document.querySelector(
                    `#editForm input[name="areas[]"][value="${area}"]`
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

        document.getElementById('deleteForm').action =
            `/users/${btn.dataset.id}`;
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
    | TOGGLE PASSWORD
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', (e) => {

        const btn =
            e.target.closest('.toggle-password');

        if (!btn) {
            return;
        }

        const target =
            document.getElementById(
                btn.dataset.target
            );

        if (!target) {
            return;
        }

        const icon =
            btn.querySelector('i');

        if (target.type === 'password') {

            target.type = 'text';

            icon?.classList.remove('ti-eye');
            icon?.classList.add('ti-eye-off');

        } else {

            target.type = 'password';

            icon?.classList.remove('ti-eye-off');
            icon?.classList.add('ti-eye');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | SELECT ALL
    |--------------------------------------------------------------------------
    */

    document.addEventListener('change', (e) => {

        const selectAll =
            e.target.closest('.select-all');

        if (!selectAll) {
            return;
        }

        const target =
            selectAll.dataset.target;

        document
            .querySelectorAll(
                `.${target}-checkbox`
            )
            .forEach(checkbox => {

                checkbox.checked =
                    selectAll.checked;

                toggleCard(checkbox);
            });
    });

    /*
    |--------------------------------------------------------------------------
    | INDIVIDUAL CHECKBOXES
    |--------------------------------------------------------------------------
    */

    document.addEventListener('change', (e) => {

        if (
            !e.target.matches(
                '.roles-checkbox, .areas-checkbox'
            )
        ) {
            return;
        }

        toggleCard(e.target);
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

                createForm
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
            }
        );
});

/*
|--------------------------------------------------------------------------
| CARD STYLE
|--------------------------------------------------------------------------
*/

function toggleCard(checkbox)
{
    const card =
        checkbox.closest('.card');

    if (!card) {
        return;
    }

    card.classList.toggle(
        'selected-card',
        checkbox.checked
    );
}

/*
|--------------------------------------------------------------------------
| REFRESH
|--------------------------------------------------------------------------
*/

async function refreshEntidad()
{
    const response =
        await axios.get('/users/cards');

    document
        .getElementById('usersContainer')
        .outerHTML =
        response.data;
}