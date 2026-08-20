// import { refreshContainer } from '../core/refresh';

// export function initPagination(config) {

//     const results =
//         document.getElementById(
//             config.resultsContainer
//         );

//     if (!results) return;


//     results.addEventListener(
//         'click',
//         async e => {

//             const link =
//                 e.target.closest(
//                     '.pagination a'
//                 );

//             if (!link) return;

//             e.preventDefault();


//             const url =
//                 new URL(
//                     link.href,
//                     window.location.origin
//                 );


//             const controls =
//                 document.querySelectorAll(
//                     '[data-crud-filter]'
//                 );


//             controls.forEach(control => {

//                 if (!control.name) return;

//                 if (
//                     control.type === 'checkbox' &&
//                     !control.checked
//                 ) {
//                     return;
//                 }

//                 if (control.value === '') {

//                     url.searchParams.delete(
//                         control.name
//                     );

//                     return;
//                 }

//                 url.searchParams.set(
//                     control.name,
//                     control.value
//                 );

//             });


//             await refreshContainer(
//                 url.toString(),
//                 config.resultsContainer
//             );


//             window.history.replaceState(
//                 {},
//                 '',
//                 url.toString()
//             );

//         }
//     );

// }

import { refreshContainer } from '../core/refresh';

export function initPagination(config) {

    const container =
        document.getElementById(
            config.resultsContainer
        );

    if (!container) return;

    const links =
        container.querySelectorAll(
            '.crud-pagination a'
        );

    links.forEach(link => {

        link.addEventListener(
            'click',
            async (event) => {

                event.preventDefault();

                const pageUrl =
                    new URL(
                        link.href,
                        window.location.origin
                    );

                const resultsUrl =
                    new URL(
                        config.resultsUrl,
                        window.location.origin
                    );

                resultsUrl.search =
                    pageUrl.search;

                await refreshContainer(
                    resultsUrl.toString(),
                    config.resultsContainer
                );

                // URL visible del navegador
                // window.history.pushState(
                //     {},
                //     '',
                //     pageUrl.toString()
                // );
                const browserUrl = new URL(
                    config.browserUrl,
                    window.location.origin
                );

                browserUrl.search = pageUrl.search;

                window.history.pushState(
                    {},
                    '',
                    browserUrl.toString()
                );

                // Volver a registrar eventos
                initPagination(config);
            }
        );

    });
}