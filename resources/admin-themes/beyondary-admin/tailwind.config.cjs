const path = require("path");

const adminPackageViews = path.join(
    __dirname,
    "../../../packages/Webkul/Admin/src/Resources"
);

const beyondaryStorefrontViews = path.join(
    __dirname,
    "../../../packages/Beyondary/Storefront/resources/views"
);

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./views/**/*.blade.php",
        "./assets/**/*.js",
        path.join(adminPackageViews, "**/*.blade.php"),
        path.join(adminPackageViews, "**/*.js"),
        path.join(beyondaryStorefrontViews, "**/*.blade.php"),
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
                sans: ["var(--admin-font-sans)", "ui-sans-serif", "system-ui", "sans-serif"],
                display: ["var(--admin-font-display)", "ui-serif", "Georgia", "serif"],
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
        "admin-theme-option",
        "admin-theme-option--active",
        "admin-theme-settings-grid",
        "admin-theme-color-input",
        "accent-admin-primary",
        "sf-page-map",
        "sf-page-map__chrome",
        "sf-page-map__viewport",
        "sf-page-map__block",
        "sf-page-map__block--active",
        "sf-page-map__thumb",
        "sf-page-map__label",
        "sf-section-preview",
        "sf-section-preview--compact",
        "sf-section-card",
        "sf-section-card__preview",
        "sf-section-card__body",
        "sf-section-card__order",
        "sf-section-card__badge",
        "sf-section-card__badge--active",
        "sf-section-card__badge--missing",
        "sf-section-card__action",
        "sf-zone-label",
        "line-clamp-2",
    ],
};
