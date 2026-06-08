const path = require("path");

const adminPackageViews = path.join(
    __dirname,
    "../../../packages/Webkul/Admin/src/Resources"
);

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./views/**/*.blade.php",
        "./assets/**/*.js",
        path.join(adminPackageViews, "**/*.blade.php"),
        path.join(adminPackageViews, "**/*.js"),
    ],

    theme: {
        container: {
            center: true,
            screens: {
                "2xl": "1920px",
            },
            padding: {
                DEFAULT: "16px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1920px",
        },

        extend: {
            colors: {
                darkGreen: "#40994A",
                darkBlue: "#0044F2",
                darkPink: "#F85156",
                "admin-sidebar": "#2E2720",
                "admin-sidebar-hover": "#3A3229",
                "admin-sidebar-active": "#B88B54",
                "admin-sidebar-text": "#E8E2D9",
                "admin-sidebar-muted": "#A89B8C",
                "admin-sidebar-submenu": "#231F1A",
                "admin-sidebar-submenu-text": "#C9BFB0",
                "admin-surface": "#F8F6F0",
                "admin-card": "#FFFFFF",
                "admin-border": "#E5DFD4",
                "admin-text": "#3A2618",
                "admin-muted": "#6B4A31",
                "admin-primary": "#B88B54",
                "admin-primary-hover": "#9A7345",
            },

            fontFamily: {
                sans: ["Prompt", "ui-sans-serif", "system-ui", "sans-serif"],
                display: ["Playfair Display", "ui-serif", "Georgia", "serif"],
                icon: ["bagisto-admin"],
            },
        },
    },

    darkMode: "class",

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        },
        {
            pattern: /text-(blue|gray)-/,
        },
        "focus:ring-2",
        "focus:ring-admin-primary/30",
        "focus-within:ring-2",
        "focus-within:ring-admin-primary/30",
    ],
};
