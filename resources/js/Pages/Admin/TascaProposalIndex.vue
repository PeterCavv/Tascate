<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import "primeicons/primeicons.css";
import {router} from "@inertiajs/vue3";
import {useDateFormatter} from "@/Composables/useDateFormatter.js";

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
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Propuestas de Tasca</h1>
            <p class="text-gray-600 mt-1">Listado de todas las propuestas enviadas por los usuarios para crear una tasca.</p>
        </div>


        <div class="bg-white shadow overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nombre Tasca</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">CIF</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Propietario</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">DNI</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Fecha de propuesta</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-sm">
                    <tr v-for="proposal in tascasProposals" :key="proposal.id">
                        <td class="px-4 py-3">{{ proposal.tasca_name }}</td>
                        <td class="px-4 py-3">{{ proposal.cif }}</td>
                        <td class="px-4 py-3">{{ proposal.owner_name }}</td>
                        <td class="px-4 py-3">{{ proposal.dni }}</td>
                        <td class="px-4 py-3">{{ formateDateToDDMMYYYY(proposal.created_at) }}</td>
                        <td class="px-4 py-3">
                            <span
                              class="px-2 py-1 rounded-full text-xs font-medium"
                              :class="{
                              'bg-yellow-100 text-yellow-800': proposal.status === 'pending',
                              'bg-green-100 text-green-800': proposal.status === 'accepted',
                              'bg-red-100 text-red-800': proposal.status === 'rejected'
                            }"
                            >
                                {{ traduceStatus(proposal.status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <i
                                @click="router.visit(route('tascas-proposals.show', { tascaProposal: proposal.id }))"
                                class="pi pi-eye text-blue-400 text-l cursor-pointer mr-2" title="Ver detalles"/>
                            <i class="pi pi-file-edit text-blue-400 text-l cursor-pointer" title="Editar"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
