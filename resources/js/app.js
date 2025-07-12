import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import MainLayout from "@/Layouts/MainLayout.vue";
import {autoAnimatePlugin} from "@formkit/auto-animate/vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => {
        /*const pages = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/!**!/!*.vue'),
        )*/

        const pages = import.meta.glob("./Pages/**/*.vue", {
            eager: true
        });

        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || MainLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(autoAnimatePlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
