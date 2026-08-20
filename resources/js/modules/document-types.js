import { initCrud } from '../crud';

document.addEventListener('DOMContentLoaded', () => {

    initCrud({

        entity: 'document-types',

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

        resultsContainer: 'documentTypesResults',
        resultsUrl: '/document-types/cards',
        browserUrl: '/document-types',
        searchInput: '[data-crud-filter]',
        paginationContainer: 'documentTypesResults',

    });

});