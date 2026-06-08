import { configure } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";

const CUSTOM_MESSAGES = {
    ar: {
        date_format: "يجب أن يكون {field} بتنسيق وقت صالح (مثال: 23:59).",
        decimal: "يجب أن يكون هذا {field} رقمًا عشريًا صالحًا",
        phone: "يجب أن يكون هذا {field} رقم هاتف صالحًا",
    },
    bn: {
        date_format: "{field} একটি বৈধ সময় বিন্যাসে হতে হবে (যেমন: 23:59)।",
        decimal: "এই {field} একটি বৈধ দশমিক সংখ্যা হতে হবে",
        phone: "এই {field} একটি বৈধ ফোন নম্বর হতে হবে",
    },
    ca: {
        date_format: "El {field} ha de tenir un format d'hora vàlid (ex: 23:59).",
        decimal: "Aquest {field} ha de ser un número decimal vàlid.",
        phone: "Aquest {field} ha de ser un número de telèfon vàlid.",
    },
    de: {
        date_format: "Das {field} muss ein gültiges Zeitformat haben (z.B.: 23:59).",
        decimal: "Dieses {field} muss eine gültige Dezimalzahl sein.",
        phone: "Dieses {field} muss eine gültige Telefonnummer sein.",
    },
    en: {
        date_format: "The {field} must be in a valid time format (e.g.: 23:59).",
        decimal: "This {field} must be a valid decimal number.",
        phone: "This {field} must be a valid phone number",
    },
    es: {
        date_format: "El {field} debe tener un formato de hora válido (ej.: 23:59).",
        decimal: "Este {field} debe ser un número decimal válido.",
        phone: "Este {field} debe ser un número de teléfono válido.",
    },
    fa: {
        date_format: "{field} باید در قالب زمان معتبر باشد (مثال: 23:59).",
        decimal: "این {field} باید یک عدد اعشاری معتبر باشد.",
        phone: "این {field} باید یک شماره تلفن معتبر باشد.",
    },
    fr: {
        date_format: "Le {field} doit être dans un format d'heure valide (ex : 23:59).",
        decimal: "Ce {field} doit être un nombre décimal valide.",
        phone: "Ce {field} doit être un numéro de téléphone valide.",
    },
    he: {
        date_format: "{field} חייב להיות בפורמט שעה תקין (לדוגמה: 23:59).",
        decimal: "זה {field} חייב להיות מספר עשרוני תקין.",
        phone: "זה {field} חייב להיות מספר טלפון תקין.",
    },
    hi_IN: {
        date_format: "{field} एक मान्य समय प्रारूप में होना चाहिए (उदा.: 23:59)।",
        decimal: "यह {field} एक मान्य दशमलव संख्या होनी चाहिए।",
        phone: "यह {field} कोई मान्य फ़ोन नंबर होना चाहिए।",
    },
    id: {
        date_format: "{field} harus dalam format waktu yang valid (contoh: 23:59).",
        decimal: "Nomor desimal {field} harus valid.",
        phone: "Nomor telepon {field} harus valid.",
    },
    it: {
        date_format: "Il {field} deve essere in un formato orario valido (es.: 23:59).",
        decimal: "Questo {field} deve essere un numero decimale valido.",
        phone: "Questo {field} deve essere un numero di telefono valido.",
    },
    ja: {
        date_format: "{field}は有効な時刻形式である必要があります（例: 23:59）。",
        decimal: "この{field}は有効な10進数である必要があります。",
        phone: "この{field}は有効な電話番号である必要があります。",
    },
    nl: {
        date_format: "Het {field} moet een geldig tijdformaat hebben (bijv.: 23:59).",
        decimal: "Dit {field} moet een geldig decimaal getal zijn.",
        phone: "Dit {field} moet een geldig telefoonnummer zijn.",
    },
    pl: {
        confirmed: "Pole {field} nie zgadza się z polem potwierdzającym",
        date_format: "Pole {field} musi mieć prawidłowy format czasu (np.: 23:59).",
        decimal: "Pole {field} musi być prawidłową liczbą dziesiętną.",
        phone: "Pole {field} musi zawierać prawidłowy numer telefonu",
    },
    pt_BR: {
        date_format: "O {field} deve estar em um formato de hora válido (ex.: 23:59).",
        decimal: "Este {field} deve ser um número decimal válido.",
        phone: "Este {field} deve ser um número de telefone válido.",
    },
    ro: {
        decimal: "Acest {field} trebuie să fie un număr zecimal valid.",
        phone: "Acest {field} trebuie să fie un număr de telefon valid.",
    },
    ru: {
        date_format: "{field} должно быть в допустимом формате времени (например: 23:59).",
        decimal: "Это {field} должно быть действительным десятичным числом.",
        phone: "Это {field} должно быть действительным номером телефона.",
    },
    sin: {
        date_format: "{field} වලංගු කාල ආකෘතියක් විය යුතුය (උදා: 23:59).",
        decimal: "මෙම {field} වටේ වලංගු දශක්ෂණ අංකය විය යුතුයි.",
        phone: "මෙම {field} වටේ වලංගු දුරකතන අංකය විය යුතුයි.",
    },
    tr: {
        date_format: "{field} geçerli bir saat biçiminde olmalıdır (ör.: 23:59).",
        decimal: "Bu {field} geçerli bir ondalık sayı olmalıdır.",
        phone: "Bu {field} geçerli bir telefon numarası olmalıdır.",
    },
    uk: {
        date_format: "{field} має бути у дійсному форматі часу (наприклад: 23:59).",
        decimal: "Це {field} повинно бути дійсним десятковим числом.",
        phone: "Це {field} повинно бути дійсним номером телефону.",
    },
    zh_CN: {
        date_format: "{field} 必须是有效的时间格式（例如：23:59）。",
        decimal: "这个 {field} 必须是一个有效的十进制数。",
        phone: "这个 {field} 必须是一个有效的电话号码。",
    },
};

