import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '0.0.0.0', // exposes Vite server to host machine
        hmr: {
            clientPort: 5173,
            host: 'localhost',
            protocol: 'ws'
        },
        port: 5173, // optional: specify port
        watch:{
            usePolling: true
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
