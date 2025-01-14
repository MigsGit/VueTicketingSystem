import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite';
import { resolve } from 'path';
import { copyFileSync } from 'fs';
import { copy } from 'vite-plugin-copy'; // Import the copy plugin

    export default defineConfig({

    build: {
        manifest: true, // Enable manifest generation
        // manifest: 'assets.json', // Customize the manifest filename...
        outDir: 'build', // Output all build files to the 'build' folder outside public
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[name].[hash].[ext]', // Place assets in 'build/assets'
            },
        },
    },
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
            // hotFile: 'storage/vite.hot', // Customize the "hot" file...
            // buildDirectory: 'bundle', // Customize the build directory...
            // refresh: true,
        }),
        copy({
            targets: [
                {
                src: resolve(__dirname, 'public/assets/**/*'), // Source folder
                dest: resolve(__dirname, 'build/assets') // Destination folder
                }
            ]
        })
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },

});
