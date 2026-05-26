export function showToast(message, type = 'success') {

    const toast =
        document.getElementById('globalToast');

    const body =
        document.getElementById('globalToastBody');

    if (!toast || !body) return;

    toast.classList.remove(
        'bg-success',
        'bg-danger',
        'text-white'
    );

    if (type === 'success') {

        toast.classList.add(
            'bg-success',
            'text-white'
        );

    } else {

        toast.classList.add(
            'bg-danger',
            'text-white'
        );
    }

    body.innerHTML = message;

    const bsToast = new bootstrap.Toast(
        toast,
        {
            delay: 5000,
            autohide: true
        }
    );

    bsToast.show();
}