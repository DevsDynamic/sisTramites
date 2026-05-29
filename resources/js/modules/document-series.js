// import { ajaxFormSubmit } from '../core/ajax';
// import { resetModal } from '../core/modal';

// document.addEventListener(
//     'DOMContentLoaded',
//     function () {

//         /* RESET */
//         resetModal(
//             'createModal',
//             'createForm'
//         );

//         /* CREATE */
//         const createForm =
//             document.getElementById(
//                 'createForm'
//             );

//         if (createForm) {

//             createForm.addEventListener(
//                 'submit',
//                 async function (e) {

//                     e.preventDefault();

//                     ajaxFormSubmit(
//                         createForm,
//                         {
//                             modalId: 'createModal'
//                         }
//                     );
//                 }
//             );
//         }

//         /* EDIT */
//         const editForm =
//             document.getElementById(
//                 'editForm'
//             );

//         if (editForm) {

//             editForm.addEventListener(
//                 'submit',
//                 async function (e) {

//                     e.preventDefault();

//                     ajaxFormSubmit(
//                         editForm,
//                         {
//                             modalId: 'editModal'
//                         }
//                     );
//                 }
//             );
//         }

//         /* DELETE */
//         const deleteForm =
//             document.getElementById(
//                 'deleteForm'
//             );

//         if (deleteForm) {

//             deleteForm.addEventListener(
//                 'submit',
//                 async function (e) {

//                     e.preventDefault();

//                     ajaxFormSubmit(
//                         deleteForm,
//                         {
//                             modalId: 'deleteModal'
//                         }
//                     );
//                 }
//             );
//         }
//     }
// );


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