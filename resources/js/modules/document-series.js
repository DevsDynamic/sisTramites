import { initCrud } from '../crud';

document.addEventListener('DOMContentLoaded', () => {

    initCrud({

        entity: 'document-series',

        createForm: 'createForm',
        editForm: 'editForm',
        deleteForm: 'deleteForm',
        statusForm: 'statusForm',
        activeForm: 'activeForm',

        createModal: 'createModal',
        editModal: 'editModal',
        deleteModal: 'deleteModal',
        statusModal: 'statusModal',
        activeModal: 'activeModal',

        resultsContainer: 'documentSeriesResults',
        resultsUrl: '/document-series/cards',
        browserUrl: '/document-series',
        searchInput: '[data-crud-filter]',
        paginationContainer: 'documentSeriesResults',

    });

    function initSeriesPreview(prefix) {
        const modal = document.getElementById(`${prefix}Modal`);
        const prefixInput = document.getElementById(`${prefix}_prefix`);
        const currentInput = document.getElementById(`${prefix}_current_number`);
        const paddingInput = document.getElementById(`${prefix}_padding`);
        const preview = document.getElementById(`${prefix}_seriesPreview`);

        if (!prefixInput || !currentInput || !paddingInput || !preview) {
            return;
        }

        const updatePreview = () => {
            const seriesPrefix = prefixInput.value.trim() || 'SER';
            const current = Number(currentInput.value || 0);
            const padding = Number(paddingInput.value || 6);

            preview.textContent =
                `${seriesPrefix}-${String(current + 1).padStart(padding, '0')}`;
        };

        [prefixInput, currentInput, paddingInput].forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        modal?.addEventListener('show.bs.modal', updatePreview);

        updatePreview();
    }

    initSeriesPreview('create');
    initSeriesPreview('edit');
});