const shopConfig = require('../../../packages/Webkul/Shop/tailwind.config.js');

/** @type {import('tailwindcss').Config} */
module.exports = {
    ...shopConfig,
    content: [
        './assets/**/*.js',
        './views/**/*.blade.php',
        '../../../packages/Webkul/Shop/src/Resources/**/*.blade.php',
        '../../../packages/Webkul/Shop/src/Resources/**/*.js',
    ],
    theme: {
        ...shopConfig.theme,
        extend: {
            ...shopConfig.theme.extend,
            colors: {
                ...shopConfig.theme.extend.colors,
                brand: {
                    gold: '#B88B54',
                    dark: '#3A2618',
                    earth: '#6B4A31',
                    light: '#F8F6F0',
                },
            },
            fontFamily: {
                sans: ['Prompt', 'sans-serif'],
                serif: ['Playfair Display', 'serif'],
                ...shopConfig.theme.extend.fontFamily,
            },
        },
    },
};