const LOCALE_IMPORTS = {
    ar: () => import("@vee-validate/i18n/dist/locale/ar.json"),
    bn: () => import("@vee-validate/i18n/dist/locale/bn.json"),
    ca: () => import("@vee-validate/i18n/dist/locale/ca.json"),
    de: () => import("@vee-validate/i18n/dist/locale/de.json"),
    es: () => import("@vee-validate/i18n/dist/locale/es.json"),
    fa: () => import("@vee-validate/i18n/dist/locale/fa.json"),
    fr: () => import("@vee-validate/i18n/dist/locale/fr.json"),
    he: () => import("@vee-validate/i18n/dist/locale/he.json"),
    hi_IN: () => import("../../locales/hi_IN.json"),
    id: () => import("@vee-validate/i18n/dist/locale/id.json"),
    it: () => import("@vee-validate/i18n/dist/locale/it.json"),
    ja: () => import("@vee-validate/i18n/dist/locale/ja.json"),
    nl: () => import("@vee-validate/i18n/dist/locale/nl.json"),
    pl: () => import("@vee-validate/i18n/dist/locale/pl.json"),
    pt_BR: () => import("@vee-validate/i18n/dist/locale/pt_BR.json"),
    ro: () => import("../../locales/ro.json"),
    ru: () => import("@vee-validate/i18n/dist/locale/ru.json"),
    sin: () => import("../../locales/sin.json"),
    tr: () => import("@vee-validate/i18n/dist/locale/tr.json"),
    uk: () => import("@vee-validate/i18n/dist/locale/uk.json"),
    zh_CN: () => import("@vee-validate/i18n/dist/locale/zh_CN.json"),
};

const loadedLocales = {
    en: buildLocaleConfig("en", en),
};

export function buildLocaleConfig(code, data) {
    const extras = CUSTOM_MESSAGES[code] ?? {};

    return {
        ...data,
        messages: {
            ...data.messages,
            ...extras,
        },
    };
}

function applyLocaleConfig() {
    configure({
        generateMessage: localize({ ...loadedLocales }),
        validateOnBlur: true,
        validateOnInput: true,
        validateOnChange: true,
    });
}

export async function loadValidationLocale(code) {
    if (loadedLocales[code]) {
        return;
    }

    const loader = LOCALE_IMPORTS[code];

    if (! loader) {
        return;
    }

    const module = await loader();
    const localeData = module.default ?? module;

    loadedLocales[code] = buildLocaleConfig(code, localeData);
    applyLocaleConfig();
}

export function initValidationLocales() {
    applyLocaleConfig();
}
