<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import ReservationForm from "@/Components/ReservationForm.vue";
import {Head, router, usePage} from '@inertiajs/vue3';
import {ref} from "vue";

const { auth } = usePage().props

const { tasca } = defineProps({
    tasca: Object,
    tasca_picture_path: String,
});

const openReservation = ref(false);

defineOptions({
    layout: MainLayoutTemp,
});

</script>

<template>
    <Head :title="tasca.name" />

    <div class="max-w-full mx-4">
        <div class="relative h-44 rounded-xl overflow-hidden shadow-lg mb-4">
            <img
                :src="tasca_picture_path"
                alt="Foto de la tasca"
                class="absolute inset-0 object-cover w-full h-full"
            />
            <div class="absolute inset-0 bg-black bg-opacity-40 p-4 flex flex-col justify-end text-white">
                <h2 class="text-3xl font-bold">{{ tasca.name }}</h2>
                <p class="text-sm">{{ tasca.address }}</p>

                <div class="mt-2 flex items-center justify-between flex-wrap gap-2">
        <span
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="tasca.reservation ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
        >
          {{ tasca.reservation ? 'Permite reservas' : 'No permite reservas' }}
        </span>

                    <div v-if="tasca.reservation">
                        <button
                            v-if="auth.user"
                            @click="openReservation = true"
                            class="px-4 py-1.5 rounded-full bg-green-600 text-white text-sm font-semibold shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                        >
                            Realizar Reserva
                        </button>
                        <button
                            v-else
                            @click="router.visit('/login')"
                            class="px-4 py-1.5 rounded-full bg-green-600 text-white text-sm font-semibold shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                        >
                            Inicia sesión para reservar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="max-w-full mx-4">
        <div class="py-1">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Horario</h3>
            <span class="font-medium">{{ tasca.opening_time }} - </span>
            <span class="font-medium">{{ tasca.closing_time }}</span>
        </div>

        <div class="py-3">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Reseñas</h3>
            <div v-if="tasca.reviews.length > 0">
                <ul class="divide-y divide-gray-200">
                    <li v-for="review in tasca.reviews" :key="review.id" class="py-2">
                        <template v-for="i in 5" :key="i">
                            <span v-if="i <= review.rating" class="text-yellow-400 text-xl">★</span>
                            <span v-else class="text-gray-300 text-xl">☆</span>
                        </template>
                        <p class="text-sm text-gray-800">"{{ review.body }}"</p>
                        <p class="text-xs text-gray-500 mt-1">– {{ review.customer.user.name }}</p>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p class="text-gray-500">No hay reseñas disponibles.</p>
            </div>
        </div>
    </div>

    <transition name="fade">
        <div
            v-if="openReservation"
            class="fixed inset-0 bg-black bg-opacity-40 z-40"
        />
    </transition>

    <transition name="slide">
        <div
            v-if="openReservation"
            class="fixed top-0 right-0 bottom-0 bg-white w-full sm:w-2/3 md:w-1/2 lg:w-1/3 shadow-lg p-4 overflow-y-auto z-50"
        >
            <button
                @click="openReservation = false"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            >
                ✕
            </button>
            <ReservationForm :tasca="tasca" :isEdit="false" :reservation="null"/>
        </div>
    </transition>
</template>

<style>
/* Fondo: solo fade */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}

/* Panel: slide horizontal */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}
.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}
</style>
