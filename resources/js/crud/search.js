import { refreshContainer } from '../core/refresh';
import { initPagination } from './pagination';

export function initSearch(config) {

    const results =
        document.getElementById(
            config.resultsContainer
        );

    if (!results) return;

    const controls =
        document.querySelectorAll(
            '[data-crud-filter]'
        );

    if (!controls.length) return;

    let timeout = null;

    const search = async () => {

        const params = new URLSearchParams();

        controls.forEach(control => {

            if (!control.name) return;

            if (
                control.type === 'checkbox' &&
                !control.checked
            ) {
                return;
            }

            if (control.value === '') {
                return;
            }

            params.set(
                control.name,
                control.value
            );
        });

        const resultsUrl = new URL(
            config.resultsUrl,
            window.location.origin
        );

        resultsUrl.search = params.toString();

        await refreshContainer(
            resultsUrl.toString(),
            config.resultsContainer
        );

        initPagination(config);

        const pageUrl = new URL(
            config.browserUrl,
            window.location.origin
        );

        pageUrl.search = params.toString();

        window.history.replaceState(
            {},
            '',
            pageUrl.toString()
        );
    };

    controls.forEach(control => {

        const event =
            control.type === 'search' ||
                control.type === 'text'
                ? 'input'
                : 'change';

        control.addEventListener(
            event,
            () => {

                clearTimeout(timeout);

                timeout = setTimeout(
                    search,
                    300
                );

            }
        );

    });

}
