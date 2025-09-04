import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/ocr.js',
                'resources/js/brands-live-search.js',
                'resources/js/categories-live-search.js',
                'resources/js/products-live-search.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    optimizeDeps: {
        include: ['tesseract.js', 'cropperjs']
    }
});
