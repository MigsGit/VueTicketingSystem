import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'
// console.log(resolve);
// console.log(resolve);
export default defineConfig({
    // remove hash in public/asset/js & css
    build: {
        rollupOptions: {
          output: {
            entryFileNames: `assets/[name].js`,
            chunkFileNames: `assets/[name].js`,
            assetFileNames: `assets/[name].[ext]`
          }
        },
        chunkSizeWarningLimit:2000,
    },
    server: {
        // hmr: {
        //     // host: 'rapidv/RapidV',
        // },
        origin: 'http://rapidv/vue-ticketing-system',
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
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
