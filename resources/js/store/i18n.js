// store/i18n.js
import { createI18n } from 'vue-i18n';
import lang from '../../lang/lang.json';
import { watch } from 'vue'; // Import watch from vue

const messages = {
    en: Object.fromEntries(Object.entries(lang).map(([key, value]) => [key, value.en])),
    ar: Object.fromEntries(Object.entries(lang).map(([key, value]) => [key, value.ar])),
};

const i18n = createI18n({
    legacy: false,
    locale: 'en', // default locale
    fallbackLocale: 'en',
    messages,
});

export const initializeI18n = (languageStore) => {
    i18n.global.locale.value = languageStore.language;

    watch(languageStore.$state, (state) => {
        i18n.global.locale.value = state.language;
    });

    return i18n;
};

export default i18n;
