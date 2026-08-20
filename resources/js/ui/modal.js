export function showModal(id) {
    bootstrap.Modal
        .getOrCreateInstance(
            document.getElementById(id)
        )
        .show();
}

export function hideModal(id) {
    bootstrap.Modal
        .getInstance(
            document.getElementById(id)
        )
        ?.hide();
}

export function toggleModal(id) {
    bootstrap.Modal
        .getOrCreateInstance(
            document.getElementById(id)
        )
        .toggle();
}