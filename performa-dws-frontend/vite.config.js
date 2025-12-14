import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')
  
  return {
    base: mode === 'production' 
      ? '/bssn/performa-dws-frontend/' 
      : '/',
    
    plugins: [vue()],
    
    resolve: {
      alias: {
        '@': path.resolve(__dirname, './src')
      }
    },
    
    server: {
      port: 3000,
      proxy: mode === 'development' ? {
        '/api': {
          target: env.VITE_API_URL || 'http://localhost:8000',
          changeOrigin: true,
          secure: false
        }
      } : undefined
    },
    
    build: {
      outDir: 'dist',
      assetsDir: 'assets',
      sourcemap: false,
      minify: 'esbuild', // Pakai esbuild
      target: 'es2015', // Browser compatibility
      rollupOptions: {
        output: {
          manualChunks: {
            'vendor': ['vue', 'vue-router'],
          }
        }
      }
    }
  }
})