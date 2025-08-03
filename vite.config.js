
import { defineConfig } from 'vite';

export default defineConfig({
  root: 'src',
  build: {
    outDir: '../dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        theme: './index.js'
      },
      output: {
        entryFileNames: 'theme.js',
        assetFileNames: 'theme.css'
      }
    }
  }
});
