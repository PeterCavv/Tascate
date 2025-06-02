import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import setupI18n from './i18n'
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
import ToastService from 'primevue/toastservice';
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
import KeyFilter from 'primevue/keyfilter';
import SplitButton from 'primevue/splitbutton';

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
        const i18n = setupI18n(props.initialPage.props.translations, props.initialPage.props.locale)

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
                },
                locale: {
                    choose: 'Elegir archivo',
                    upload: 'Subir',
                    cancel: 'Cancelar'
                }
            })
            .use(ToastService)
            .use(i18n)

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
        app.component('SplitButton', SplitButton);

        app.directive('keyfilter', KeyFilter);


        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
