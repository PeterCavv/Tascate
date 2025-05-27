import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// PrimeVue imports
import PrimeVue from 'primevue/config';
import 'primeicons/primeicons.css';
import Aura from '@primeuix/themes/aura';

import Checkbox from 'primevue/checkbox';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import FloatLabel from 'primevue/floatlabel';
import Toast from 'primevue/toast';
import Card from 'primevue/card';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import Avatar from 'primevue/avatar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import DatePicker from 'primevue/datepicker';
import InputNumber from 'primevue/inputnumber';
import Fieldset from 'primevue/fieldset';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                ripple: true,
                unstyled: false,
                theme: {
                    preset: Aura,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'light',
                        cssLayer: false
                    }
                }
            });

        // Register PrimeVue components globally
        app.component('InputText', InputText);
        app.component('Checkbox', Checkbox);
        app.component('Button', Button);
        app.component('FloatLabel', FloatLabel);
        app.component('Toast', Toast);
        app.component('Card', Card);
        app.component('Dialog', Dialog);
        app.component('Dropdown', Dropdown);
        app.component('Avatar', Avatar);
        app.component('DataTable', DataTable);
        app.component('Column', Column);
        app.component('Select', Select);
        app.component('DatePicker', DatePicker);
        app.component('InputNumber', InputNumber);
        app.component('Fieldset', Fieldset);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
