import { initCrud } from '../crud';

document.addEventListener('DOMContentLoaded', () => {
    initCrud({
        entity: 'users',
        createForm: 'createForm',
        editForm: 'editForm',
        deleteForm: 'deleteForm',
        activeForm: 'activeForm',
        createModal: 'createModal',
        editModal: 'editModal',
        deleteModal: 'deleteModal',
        activeModal: 'activeModal',
        resultsContainer: 'usersResults',
        resultsUrl: '/users/cards',
        browserUrl: '/users',
        searchInput: '[data-crud-filter]',
        paginationContainer: 'usersResults',
    });

    ['create', 'edit'].forEach(initUserForm);
});

function initUserForm(prefix) {
    const modal = document.getElementById(`${prefix}Modal`);
    const form = document.getElementById(`${prefix}Form`);

    if (!modal || !form) return;

    const setCardState = checkbox => {
        checkbox.closest('.selectable-card')
            ?.classList.toggle('border-primary', checkbox.checked);
    };

    const syncSelectAll = target => {
        const checkboxes = form.querySelectorAll(`.${target}-checkbox`);
        const selectAll = form.querySelector(`.select-all[data-target="${target}"]`);

        if (selectAll) {
            selectAll.checked = checkboxes.length > 0
                && [...checkboxes].every(checkbox => checkbox.checked);
        }
    };

    const selectValues = (target, values) => {
        const selected = new Set(values.map(String));

        form.querySelectorAll(`.${target}-checkbox`).forEach(checkbox => {
            checkbox.checked = selected.has(checkbox.value);
            setCardState(checkbox);
        });

        syncSelectAll(target);
    };

    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;

        if (prefix === 'edit' && button) {
            selectValues('roles', JSON.parse(button.dataset.roles ?? '[]'));
            selectValues('areas', JSON.parse(button.dataset.areas ?? '[]'));
        }
    });

    form.addEventListener('change', event => {
        if (event.target.matches('.roles-checkbox, .areas-checkbox')) {
            setCardState(event.target);
            syncSelectAll(event.target.classList.contains('roles-checkbox') ? 'roles' : 'areas');
        }

        if (event.target.matches('.select-all')) {
            const target = event.target.dataset.target;

            form.querySelectorAll(`.${target}-checkbox`).forEach(checkbox => {
                checkbox.checked = event.target.checked;
                setCardState(checkbox);
            });
        }
    });

    form.addEventListener('click', event => {
        const button = event.target.closest('.toggle-password');

        if (!button) return;

        const input = document.getElementById(button.dataset.target);
        const icon = button.querySelector('i');

        if (!input) return;

        input.type = input.type === 'password' ? 'text' : 'password';
        icon?.classList.toggle('ti-eye', input.type === 'password');
        icon?.classList.toggle('ti-eye-off', input.type === 'text');
    });
}
