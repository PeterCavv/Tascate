<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import ReservationForm from "@/Components/ReservationForm.vue";
import { useDateFormatter } from "@/Composables/useDateFormatter.js";
import { useRatingCalculator } from "@/Composables/useRatingCalculator.js";
import { Head, router, usePage } from "@inertiajs/vue3";
import "primeicons/primeicons.css";
import Message from "primevue/message";
import { onMounted, ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import { nextTick } from 'vue';
import Button from "primevue/button";
import {useI18n} from "vue-i18n";

const { t } = useI18n();

let map = null;

const activeSection = ref("map"); // "map" o "reviews"



const { auth } = usePage().props;

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
    const [openH, openM] = tasca.opening_time.split(":").map(Number);
    const [closeH, closeM] = tasca.closing_time.split(":").map(Number);

    const open = new Date();
    open.setHours(openH, openM, 0);

    const close = new Date();
    close.setHours(closeH, closeM, 0);

    let diff = (close - open) / (1000 * 60 * 60);

    if (diff < 0) diff += 24;

    return diff > 8;
};

const toggleFavorite = (tasca) => {
    router.post(route("tascas.toggle-favorite", tasca), {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Mapa

const mapContainer = ref(null);

const lat = parseFloat(tasca.latitude);
const lng = parseFloat(tasca.longitude);

onMounted(() => {
    if (!window.google) {
        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY}`;
        script.async = true;
        script.defer = true;
        script.onload = initMap;
        document.head.appendChild(script);
    } else {
        initMap();
    }
});

function initMap() {
    const summerStyle = [
        {
            elementType: "geometry",
            stylers: [{ color: "#e4fdd9" }], // fondo verde lima claro
        },
        {
            elementType: "labels.text.fill",
            stylers: [{ color: "#6b9b2e" }], // texto verde vibrante
        },
        {
            elementType: "labels.text.stroke",
            stylers: [{ color: "#ffffff" }], // borde blanco
        },
        {
            featureType: "administrative",
            elementType: "geometry",
            stylers: [{ color: "#c5f277" }], // lime intenso
        },
        {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{ color: "#faffb4" }], // amarillo suave
        },
        {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{ color: "#b7f77d" }], // verde hoja
        },
        {
            featureType: "road",
            elementType: "geometry",
            stylers: [{ color: "#fce764" }], // amarillo pastel
        },
        {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [{ color: "#a0a000" }], // texto amarillo oscuro
        },
        {
            featureType: "transit.line",
            elementType: "geometry",
            stylers: [{ color: "#ffe46b" }],
        },
        {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ color: "#aef1dd" }], // agua turquesa clara
        },
        {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{ color: "#53c190" }],
        },
    ];

    map = new window.google.maps.Map(mapContainer.value, {
        center: { lat, lng },
        zoom: 14,
        styles: summerStyle,
    });

    new window.google.maps.Marker({
        position: { lat, lng },
        map,
        title: tasca.name,
        icon: {
            url: "/images/tascate-icon-map.png",
            scaledSize: new window.google.maps.Size(30, 40),
        },
    });
}

watch(activeSection, (newValue) => {
    if (newValue === 'map') {
        nextTick(() => {
            setTimeout(() => {
                if (mapContainer.value) {
                    mapContainer.value.innerHTML = '';
                    initMap();
                }
            }, 500);
        });
    }
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
                <i
                    v-if="auth.user && auth.is_tasca && auth.user.id === tasca.user.id"
                    @click="router.visit(route('tascas.edit', { tasca: tasca.id }))"
                    :title="t('messages.tasca.edit')"
                    class="pi pi-pen-to-square absolute top-4 right-4 text-white text-xl hover:text-green-400
                    cursor-pointer transition"></i>

                <h2 class="text-3xl font-bold">{{ tasca.name }}</h2>
                <p class="text-sm">{{ tasca.address }}</p>

                <div class="mt-2 flex justify-between items-center flex-wrap gap-2">
                    <div class="flex gap-2 flex-wrap">
                        <Message
                            :icon="tasca.reservation ? 'pi pi-check' : 'pi pi-times'"
                            :severity="tasca.reservation ? 'success' : 'error'"
                            size="small"
                        >
                            {{ tasca.reservation ? t('messages.tasca.reservation_allowed') : t('messages.tasca.reservation_not_allowed') }}
                        </Message>
                        <Message
                            v-if="isOpenMoreThan8Hours(tasca)"
                            icon="pi pi-clock"
                            severity="info"
                            size="small"
                        >
                            {{ t('messages.tasca.extensive_hours') }}
                        </Message>
                        <Message
                            v-if="tasca.reviews.length >= 1 && getRoundedRating(tasca) >= 4"
                            icon="pi pi-crown"
                            severity="warn"
                            size="small"
                        >
                            {{ t('messages.tasca.top_rated') }}
                        </Message>
                    </div>
                    <Link
                        v-if="auth.user && auth.is_tasca && auth.user.id === tasca.user.id"
                        :href="`/${tasca.id}/map-set`"
                        class="px-4 py-1.5 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1"
                    >
                        {{ auth.user ? "Añadir ubicación tasca" : "Inicia sesión para acceder al mapa" }}
                    </Link>

                    <div v-if="tasca.reservation && (!auth.user || !auth.is_tasca)">
                        <Button
                            :label="auth.user ? t('messages.tasca.make_reservation') : t('messages.tasca.login_to_reserve')"
                            @click="auth.user ? openReservation = true : router.visit('/login')"
                            class="px-4 py-1.5 rounded-full focus:ring-offset-1"
                        />
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
                        <span class="text-sm text-gray-400 ml-2">
                            {{ t('messages.tasca.no_rating') }}
                        </span>
                    </template>
                </div>
                <div class="text-sm text-gray-600 mt-1">
                    {{ tasca.reviews.length + t('messages.tasca.reviews')}}
                </div>
                <Button
                    v-if="!auth.is_tasca"
                    @click.stop
                    @click="toggleFavorite(tasca)"
                    variant="text"
                    class="mt-1"
                    rounded
                    :icon="tasca.is_favorite ? 'pi pi-bookmark-fill' : 'pi pi-bookmark text-3xl'"
                />
            </div>
        </div>

        <div class="pr-0 sm:pr-44">
            <div class="py-1">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    {{ t('messages.tasca.schedule') }}
                </h3>
                <span class="font-medium">{{ tasca.opening_time }} - </span>
                <span class="font-medium">{{ tasca.closing_time }}</span>
            </div>

            <div class="flex justify-center gap-4 my-6">
                <button
                    @click="activeSection = 'map'"
                    :class="[
      'px-4 py-2 rounded-full font-semibold transition duration-300',
      activeSection === 'map'
        ? 'bg-green-600 text-white shadow'
        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
    ]"
                >
                    Mapa
                </button>

                <button
                    @click="activeSection = 'reviews'"
                    :class="[
      'px-4 py-2 rounded-full font-semibold transition duration-300',
      activeSection === 'reviews'
        ? 'bg-green-600 text-white shadow'
        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
    ]"
                >
                    Reseñas
                </button>
            </div>

            <transition name="slide-horizontal" mode="out-in">
                <div :key="activeSection">
                    <div v-if="activeSection === 'map' && tasca.latitude && tasca.longitude">
                        <div ref="mapContainer" class="map-container"></div>
                    </div>

                    <div v-else-if="activeSection === 'reviews'">
                        <h1 class="text-xl font-bold mb-2">Reseñas</h1>
                        <Button
                            :label="t('messages.tasca.leave_review')"
                            v-if="auth.user && auth.is_customer && user_review.length === 0"
                            @click="router.visit(route('reviews.create', { tasca: tasca.id }))"
                            variant="outlined"
                            class="px-4 py-1.5 focus:ring-offset-1"
                        />
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
                                            {{ t('messages.tasca.edit_review') }}
                                            <i class="pi pi-pencil"></i>
                                        </button>
                                    </template>
                                    <template v-if="auth.user && auth.user.id === tasca.user.id">
                                        <i class="pi pi-trash text-red-500 cursor-pointer hover:text-red-700 ml-3"
                                           @click="router.delete(route('reviews.destroy', { tasca: tasca, review: review.id }))"
                                           :title="t('messages.tasca.delete_review')"></i>
                                    </template>
                                    <p class="text-sm text-gray-800">
                                        "{{ review.body }}"
                                        <span
                                            v-if="review.created_at !== review.updated_at"
                                            class="italic text-gray-500 text-xs"
                                        >
                                            {{ t('messages.tasca.edited') }}
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
                                            {{ t('messages.tasca.published_today') }}
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
                            <p class="pt-3 text-gray-500">
                                {{ t('messages.tasca.no_reviews') }}
                            </p>
                        </div>
                    </div>

                    <div v-else>
                        <div v-if="tasca.latitude && tasca.longitude">
                            <div ref="mapContainer" class="h-96 w-full border-2 border-black rounded-2xl shadow-lg"></div>
                        </div>
                        <div v-else>
                            <p class="text-gray-500">No hay mapa disponible para esta tasca.</p>
                        </div>
                    </div>
                </div>
            </transition>

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
                        <span class="text-sm text-gray-600 mt-1">
                            {{ tasca.reviews.length + t('messages.tasca.reviews')}}
                        </span>
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
