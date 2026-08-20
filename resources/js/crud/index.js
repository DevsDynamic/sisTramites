import { initCreate } from './create';
import { initEdit } from './edit';
import { initDelete } from './delete';
import { initActive } from './active';
import { initStatus } from './status';
import { refreshContainer } from '../core/refresh';
import { initSearch } from './search';
import { initPagination } from './pagination';

export function initCrud(config) {
    config.refresh = async () => {
        const resultsUrl = new URL(
            config.resultsUrl,
            window.location.origin
        );

        // Conserva búsqueda, filtros y página actuales.
        resultsUrl.search = window.location.search;

        await refreshContainer(
            resultsUrl.toString(),
            config.resultsContainer
        );

        // El contenido fue reemplazado: registrar nuevamente paginación.
        initPagination(config);
    };

    if (config.createForm) {
        initCreate(config);
    }

    if (config.editForm) {
        initEdit(config);
    }

    if (config.deleteForm) {
        initDelete(config);
    }

    if (config.statusForm) {
        initStatus(config);
    }

    if (config.activeForm) {
        initActive(config);
    }

    if (config.searchInput) {
        initSearch(config);
    }

    if (config.paginationContainer) {
        initPagination(config);
    }
}