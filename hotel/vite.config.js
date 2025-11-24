import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                // ➕ Tus CSS reales que usas en @vite()
                'resources/css/estilo.css',
                'resources/css/estilo2.css',
                'resources/css/gerente.css',
                'resources/css/recepcionista.css',
                'resources/css/huesped.css',
            ],
            refresh: true,
        }),
    ],
});
