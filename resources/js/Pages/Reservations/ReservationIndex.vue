<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Head} from "@inertiajs/vue3";

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

    <div v-if="reservations" class="flex flex-wrap -mx-2 mt-6">
        <div
            v-for="reservation in reservations"
            :key="reservation.id"
            class="w-1/2 px-2 mb-4 cursor-pointer"
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
    <div v-else>
        <p class="text-gray-500">Actualmente no tienes ninguna reservas. ¿A qué esperas?</p>
    </div>


</template>
