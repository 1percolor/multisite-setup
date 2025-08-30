import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'wp/wp-content/plugins/acme-ms-plugin/build',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        admin: 'resources/js/admin.ts'
      }
    }
  }
});
