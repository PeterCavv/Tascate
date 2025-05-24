<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Head, router} from "@inertiajs/vue3";

defineProps({
    reservations: Object,
});

defineOptions({
    layout: MainLayoutTemp,
});

</script>

<template>
    <Head title="Mis Reservas" />

    <h1 class="text-2xl font-bold mb-6">Mis Reservas</h1>

    <div v-if="reservations?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-6">
        <div
            v-for="reservation in reservations"
            :key="reservation.id"
            @click="router.visit(`/reservations/${reservation.id}`, { preserveState: true, preserveScroll: true })"
            class="cursor-pointer"
        >
            <div class="bg-white shadow-md rounded-xl p-4 h-40 hover:shadow-lg transition flex items-end">
                <div class="text-left">
                    <h2 class="text-2xl font-extrabold mb-1 text-gray-900">
                        Reserva en {{ reservation.tasca.name }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ reservation.tasca.address }}</p>
                </div>
            </div>
        </div>
    </div>

    <div v-else class="mt-6 text-gray-500">
        Actualmente no tienes ninguna reserva. ¿A qué esperas?
    </div>
</template>

