export const CRUD_ACTIONS = {

    activate: {
        title: 'Activar registro',
        action: 'activar',
        buttonClass: 'btn-success',
        iconClass: 'ti ti-toggle-left text-success',
        buttonHtml: '<i class="ti ti-toggle-left me-1"></i> Activar',
    },

    deactivate: {
        title: 'Desactivar registro',
        action: 'desactivar',
        buttonClass: 'btn-danger',
        iconClass: 'ti ti-toggle-right text-danger',
        buttonHtml: '<i class="ti ti-toggle-right me-1"></i> Desactivar',
    },

    delete: {
        title: 'Eliminar registro',
        buttonClass: 'btn-danger',
        iconClass: 'ti ti-trash text-danger',
        buttonHtml: '<i class="ti ti-trash me-1"></i> Eliminar',
    },
};