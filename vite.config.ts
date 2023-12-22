import { defineConfig } from 'laravel-vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    build: {
        target: 'esnext',
        rollupOptions: {
                output:{
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            return id.toString().split('node_modules/')[1].split('/')[0].toString();
                        }
                    }
                }
            } // you can also use 'es2020' here
      },
    optimizeDeps: {
        esbuildOptions: {
            target: 'esnext', // you can also use 'es2020' here
        },
    },
    server: {
        hmr: {
            host: 'localhost',
            overlay: false
        },
        watch: {
            ignored: ['**/.env/**'],
        },
    },
    resolve: {
        alias: {
            "vue-i18n": "vue-i18n/dist/vue-i18n.cjs.js"
        }
    }
}).withPlugins(
    vue
)
