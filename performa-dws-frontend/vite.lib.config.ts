import { fileURLToPath, URL } from 'node:url'

import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'
// import dts from 'vite-plugin-dts'
import EnvironmentPlugin from 'vite-plugin-environment'

import ProjectConst from './src/const'

export default defineConfig({
  plugins: [
    vue(),
    tailwindcss(),
    EnvironmentPlugin('all')
    // dts({ rollupTypes: false, tsconfigPath: './tsconfig.lib.json' })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    lib: {
      entry: './src/index.ts',
      name: ProjectConst.name,
      fileName: (format) => `${ProjectConst.slug}.${format}.js`
    },
    rollupOptions: {
      external: [
        'axios',
        'flowbite',
        'pinia',
        'tailwindcss',
        'uuid',
        'vee-validate',
        'vue',
        'vue-router',
        'yup'
      ],
      output: {
        globals: {
          axios: 'axios',
          flowbite: 'flowbite',
          pinia: 'Pinia',
          tailwindcss: 'tailwindcss',
          uuid: 'uuid',
          'vee-validate': 'VeeValidate',
          vue: 'Vue',
          'vue-router': 'VueRouter',
          yup: 'yup'
        }
      }
    },
    outDir: 'dist/lib'
  }
})
