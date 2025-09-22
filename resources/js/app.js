import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import darkModeState from './state/darkMode';
import './utils/toast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.use(ZiggyVue);
        
        // تسجيل المكونات العالمية
        if (window.ToastWrapper) {
            app.component('ToastWrapper', window.ToastWrapper);
        }
        
        // تطبيق الوضع الداكن عند تحميل التطبيق
        const applyDarkMode = () => {
            const isDark = localStorage.getItem('dark-mode') === 'true' || 
                          (!localStorage.getItem('dark-mode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            return isDark;
        };

        // تطبيق الوضع الداكن فوراً
        applyDarkMode();

        // إضافة خاصية global للـ toast
        app.config.globalProperties.$toast = window.toast;
        app.config.globalProperties.$showToast = window.showToast;

        app.mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});