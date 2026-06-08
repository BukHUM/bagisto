/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { setLocale } from "@vee-validate/i18n";
import { all } from "@vee-validate/rules";
import { initValidationLocales, loadValidationLocale } from "./vee-validate-locales";

window.defineRule = defineRule;

export default {
    install: (app) => {
        app.component("VForm", Form);
        app.component("VField", Field);
        app.component("VErrorMessage", ErrorMessage);

        initValidationLocales();

        window.addEventListener("load", async () => {
            const lang = document.documentElement.lang || "en";

            if (lang !== "en") {
                await loadValidationLocale(lang);
            }

            setLocale(lang);
        });

        Object.entries(all).forEach(([name, rule]) => {
            defineRule(name, (value, params, ctx) => {
                const processedValue = typeof value === "string" ? value.trim() : value;

                return rule(processedValue, params, ctx);
            });
        });

        defineRule("phone", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (! /^\+?\d+$/.test(trimmedValue)) {
                return false;
            }

            return true;
        });

        defineRule("address", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (
                !/^[a-zA-Z0-9\s.\/*'\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\u0590-\u05FF\u3040-\u309F\u30A0-\u30FF\u0400-\u04FF\u0D80-\u0DFF\u3400-\u4DBF\u2000-\u2A6D\u00C0-\u017F\u0980-\u09FF\u0900-\u097F\u4E00-\u9FFF,\(\)-]{1,60}$/iu.test(
                    trimmedValue
                )
            ) {
                return false;
            }

            return true;
        });

        defineRule("postcode", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (! /^[a-zA-Z0-9][a-zA-Z0-9\s-]*[a-zA-Z0-9]$/.test(trimmedValue)) {
                return false;
            }

            return true;
        });

        defineRule("decimal", (value, { decimals = "*", separator = "." } = {}) => {
            if (value === null || value === undefined || value === "") {
                return true;
            }

            const trimmedValue = value.trim();

            if (Number(decimals) === 0) {
                return /^-?\d*$/.test(trimmedValue);
            }

            const regexPart = decimals === "*" ? "+" : `{1,${decimals}}`;
            const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

            return regex.test(trimmedValue);
        });

        defineRule("date_format", (value, params) => {
            if (! value || ! value.length) {
                return true;
            }

            const format = Array.isArray(params) ? params[0] : params;

            if (format === "H:i") {
                return /^([01]?\d|2[0-3]):[0-5]\d$/.test(value.trim());
            }

            return true;
        });

        defineRule("required_if", (value, { condition = true } = {}) => {
            if (condition) {
                if (value === null || value === undefined || value === "") {
                    return false;
                }
            }

            return true;
        });

        defineRule("", () => true);
    },
};
