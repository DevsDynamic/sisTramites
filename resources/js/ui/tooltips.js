export function initTooltips()
{
    document
        .querySelectorAll(
            '[data-bs-toggle-tooltip="tooltip"]'
        )
        .forEach(el => {

            bootstrap.Tooltip
                .getOrCreateInstance(el);

        });

}

document.addEventListener(
    'DOMContentLoaded',
    initTooltips
);