import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { resolve } from 'node:path';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.tsx', 'resources/js/app.js'],
      ssr: 'resources/js/ssr.tsx',
      refresh: true,
    }),
    react(),
  ],
  resolve: {
    alias: {
      'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
    },
  },
  esbuild: {
    jsx: 'automatic',
  },
});
