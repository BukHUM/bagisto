import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(__dirname, "../../../");

export default defineConfig(({ mode }) => {
    Object.assign(process.env, loadEnv(mode, projectRoot));

    const themeModules = path.join(__dirname, "node_modules");

    return {
        resolve: {
            alias: {
                vue: path.join(themeModules, "vue"),
                "vee-validate": path.join(themeModules, "vee-validate"),
                "@vee-validate/rules": path.join(themeModules, "@vee-validate/rules"),
                "@vee-validate/i18n": path.join(themeModules, "@vee-validate/i18n"),
                axios: path.join(themeModules, "axios"),
                mitt: path.join(themeModules, "mitt"),
                flatpickr: path.join(themeModules, "flatpickr"),
                "vue-flatpickr": path.join(themeModules, "vue-flatpickr"),
            },
        },

        build: {
            emptyOutDir: true,
            minify: "esbuild",
            cssCodeSplit: true,
            rollupOptions: {
                output: {
                    manualChunks: {
                        vue: ["vue"],
                        veeValidate: ["vee-validate", "@vee-validate/rules", "@vee-validate/i18n"],
                        vendor: ["axios", "mitt"],
                    },
                },
            },
        },

        envDir: projectRoot,

        server: {
            host: process.env.VITE_HOST || "localhost",
            port: process.env.VITE_PORT || 5174,
            cors: true,
        },

        plugins: [
            vue(),

            laravel({
                hotFile: path.join(projectRoot, "public/shop-beyondary-vite.hot"),
                publicDirectory: path.join(projectRoot, "public"),
                buildDirectory: "themes/shop/beyondary/build",
                input: [
                    "assets/css/app.css",
                    "assets/js/app.js",
                ],
                refresh: true,
                preload: false,
            }),
        ],

        experimental: {
            renderBuiltUrl(filename, { hostType }) {
                if (hostType === "css") {
                    return path.basename(filename);
                }
            },
        },
    };
});
