import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({

    server: {

        host: true,

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

                'resources/js/modules/areas.js',
                'resources/js/modules/users.js',
                'resources/js/modules/roles.js',
                'resources/js/modules/document-series.js',
                'resources/js/modules/document-types.js',
                'resources/js/modules/documents.js',
                'resources/js/modules/signatures.js',
            ],
            refresh: true,
        }),
    ],
});