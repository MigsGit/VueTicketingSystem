import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';
import { copyFileSync } from 'fs';
import laravel from 'vite-plugin-laravel';

export default defineConfig({
  plugins: [
    vue(), // Vue plugin for handling .vue files
    laravel({
      // Configure Laravel plugin to handle Laravel input resources
      input: [
        'resources/js/app.js', // Main entry for Laravel's JS
        'resources/css/app.css', // Main entry for Laravel's CSS
      ],
      refresh: true, // Enable hot reloading
    })
  ],
  build: {
    manifest: true, // Enable manifest generation
    outDir: 'build', // Build files go to 'build' outside the public folder
    rollupOptions: {
      output: {
        assetFileNames: 'assets/[name].[hash].[ext]', // Assets go into 'build/assets'
      },
    },
    // Copy manifest.json from build folder to public folder after build
    writeBundle() {
      const manifestPath = resolve(__dirname, 'build', 'manifest.json');
      const publicManifestPath = resolve(__dirname, 'public', 'manifest.json');

      // Copy the generated manifest.json file to the public folder
      copyFileSync(manifestPath, publicManifestPath);

      console.log('Manifest copied to public folder.');
    }
  },
  publicDir: 'public', // The directory for static assets (public folder in Laravel)
});
