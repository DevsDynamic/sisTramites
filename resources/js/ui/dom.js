export function setText(id, value) {
    const el = document.getElementById(id);

    if (!el) return;

    el.textContent = value ?? '';
}

export function setHtml(id, value) {
    const el = document.getElementById(id);

    if (!el) return;

    el.innerHTML = value ?? '';
}

export function setValue(id, value) {
    const el = document.getElementById(id);

    if (!el) return;

    el.value = value ?? '';
}

export function setChecked(id, value = true) {
    const el = document.getElementById(id);

    if (!el) return;

    el.checked = value;
}

export function setAction(formId, url) {
    const form = document.getElementById(formId);

    if (!form) return;

    form.action = url;
}

export function show(id) {
    const el = document.getElementById(id);

    if (!el) return;

    el.classList.remove('d-none');
}

export function hide(id) {
    const el = document.getElementById(id);

    if (!el) return;

    el.classList.add('d-none');
}

export function toggle(id) {
    const el = document.getElementById(id);

    if (!el) return;

    el.classList.toggle('d-none');
}

export function enable(id) {
    const el = document.getElementById(id);

    if (!el) return;

    el.disabled = false;
}

export function disable(id) {
    const el = document.getElementById(id);

    if (!el) return;

    el.disabled = true;
}