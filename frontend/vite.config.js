import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      'vue': 'vue/dist/vue.esm-bundler.js', // BUILDS TEMPLATES AT RUNTIME
      '@views': fileURLToPath(new URL('./src/views', import.meta.url)),
      '@components': fileURLToPath(new URL('./src/components', import.meta.url)),
      '@services': fileURLToPath(new URL('./src/services', import.meta.url)),
      '@stores': fileURLToPath(new URL('./src/stores', import.meta.url)),
      '@utils': fileURLToPath(new URL('./src/utils', import.meta.url)),
      '@config': fileURLToPath(new URL('./src/config', import.meta.url)),
    },
  },
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false
      },
      '/auth': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/auth/, '/auth')
      },
      '/items': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path
      },
      '/chat': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path
      },
      '/notifications': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path
      },
      '/user': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path
      }
    }
  }
})