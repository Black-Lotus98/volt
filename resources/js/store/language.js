// store/language.js
import { ref } from 'vue';
import { defineStore } from 'pinia';
import { useStorage } from '@vueuse/core';

export const useLanguageStore = defineStore('language', () => {
    const language = useStorage('language', ref('en'));

    if (language.value !== 'en' && language.value !== 'ar') {
        language.value = 'en';
    }

    function toggleLanguage() {
        language.value = language.value === 'en' ? 'ar' : 'en';
    }

    return { language, toggleLanguage };
});
