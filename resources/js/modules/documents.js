import { ajaxFormSubmit } from '../core/ajax';

function initCreateForm() {
    const createForm = document.getElementById('createForm');
    if (!createForm) return;

    // Clonar el elemento para eliminar TODOS los listeners previos
    const cleanForm = createForm.cloneNode(true);
    createForm.parentNode.replaceChild(cleanForm, createForm);

    cleanForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = cleanForm.querySelector('#submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

        ajaxFormSubmit(this, {
            reload:   false,
            redirect: true,
        }).finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-device-floppy"></i> Guardar documento';
        });
    });
}

// Ejecutar solo una vez
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCreateForm, { once: true });
} else {
    initCreateForm();
}