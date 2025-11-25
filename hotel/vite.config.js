import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',

                // Tus CSS
                'resources/css/estilo.css',
                'resources/css/estilo2.css',
                'resources/css/gerente.css',
                'resources/css/recepcionista.css',
                'resources/css/huesped.css',

                // JS
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
