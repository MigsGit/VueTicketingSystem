import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path';
export default defineConfig({
    // remove hash in public/asset/js & css
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: ['resources/css/app.css',
            'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // rollupOptions: {
            // Define a custom output directory (e.g., '../build' outside the 'public' folder)
            outDir: resolve(__dirname, './build'),
            emptyOutDir: true, // Clears the directory on each build
            assetsDir: 'assets', // Customize the assets folder name if needed
            manifestPath: 'assets/manifest.json', // Custom path for manifest
            manifest: true, // Enables manifest.json generation (for Laravel to reference)
            rollupOptions: {
                output: {
                  assetFileNames: 'assets/[name].[hash].[ext]',
                },
            },
        // }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
