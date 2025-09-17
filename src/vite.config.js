// vite サーバ情報ファイル

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],

    // 下記設定を追加しないと、vite サーバにアクセスできず、Vite 側の内容が描画されない
    server: {
        host: '0.0.0.0', // Docker コンテナ内でアクセス可能
        port: 5173,
        // hot reloading 用の設定
        hmr: {
            host: 'localhost',
            port: 5173,
        },
    },
});
