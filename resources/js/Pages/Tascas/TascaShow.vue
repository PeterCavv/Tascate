<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import ReservationForm from "@/Components/ReservationForm.vue";
import { useDateFormatter } from "@/Composables/useDateFormatter.js";
import { useRatingCalculator } from "@/Composables/useRatingCalculator.js";
import {Head, router, usePage} from '@inertiajs/vue3';
import {ref} from "vue";
import 'primeicons/primeicons.css';
import Message from 'primevue/message';
import "primeicons/primeicons.css";

const { auth } = usePage().props

const { tasca } = defineProps({
    tasca: Object,
    tasca_picture_path: String,
    user_review: Object,
});

const { formateDateToDDMMYYYY, isToday } = useDateFormatter();
const { getRoundedRating } = useRatingCalculator();

const openReservation = ref(false);

defineOptions({
    layout: MainLayoutTemp,
});

const isOpenMoreThan8Hours = (tasca) => {
    const [openH, openM] = tasca.opening_time.split(':').map(Number);
    const [closeH, closeM] = tasca.closing_time.split(':').map(Number);

    const open = new Date();
    open.setHours(openH, openM, 0);

    const close = new Date();
    close.setHours(closeH, closeM, 0);

    let diff = (close - open) / (1000 * 60 * 60);

    if (diff < 0) diff += 24;

    return diff > 8;
}

const toggleFavorite = (tasca) => {
    router.post(route('tascas.toggle-favorite', tasca), {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

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
                <i
                    v-if="auth.user && auth.user.role === 'tasca' && auth.user.id === tasca.user.id"
                    @click="router.visit(route('tascas.edit', { tasca: tasca.id }))"
                    title="Editar"
                    class="pi pi-pen-to-square absolute top-4 right-4 text-white text-xl hover:text-green-400 cursor-pointer"></i>

                <h2 class="text-3xl font-bold">{{ tasca.name }}</h2>
                <p class="text-sm">{{ tasca.address }}</p>

                <div class="mt-2 flex justify-between items-center flex-wrap gap-2">
                    <div class="flex gap-2 flex-wrap">
                        <Message
                            :severity="tasca.reservation ? 'success' : 'error'"
                            size="small"
                        >
                            {{ tasca.reservation ? "Permite reservas" : "No permite reservas" }}
                        </Message>
                        <Message
                            v-if="isOpenMoreThan8Hours(tasca)"
                            icon="pi pi-clock"
                            severity="info"
                            size="small"
                        >
                            Horario extenso
                        </Message>
                        <Message
                            v-if="tasca.reviews.length >= 1 && getRoundedRating(tasca) >= 4"
                            icon="pi pi-crown"
                            severity="warn"
                            size="small"
                        >
                            Mejores valorados
                        </Message>
                    </div>

                    <!-- Botón alineado a la derecha -->
                    <div v-if="tasca.reservation && (!auth.user || auth.user.role !== 'tasca')">
                        <Button
                            @click="auth.user ? openReservation = true : router.visit('/login')"
                            class="px-4 py-1.5 rounded-full bg-green-600 text-white text-sm font-semibold shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                        >
                            {{ auth.user ? "Realizar Reserva" : "Inicia sesión para reservar" }}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative max-w-full mx-4">
        <div class="hidden sm:block absolute top-0 right-0 w-40">
            <div class="p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{getRoundedRating(tasca)}}</div>
                <div class="flex justify-center mt-1">
                    <template v-if="tasca.reviews.length > 0" v-for="i in 5" :key="i">
                        <span v-if="i <= getRoundedRating(tasca)" class="text-yellow-400 text-base">★</span>
                        <span v-else class="text-gray-300 text-base">☆</span>
                    </template>
                    <template v-else>
                        <span class="text-sm text-gray-400 ml-2">Sin calificar</span>
                    </template>
                </div>
                <div class="text-sm text-gray-600 mt-1">{{ tasca.reviews.length }} reseña/s</div>
                <button
                    @click.stop
                    @click="toggleFavorite(tasca)"
                    class="text-gray-400 hover:text-gray-600 transition-transform duration-200 mt-3 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
                >
                    <i :class="tasca.is_favorite ? 'pi pi-bookmark-fill text-gray-600 text-3xl' : 'pi pi-bookmark text-3xl'"></i>
                </button>
            </div>
        </div>

        <div class="pr-0 sm:pr-44">
            <div class="py-1">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Horario</h3>
                <span class="font-medium">{{ tasca.opening_time }} - </span>
                <span class="font-medium">{{ tasca.closing_time }}</span>
            </div>

            <div class="py-3">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Reseñas</h3>
                <button
                    v-if="auth.user && auth.user.role === 'customer' && user_review.length === 0"
                    @click="router.visit(route('reviews.create', { tasca: tasca.id }))"
                    class="px-4 py-1.5 rounded-full bg-green-600 text-white text-sm font-semibold shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                >
                    Dejar una reseña
                </button>

                <!-- PHONE ONLY -->
                <div class="block sm:hidden mb-4 w-full">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-md text-center w-4/5 mx-auto">
                        <div class="text-2xl font-bold text-gray-800">4.3</div>
                        <div class="flex justify-center mt-1">
                            <template v-for="i in 5" :key="i">
                                <span v-if="i <= 4" class="text-yellow-400 text-lg">★</span>
                                <span v-else class="text-gray-300 text-lg">☆</span>
                            </template>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">123 reseñas</div>
                    </div>
                </div>

                <div v-if="tasca.reviews.length > 0">
                    <ul class="divide-y divide-gray-200">
                        <li v-for="review in tasca.reviews" :key="review.id" class="py-2">
                            <template v-for="i in 5" :key="i">
                                <span v-if="i <= review.rating" class="text-yellow-400 text-xl">★</span>
                                <span v-else class="text-gray-300 text-xl">☆</span>
                            </template>
                            <template v-if="auth.user && review.customer.user.id === auth.user.id">
                                <button
                                    @click="router.visit(route('reviews.edit', { tasca: tasca, review: review.id }))"
                                    class="ml-3 text-sm text-blue-500 hover:text-blue-700 "
                                >
                                    Editar reseña
                                    <i class="pi pi-pencil"></i>
                                </button>
                            </template>
                            <template v-if="auth.user && auth.user.id === tasca.user.id">
                                <i class="pi pi-trash text-red-500 cursor-pointer hover:text-red-700 ml-3"
                                   @click="router.delete(route('reviews.destroy', { tasca: tasca, review: review.id }))"
                                   title="Eliminar reseña"></i>
                            </template>
                            <p class="text-sm text-gray-800">
                                "{{ review.body }}"
                                <span
                                    v-if="review.created_at !== review.updated_at"
                                    class="italic text-gray-500 text-xs"
                                >
                                    Editado
                                </span>
                            </p>
                            <div class="mt-1 flex items-center flex-wrap gap-2">
                                <p
                                    @click="router.visit(`/users/${review.customer.user.id}`)"
                                    class="text-xs text-gray-500 underline hover:text-gray-800 mt-1 cursor-pointer">
                                    – {{ review.customer.user.name }}
                                </p>
                                <p
                                    v-if="isToday(review.created_at)"
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    Publicado hoy
                                </p>
                                <p
                                    v-else
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    {{ formateDateToDDMMYYYY(review.created_at) }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <p class="pt-3 text-gray-500">No hay reseñas disponibles.</p>
                </div>
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
            <ReservationForm :tasca="tasca" :isEdit="false" :reservation="null" />
        </div>
    </transition>
</template>



<style>
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
