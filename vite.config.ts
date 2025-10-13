import { defineConfig } from 'vite';
import { isInWpContext, wpPath, config } from './context';
import { resolve } from 'node:path';

export default defineConfig(
  isInWpContext
    ? {
        build: {
          outDir: resolve(
            wpPath!,
            config?.vite?.build?.outDir || './web/app/mu-plugins/dist',
          ),
          emptyOutDir: true,
          modulePreload: false,
          rollupOptions: {
            input: 'src/index.css',
            output: {
              entryFileNames: `assets/[name].js`,
              chunkFileNames: `assets/[name].js`,
              assetFileNames: 'assets/[name][extname]',
            },
          },
        },
      }
    : {
        build: {
          modulePreload: false,
          sourcemap: true,
          rollupOptions: {
            input: ['index.html', 'admin.html', 'editor.html'],
            output: {
              entryFileNames: `assets/[name].js`,
              chunkFileNames: `assets/[name].js`,
              assetFileNames: 'assets/[name][extname]',
            },
          },
        },
      },
);
