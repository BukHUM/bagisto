import Flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

const LOCALE_LOADERS = {
    es: () => import("flatpickr/dist/l10n/es.js").then((m) => m.Spanish),
    ca: () => import("flatpickr/dist/l10n/cat.js").then((m) => m.Catalan),
    ar: () => import("flatpickr/dist/l10n/ar.js").then((m) => m.Arabic),
    fa: () => import("flatpickr/dist/l10n/fa.js").then((m) => m.Persian),
    tr: () => import("flatpickr/dist/l10n/tr.js").then((m) => m.Turkish),
    bn: () => import("flatpickr/dist/l10n/bn.js").then((m) => m.Bengali),
    de: () => import("flatpickr/dist/l10n/de.js").then((m) => m.German),
    fr: () => import("flatpickr/dist/l10n/fr.js").then((m) => m.French),
    he: () => import("flatpickr/dist/l10n/he.js").then((m) => m.Hebrew),
    hi: () => import("flatpickr/dist/l10n/hi.js").then((m) => m.Hindi),
    it: () => import("flatpickr/dist/l10n/it.js").then((m) => m.Italian),
    ja: () => import("flatpickr/dist/l10n/ja.js").then((m) => m.Japanese),
    nl: () => import("flatpickr/dist/l10n/nl.js").then((m) => m.Dutch),
    pl: () => import("flatpickr/dist/l10n/pl.js").then((m) => m.Polish),
    pt: () => import("flatpickr/dist/l10n/pt.js").then((m) => m.Portuguese),
    ru: () => import("flatpickr/dist/l10n/ru.js").then((m) => m.Russian),
    sin: () => import("flatpickr/dist/l10n/si.js").then((m) => m.Sinhala),
    uk: () => import("flatpickr/dist/l10n/uk.js").then((m) => m.Ukrainian),
    zh: () => import("flatpickr/dist/l10n/zh.js").then((m) => m.Chinese),
};

async function setLocaleFromLang() {
    const lang = document.documentElement.lang || "en";
    const loader = LOCALE_LOADERS[lang];

    if (! loader) {
        return;
    }

    const locale = await loader();

    window.Flatpickr.localize(locale);
}

export default {
    install: (app) => {
        window.Flatpickr = Flatpickr;

        setLocaleFromLang();

        const changeTheme = (theme) => {
            const existingTheme = document.getElementById("flatpickr");

            if (existingTheme) {
                existingTheme.remove();
            }

            if (theme === "light") {
                return;
            }

            const linkElement = document.createElement("link");
            linkElement.rel = "stylesheet";
            linkElement.type = "text/css";
            linkElement.href = `https://npmcdn.com/flatpickr/dist/themes/${theme}.css`;
            linkElement.id = "flatpickr";

            document.head.appendChild(linkElement);
        };

        const currentTheme = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";

        changeTheme(currentTheme);

        window.emitter.on("change-theme", (theme) => changeTheme(theme));
    },
};
