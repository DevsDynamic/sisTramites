import { initCrud } from '../crud';

document.addEventListener('DOMContentLoaded', () => {
    initCrud({
        entity: 'roles',
        createForm: 'createForm',
        editForm: 'editForm',
        deleteForm: 'deleteForm',
        createModal: 'createModal',
        editModal: 'editModal',
        deleteModal: 'deleteModal',
        resultsContainer: 'rolesResults',
        resultsUrl: '/roles/cards',
        browserUrl: '/roles',
        searchInput: '[data-crud-filter]',
        paginationContainer: 'rolesResults',
    });

    ['create', 'edit'].forEach(initRoleForm);
});

function initRoleForm(prefix) {
    const modal = document.getElementById(`${prefix}Modal`);
    const form = document.getElementById(`${prefix}Form`);

    if (!modal || !form) return;

    const moduleCheckboxes = () => form.querySelectorAll('.module-checkbox');
    const permissionCheckboxes = () => form.querySelectorAll('.permission-checkbox');

    const syncModule = module => {
        const permissions = form.querySelectorAll(`.permission-module-${module}`);
        const moduleCheckbox = form.querySelector(`.module-checkbox[data-module="${module}"]`);

        if (moduleCheckbox) {
            moduleCheckbox.checked = permissions.length > 0
                && [...permissions].every(permission => permission.checked);
        }
    };

    const syncAll = () => {
        const selectAll = form.querySelector('.select-all-permissions');
        const permissions = permissionCheckboxes();

        if (selectAll) {
            selectAll.checked = permissions.length > 0
                && [...permissions].every(permission => permission.checked);
        }
    };

    const selectPermissions = permissions => {
        const selected = new Set(permissions);

        permissionCheckboxes().forEach(permission => {
            permission.checked = selected.has(permission.value);
        });

        moduleCheckboxes().forEach(module => syncModule(module.dataset.module));
        syncAll();
    };

    modal.addEventListener('show.bs.modal', event => {
        if (prefix === 'edit' && event.relatedTarget) {
            selectPermissions(JSON.parse(event.relatedTarget.dataset.permissions ?? '[]'));
        }
    });

    form.addEventListener('change', event => {
        if (event.target.matches('.select-all-permissions')) {
            permissionCheckboxes().forEach(permission => {
                permission.checked = event.target.checked;
            });

            moduleCheckboxes().forEach(module => {
                module.checked = event.target.checked;
            });
        }

        if (event.target.matches('.module-checkbox')) {
            form.querySelectorAll(`.permission-module-${event.target.dataset.module}`).forEach(permission => {
                permission.checked = event.target.checked;
            });

            syncAll();
        }

        if (event.target.matches('.permission-checkbox')) {
            const module = [...event.target.classList]
                .find(className => className.startsWith('permission-module-'))
                ?.replace('permission-module-', '');

            if (module) syncModule(module);
            syncAll();
        }
    });
}
