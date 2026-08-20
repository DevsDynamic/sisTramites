export function showLoading(form) {

    const button = form.querySelector('[type="submit"]');

    if (!button) return;

    button.disabled = true;

    button.querySelector('.btn-content')
        ?.classList.add('d-none');

    button.querySelector('.btn-loading')
        ?.classList.remove('d-none');
}

export function restoreLoading(form) {

    const button = form.querySelector('[type="submit"]');

    if (!button) return;

    button.disabled = false;

    button.querySelector('.btn-content')
        ?.classList.remove('d-none');

    button.querySelector('.btn-loading')
        ?.classList.add('d-none');
}