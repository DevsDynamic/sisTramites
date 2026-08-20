const STORAGE_KEY = 'theme';

export function initTheme() {

    const html = document.documentElement;

    const savedTheme =
        localStorage.getItem(STORAGE_KEY) ?? 'light';

    html.dataset.theme = savedTheme;

    updateThemeButton();

}

export function toggleTheme() {

    const html = document.documentElement;

    const newTheme =
        html.dataset.theme === 'dark'
            ? 'light'
            : 'dark';

    html.dataset.theme = newTheme;

    localStorage.setItem(
        STORAGE_KEY,
        newTheme
    );

    updateThemeButton();

}

function updateThemeButton() {

    const button =
        document.getElementById('themeToggle');

    if (!button) return;

    const dark =
        document.documentElement.dataset.theme === 'dark';

    button.innerHTML = dark
        ? '<i class="ti ti-sun"></i>'
        : '<i class="ti ti-moon"></i>';

    button.title =
        dark
            ? 'Modo claro'
            : 'Modo oscuro';

}

document
    .getElementById('themeToggle')
    ?.addEventListener(
        'click',
        toggleTheme
    );