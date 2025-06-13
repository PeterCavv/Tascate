<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import {useDateFormatter} from "@/Composables/useDateFormatter.js";
import {useI18n} from "vue-i18n";

const { t } = useI18n();

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    reservations: Array,
});

const selectedObservation = ref('');
const showDialog = ref(false);

function openDialog(observation) {
    selectedObservation.value = observation || 'Sin observaciones.';
    showDialog.value = true;
}

const { formateDateToDDMMYYYY } = useDateFormatter();


</script>

<template>
    <Head title="Gestión de Reservas" />

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ t('messages.tasca.reservation_managment.manage_reservations') }}</h1>
        <p class="text-gray-600">{{ t('messages.tasca.reservation_managment.list_reservations') }}</p>

        <DataTable
            :value="reservations"
            :paginator="true"
            :rows="7"
            :responsiveLayout="'scroll'"
            :size="'large'"
            class="p-datatable-sm rounded-lg shadow"
        >
            <Column :header="t('messages.tasca.reservation_managment.client')">
                <template #body="slotProps">
                    {{ slotProps.data.customer?.user?.name || 'Desconocido' }}
                </template>
            </Column>

            <Column field="people" :header="t('messages.tasca.reservation_managment.persons')" />

            <Column :header="t('messages.tasca.reservation_managment.date_time')">
                <template #body="slotProps">
                    {{ formateDateToDDMMYYYY(slotProps.data.reservation_date) }} - {{ slotProps.data.reservation_time }}
                </template>
            </Column>

            <Column :header="t('messages.tasca.reservation_managment.observations')">
                <template #body="slotProps">
                    <i
                        role="button"
                        tabindex="0"
                        :title="slotProps.data.observations ? t('messages.tasca.reservation_managment.see_observations') : t('messages.tasca.reservation_managment.no_observations')"
                        :class="[
        'pi',
        slotProps.data.observations ? 'pi-eye text-blue-500 hover:text-blue-700' : 'pi-eye-slash text-gray-400',
        'focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-400 rounded cursor-pointer'
      ]"
                        @click="openDialog(slotProps.data.observations)"
                        @keydown.enter="openDialog(slotProps.data.observations)"
                        :aria-label="t('messages.tasca.reservation_managment.observations')"
                    ></i>
                </template>
            </Column>
        </DataTable>

        <!-- Dialog para mostrar observaciones -->
        <Dialog
            v-model:visible="showDialog"
            modal
            header="Observaciones"
            :style="{ width: '30vw' }"
        >
            <p class="text-gray-700">{{ selectedObservation }}</p>
        </Dialog>
    </div>
</template>

<style scoped>
.p-datatable-sm .p-datatable-thead > tr > th {
    background-color: #f3f4f6;
    font-weight: 600;
    font-size: 0.875rem;
    color: #374151;
}
</style>
