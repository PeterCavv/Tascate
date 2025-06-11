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
import SelectButton from "primevue/selectbutton";
import ReviewForm from "@/Components/ReviewForm.vue";

const { t } = useI18n();
const { formateDateToDDMMYYYY, isToday } = useDateFormatter();

let map = null;

const activeSection = ref('map');
const showReviewDialog = ref(false);
const activeReview = ref(null);

const sections = [
    { label: 'Mapa', value: 'map' },
    { label: 'Reseñas', value: 'reviews' },
]

const { auth } = usePage().props;

const { tasca } = defineProps({
    tasca: Object,
    tasca_picture_path: String,
    user_review: Object,
});

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
            stylers: [{ color: "#e4fdd9" }]
        },
        {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ color: "#aef1dd" }]
        },
        {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{ color: "#b7f77d" }]
        },
        {
            featureType: "road",
            elementType: "geometry",
            stylers: [{ color: "#fce764" }]
        },
        {
            featureType: "road",
            elementType: "geometry.stroke",
            stylers: [
                { color: "#ffffff" },
                { weight: 0.6 }
            ]
        },
        {
            elementType: "labels",
            stylers: [{ visibility: "off" }]
        },
        {
            featureType: "administrative",
            elementType: "geometry",
            stylers: [{ color: "#c5f277" }]
        },
        {
            featureType: "administrative",
            elementType: "geometry.stroke",
            stylers: [
                { color: "#ffffff" },
                { weight: 0.8 }
            ]
        },
        {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{ color: "#53c190" }]
        }
    ];

    map = new window.google.maps.Map(mapContainer.value, {
        center: { lat, lng },
        zoom: 14,
        styles: summerStyle,
        streetViewControl: false,
        clickableIcons: false,
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
                    class="pi pi-pen-to-square absolute top-4 right-4 text-white text-xl hover:text-green-400 cursor-pointer transition"
                ></i>

                <h2 class="text-3xl font-bold">{{ tasca.name }}</h2>
                <p class="text-sm">{{ tasca.address }}</p>

                <div class="mt-2 flex justify-between items-center flex-wrap gap-2">
                    <div class="flex gap-2 flex-wrap">
                        <Message
                            :icon="tasca.reservation ? 'pi pi-check' : 'pi pi-times'"
                            :severity="tasca.reservation ? 'success' : 'error'"
                            size="small"
                        >
                            {{ tasca.reservation
                            ? t('messages.tasca.reservation_allowed')
                            : t('messages.tasca.reservation_not_allowed') }}
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
                    >
                        <Button
                            :label="t('messages.tasca.add_map')"
                            severity="info"
                            class="px-4 py-1.5 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-md hover:bg-blue-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1"
                        />
                    </Link>

                    <div v-if="tasca.reservation && (!auth.user || !auth.is_tasca)">
                        <Button
                            :label="auth.user
                ? t('messages.tasca.make_reservation')
                : t('messages.tasca.login_to_reserve')"
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
                <div class="text-2xl font-bold text-gray-800">{{ getRoundedRating(tasca) }}</div>
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
                    {{ tasca.reviews.length + t('messages.tasca.reviews') }}
                </div>
                <Button
                    v-if="!auth.is_tasca"
                    @click.stop="toggleFavorite(tasca)"
                    variant="text"
                    class="mt-1"
                    rounded
                    :icon="tasca.is_favorite ? 'pi pi-bookmark-fill' : 'pi pi-bookmark text-3xl'"
                />
            </div>
        </div>

        <div class="px-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">
                {{ t('messages.tasca.schedule') }}
            </h3>
            <span class="font-medium">{{ tasca.opening_time }} - </span>
            <span class="font-medium">{{ tasca.closing_time }}</span>

            <div class="flex justify-center my-6">
                <SelectButton
                    v-model="activeSection"
                    :options="sections"
                    optionLabel="label"
                    optionValue="value"
                    class="w-auto"
                    :allowEmpty="false"
                />
            </div>
        </div>

        <div :class="['transition-all', activeSection === 'reviews' ? 'pr-0 sm:pr-44' : 'pr-0']">
            <div class="relative min-h-[200px]">
                <transition name="slide-horizontal" mode="out-in">
                    <div :key="activeSection">
                        <div
                            v-if="activeSection === 'map' && tasca.latitude && tasca.longitude"
                            ref="mapContainer"
                            class="rounded-xl border-2 border-[#a0c77e] overflow-hidden w-full h-[400px]"
                        ></div>

                        <div v-else-if="activeSection === 'reviews'">
                            <h1 class="text-xl font-bold mb-2">{{ t('messages.tasca.reviews_section') }}</h1>
                            <Button
                                :label="t('messages.tasca.leave_review')"
                                v-if="auth.user && auth.is_customer && user_review.length === 0"
                                @click="() => { activeReview = null; showReviewDialog = true; }"
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
                                                @click="() => { activeReview = review; showReviewDialog = true; }"
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
                                                class="text-xs text-gray-500 underline hover:text-gray-800 mt-1 cursor-pointer"
                                            >
                                                – {{ review.customer.user.name }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ isToday(review.created_at) ? t('messages.tasca.published_today') : formateDateToDDMMYYYY(review.created_at) }}
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
                            <p class="text-gray-500">
                                {{ t('messages.tasca.no_map')}}
                            </p>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- PHONE ONLY -->
            <div class="block sm:hidden mb-4 w-full">
                <div
                    class="bg-white border border-gray-200 rounded-xl p-4 shadow-md text-center w-4/5 mx-auto"
                >
                    <div class="text-2xl font-bold text-gray-800">{{ getRoundedRating(tasca) }}</div>
                    <div class="flex justify-center mt-1">
                        <template v-for="i in 5" :key="i">
                            <span v-if="i <= getRoundedRating(tasca)" class="text-yellow-400 text-lg">★</span>
                            <span v-else class="text-gray-300 text-lg">☆</span>
                        </template>
                    </div>
                    <span class="text-sm text-gray-600 mt-1">
            {{ tasca.reviews.length + t('messages.tasca.reviews') }}
          </span>
                </div>
            </div>
        </div>
    </div>

    <transition name="fade">
        <div v-if="openReservation" class="fixed inset-0 bg-black bg-opacity-40 z-40" />
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

    <ReviewForm
        v-model:visible="showReviewDialog"
        :tasca="tasca"
        :review="activeReview"
    />
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

.map-container {
    height: 500px;
    width: 100%;
    border: 2px solid black;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.slide-horizontal-enter-active,
.slide-horizontal-leave-active {
    transition: transform 0.4s ease;
    position: absolute;
    width: 100%;
}

.slide-horizontal-enter-from {
    transform: translateX(100%);
}
.slide-horizontal-leave-to {
    transform: translateX(-100%);
}

.slide-horizontal-enter-active,
.slide-horizontal-leave-active {
    transition: transform 0.4s ease;
    position: absolute;
    width: 100%;
}

.slide-horizontal-enter-from {
    transform: translateX(100%);
}
.slide-horizontal-leave-to {
    transform: translateX(-100%);
}


</style>
