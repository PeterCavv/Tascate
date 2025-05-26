<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import "primeicons/primeicons.css";
import {router} from "@inertiajs/vue3";
import {useDateFormatter} from "@/Composables/useDateFormatter.js";
import {Head} from "@inertiajs/vue3";

defineOptions({
    layout: MainLayoutTemp,
});

defineProps({
    tascasProposals: Object
});

const traduceStatus = (status) => {
    switch (status) {
        case 'pending':
            return 'Pendiente';
        case 'accepted':
            return 'Aprobada';
        case 'rejected':
            return 'Rechazada';
        default:
            return status;
    }
};

const {formateDateToDDMMYYYY} = useDateFormatter();
</script>

<template>
    <Head title="Propuestas de Tasca" />

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Propuestas de Tasca</h1>
            <p class="text-gray-600 mt-1">Listado de todas las propuestas enviadas por los usuarios para crear una tasca.</p>
        </div>

        <DataTable
            :value="tascasProposals"
            :paginator="true"
            :rows="10"
            :size="'large'"
            :responsiveLayout="'scroll'"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            class="p-datatable-sm rounded-lg shadow"
        >
            <Column field="tasca_name" header="Nombre Tasca" />
            <Column field="cif" header="CIF" />
            <Column field="owner_name" header="Propietario" />
            <Column field="dni" header="DNI" />
            <Column header="Fecha de propuesta">
                <template #body="slotProps">
                    {{ formateDateToDDMMYYYY(slotProps.data.created_at) }}
                </template>
            </Column>

            <Column header="Estado">
                <template #body="slotProps">
                <span
                  class="px-2 py-1 rounded-full text-xs font-medium"
                  :class="{
                  'bg-yellow-100 text-yellow-800': slotProps.data.status === 'pending',
                  'bg-green-100 text-green-800': slotProps.data.status === 'accepted',
                  'bg-red-100 text-red-800': slotProps.data.status === 'rejected'
                }"
                >
                {{ traduceStatus(slotProps.data.status) }}
                </span>
                </template>
            </Column>

            <Column header="Acciones">
                <template #body="slotProps">
                    <i
                        @click="router.visit(route('tascas-proposals.show', { tascaProposal: slotProps.data.id }))"
                        class="pi pi-eye text-green-500 cursor-pointer mr-2 hover:text-green-700 transition"
                        title="Ver detalles"
                    ></i>
                    <i class="pi pi-file-edit text-green-500 cursor-pointer mr-2 hover:text-green-700 transition" title="Editar"></i>
                    <i class="pi pi-clone text-green-500 cursor-pointer mr-2 hover:text-green-700 transition" title="Clonar"></i>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<style scoped>
.p-datatable-sm .p-datatable-thead > tr > th {
    background-color: #f3f4f6; /* Tailwind bg-gray-100 */
    font-weight: 600;
    font-size: 0.875rem; /* text-sm */
    color: #374151; /* text-gray-700 */
}
</style>
