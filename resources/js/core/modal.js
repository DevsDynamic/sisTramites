export function resetModal(
    modalId,
    formId
) {

    const modal =
        document.getElementById(modalId);

    const form =
        document.getElementById(formId);

    if (!modal || !form) return;

    modal.addEventListener(
        'hidden.bs.modal',
        function () {

            form.reset();
        }
    );
}