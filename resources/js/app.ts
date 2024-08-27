import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';
import { initializeI18n } from '@/store/i18n'; // Import the initialize function
import { useLanguageStore } from '@/store/language'; // Import the language store

const appName = import.meta.env.VITE_APP_NAME || 'Volt';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)

            const languageStore = useLanguageStore();
            const i18n = initializeI18n(languageStore);

            app.use(i18n);
            app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
