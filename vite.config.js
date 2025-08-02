import { defineConfig } from 'vite';
import FullReload from 'vite-plugin-full-reload';

export default defineConfig({
  plugins: [FullReload(['**/*.php', '**/*.json'])],
  base: '',
  build: {
    outDir: './assets',
    rollupOptions: {
      input: {
        main: './src/js/main.js',
        style: './src/css/style.css'
      },
      output: {
        assetFileNames: assetInfo => /\.(css)$/.test(assetInfo.name) ? 'css/[name][extname]' : '[ext]/[name][extname]',
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js'
      }
    }
  },
  css: { postcss: './postcss.config.js' }
});