import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/listrindojaya.css',
                'resources/css/components/product-detail.css',
                'resources/css/components/cart.css',
                'resources/js/app.js',
                'resources/js/cart.js',
                'resources/js/admin.js',
                'resources/js/ui-admin.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
