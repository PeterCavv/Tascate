import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme:{
                    preset: Aura,
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// import '../css/app.css';
// import './bootstrap';
//
// import { createApp, h } from 'vue';
// import { createInertiaApp } from '@inertiajs/vue3';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { ZiggyVue } from '../../vendor/tightenco/ziggy';
// import PrimeVue from 'primevue/config';
// import Nora from '@primeuix/themes/nora';
//
// // PrimeVue Components
// import InputText from 'primevue/inputtext';
// import InputNumber from 'primevue/inputnumber';
// import InputMask from 'primevue/inputmask';
// import DatePicker from 'primevue/datepicker';
// import Select from 'primevue/select';
// import MultiSelect from 'primevue/multiselect';
// import Checkbox from 'primevue/checkbox';
//
// const appName = import.meta.env.VITE_APP_NAME || 'Tascate';
//
// createInertiaApp({
//     title: (title) => `${title} - ${appName}`,
//     resolve: (name) =>
//         resolvePageComponent(
//             `./Pages/${name}.vue`,
//             import.meta.glob('./Pages/**/*.vue'),
//         ),
//     setup({ el, App, props, plugin }) {
//         const vueApp = createApp({ render: () => h(App, props) });
//
//         vueApp.use(plugin);
//         vueApp.use(ZiggyVue);
//         vueApp.use(PrimeVue, {
//             ripple: true,
//             unstyled: false,
//             theme: {
//                 preset: Nora,
//                 options: {
//                     prefix: 'p',
//                     darkModeSelector: 'system',
//                     cssLayer: false,
//                     color: 'rose'
//                 }
//             },
//             pt: {
//                 // Global PrimeVue component options
//                 inputtext: {
//                     root: { class: 'w-full' }
//                 },
//                 inputnumber: {
//                     root: { class: 'w-full' }
//                 },
//                 datepicker: {
//                     root: { class: 'w-full' }
//                 },
//                 select: {
//                     root: { class: 'w-full' }
//                 },
//                 multiselect: {
//                     root: { class: 'w-full' }
//                 }
//             }
//         });
//
//         // Register components globally
//         vueApp.component('InputText', InputText);
//         vueApp.component('InputNumber', InputNumber);
//         vueApp.component('InputMask', InputMask);
//         vueApp.component('DatePicker', DatePicker);
//         vueApp.component('Select', Select);
//         vueApp.component('MultiSelect', MultiSelect);
//         vueApp.component('Checkbox', Checkbox);
//
//         vueApp.mount(el);
//     },
//     progress: {
//         color: '#4B5563',
//     },
// });
