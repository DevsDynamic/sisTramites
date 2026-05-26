// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     server: {
//         host: '0.0.0.0',
//         hmr: {
//             host: 'cliente1.sistramites.com',
//         },
//     },

//     plugins: [
//         laravel({
//             input: [
//                 'resources/css/app.css',
//                 'resources/js/app.js'
//             ],
//             refresh: true,
//         }),
//     ],
// });

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({

    server: {

        host: '0.0.0.0',

        hmr: {
            host: 'localhost',
        },

        cors: true,
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});