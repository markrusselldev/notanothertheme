import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  root: 'src',
  build: {
    outDir: '../dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        theme: path.resolve(__dirname, 'src/index.js')
      },
      output: {
        entryFileNames: 'theme.js',
        assetFileNames: 'theme.css'
      }
    }
  }
});
