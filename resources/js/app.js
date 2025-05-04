import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

// PrimeVue Components
import InputText from 'primevue/inputtext';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Select from 'primevue/select';
import MultiSelect from 'primevue/multiselect';
import InputNumber from 'primevue/inputnumber';
import InputMask from 'primevue/inputmask';
import Password from 'primevue/password';
import RadioButton from 'primevue/radiobutton';
import Textarea from 'primevue/textarea';
import DataTable from 'primevue/datatable';
import Accordion from 'primevue/accordion';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import ProgressBar from 'primevue/progressbar';
import ProgressSpinner from 'primevue/progressspinner';
import Avatar from 'primevue/avatar';

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
                        darkModeSelector: 'system',
                        cssLayer: false
                    }
                }
            });

        // Register PrimeVue components globally
        app.component('InputText', InputText);
        app.component('Calendar', Calendar);
        app.component('Dropdown', Dropdown);
        app.component('Checkbox', Checkbox);
        app.component('Button', Button);
        app.component('Card', Card);
        app.component('Select', Select);
        app.component('MultiSelect', MultiSelect);
        app.component('InputNumber', InputNumber);
        app.component('InputMask', InputMask);
        app.component('Password', Password);
        app.component('RadioButton', RadioButton);
        app.component('Textarea', Textarea);
        app.component('DataTable', DataTable);
        app.component('Accordion', Accordion);
        app.component('Dialog', Dialog);
        app.component('Toast', Toast);
        app.component('ProgressBar', ProgressBar);
        app.component('ProgressSpinner', ProgressSpinner);
        app.component('Avatar', Avatar);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
