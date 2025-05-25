<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Link, useForm} from "@inertiajs/vue3";
import 'primeicons/primeicons.css';

const {tascaProposal} = defineProps({
    tascaProposal: Object
});

const form = useForm({
    tasca_name: tascaProposal.tasca_name,
    address: tascaProposal.address,
    telephone: tascaProposal.telephone,
    cif: tascaProposal.cif,
    owner_name: tascaProposal.owner_name,
    owner_email: tascaProposal.owner_email,
    dni: tascaProposal.dni,
    status: tascaProposal.status || '',
});

defineOptions({
    layout: MainLayoutTemp,
});

const submitForm = () => {
    form.status === 'accepted' ?
        form.post(route('tascas-proposals.approve', {tascaProposal: tascaProposal.id}), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            },
            onError: (errors) => {
                console.error(errors);
            },
        }) :
        form.put(route('tascas-proposals.update', tascaProposal.id), {
            method: 'patch',
            onSuccess: () => {
                form.reset();
            },
            onError: (errors) => {
                console.error(errors);
            },
        })
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow space-y-6">
        <Link :href="`/tascas-proposals`" class="text-blue-600 hover:underline">
            <i class="pi pi-arrow-left mr-2 text-xs"></i>
            <span>Volver</span>
        </Link>
        <h1 class="text-2xl font-bold text-gray-800">Propuesta de Tasca</h1>
        <p class="text-gray-600">Revisa los detalles de la propuesta antes de aprobarla o rechazarla.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div><strong>Nombre:</strong> {{ tascaProposal.tasca_name }}</div>
            <div><strong>Dirección:</strong> {{ tascaProposal.address }}</div>
            <div><strong>Teléfono:</strong> {{ tascaProposal.telephone }}</div>
            <div><strong>CIF:</strong> {{ tascaProposal.cif }}</div>
            <div><strong>Propietario:</strong> {{ tascaProposal.owner_name }}</div>
            <div><strong>Email Propietario:</strong> {{ tascaProposal.owner_email }}</div>
            <div><strong>DNI:</strong> {{ tascaProposal.dni }}</div>
        </div>

        <form @submit.prevent="submitForm" class="flex items-center gap-4 mt-6">
            <label for="status" class="text-sm font-medium">Estado:</label>
            <select
                id="status"
                v-model="form.status"
                required
                class="border border-gray-300 rounded px-3 py-2 disabled:text-gray-500 disabled:cursor-not-allowed"
                :disabled="tascaProposal.status !== 'pending'"
            >
                <option value="" disabled>Seleccionar estado</option>
                <option value="pending">Pendiente</option>
                <option value="accepted">Aprobada</option>
                <option value="rejected">Rechazada</option>
            </select>
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
                Guardar
            </button>
            <p class="text-xs text-gray-500">Una vez actualizado el estado de la propuesta, no puede volver a cambiarse.</p>
        </form>
    </div>
</template>
