import { initCrud } from '../crud';

document.addEventListener('DOMContentLoaded', () => {

    initCrud({

        entity: 'areas',

        createForm: 'createForm',
        editForm: 'editForm',
        deleteForm: 'deleteForm',
        activeForm: 'activeForm',

        createModal: 'createModal',
        editModal: 'editModal',
        deleteModal: 'deleteModal',
        activeModal: 'activeModal',

        resultsContainer: 'areasResults',
        resultsUrl: '/areas/cards',
        browserUrl: '/areas',
        searchInput: '[data-crud-filter]',
        paginationContainer: 'areasResults',

    });

});
