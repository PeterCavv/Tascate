<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import "primeicons/primeicons.css";
import {router} from "@inertiajs/vue3";
import {useDateFormatter} from "@/Composables/useDateFormatter.js";
import {Head} from "@inertiajs/vue3";
import Tag from 'primevue/tag';

defineOptions({
    layout: MainLayout,
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

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">

        <section aria-labelledby="proposals-heading">
            <h1 id="proposals-heading" class="text-3xl font-bold text-gray-800">Propuestas de Tasca</h1>
            <p class="text-gray-600 mt-1">Listado de todas las propuestas enviadas por los usuarios para crear una tasca.</p>
        </section>

        <section aria-labelledby="datatable-heading">
            <h2 id="datatable-heading" class="sr-only">Tabla de propuestas de tasca</h2>
            <DataTable
                :value="tascasProposals"
                :paginator="true"
                :rows="7"
                :size="'large'"
                :responsiveLayout="'scroll'"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                class="p-datatable-sm rounded-lg shadow"
                aria-label="Listado de propuestas de tasca"
            >
                <Column field="tasca_name" header="Nombre Tasca" />
                <Column field="cif" header="CIF" />
                <Column field="owner_name" header="Propietario" />
                <Column field="dni" header="DNI" />

                <Column field="created_at" header="Fecha de propuesta" sortable>
                    <template #body="slotProps">
                        {{ formateDateToDDMMYYYY(slotProps.data.created_at) }}
                    </template>
                </Column>

                <Column field="status" header="Estado" sortable>
                    <template #body="slotProps">
                        <Tag
                            :value="traduceStatus(slotProps.data.status)"
                            class="text-xs font-medium"
                            :severity=
                                "slotProps.data.status === 'pending'
                                  ? 'warn'
                                  : slotProps.data.status === 'accepted'
                                  ? 'success'
                                  : 'danger'"
                        />
                    </template>
                </Column>

                <Column header="Acciones">
                    <template #body="slotProps">
                        <div role="group" aria-label="Acciones sobre propuesta" class="flex space-x-2">
                            <i
                                role="button"
                                tabindex="0"
                                title="Ver detalles"
                                @click="router.visit(route('tascas-proposals.show', { tascaProposal: slotProps.data.id }))"
                                @keydown.enter="router.visit(route('tascas-proposals.show', { tascaProposal: slotProps.data.id }))"
                                class="pi pi-eye text-green-500 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-400 rounded"
                                aria-label="Ver detalles de la propuesta"
                            ></i>

                            <i
                                role="button"
                                tabindex="0"
                                title="Editar propuesta"
                                @click="router.visit(route('tascas-proposals.edit', { tascaProposal: slotProps.data.id }))"
                                @keydown.enter="router.visit(route('tascas-proposals.edit', { tascaProposal: slotProps.data.id }))"
                                class="pi pi-file-edit text-yellow-500 hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-yellow-400 rounded"
                                aria-label="Editar propuesta"
                            ></i>

                            <i
                                role="button"
                                tabindex="0"
                                title="Clonar propuesta"
                                @click="router.post(route('tascas-proposals.clone', { tascaProposal: slotProps.data.id }))"
                                @keydown.enter="router.post(route('tascas-proposals.clone', { tascaProposal: slotProps.data.id }))"
                                class="pi pi-clone text-blue-500 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-400 rounded"
                                aria-label="Clonar propuesta"
                            ></i>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </section>
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
