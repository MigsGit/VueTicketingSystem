import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path';
import { copyFileSync } from 'fs';
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
        manifest: true, // Enable manifest generation
        outDir: 'dist', // Output build directory (can be customized as needed)
        rollupOptions: {
          output: {
            // Customize asset paths, if necessary
          },
        },
        // Hook to copy manifest to public folder after build
        writeBundle() {
          // Copy the generated manifest.json to the public folder
          const manifestPath = resolve(__dirname, 'dist', 'manifest.json');
          const publicManifestPath = resolve(__dirname, 'public', 'manifest.json');
          copyFileSync(manifestPath, publicManifestPath);
        },
      },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
