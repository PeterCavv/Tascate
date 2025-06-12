<script setup>
import { ref, onMounted } from 'vue';
import {route} from "ziggy-js";
import { useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";
import {useToast} from "primevue/usetoast";
import MainLayout from "@/Layouts/MainLayout.vue";
import Button from "primevue/button";

const { t } = useI18n();
const toast = useToast();
const mapContainer = ref(null);
const latitude = ref(null);
const longitude = ref(null);
let map = null;
let marker = null;

const mapform = useForm({
    latitude: null,
    longitude: null,
});

const props = defineProps({
    tasca: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    if (!window.google) {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY}&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = initMap;
        document.head.appendChild(script);
    } else {
        initMap();
    }
});

defineOptions({
    name: 'MapPage',
    layout: MainLayout,
});

function initMap() {
    const defaultCenter = { lat: 37.9838, lng: -1.1285 };

    const mapStyles = [
        {
            featureType: "poi",
            stylers: [{ visibility: "off" }], // Hide points of interest
        },
        {
            featureType: "poi.business",
            stylers: [{ visibility: "off" }], // Hide businesses
        },
    ];

    map = new window.google.maps.Map(mapContainer.value, {
        center: defaultCenter,
        zoom: 14,
        styles: mapStyles,
    });

    marker = new window.google.maps.Marker({
        position: defaultCenter,
        map,
        draggable: true,
        icon: {
            url: "/images/tascate-icon-map.png",
            scaledSize: new window.google.maps.Size(30, 40),
        },
    });

    latitude.value = defaultCenter.lat;
    longitude.value = defaultCenter.lng;

    marker.addListener('dragend', (e) => {
        const pos = e.latLng;
        latitude.value = pos.lat();
        longitude.value = pos.lng();
    });

    map.addListener('click', (e) => {
        const pos = e.latLng;
        latitude.value = pos.lat();
        longitude.value = pos.lng();
        marker.setPosition(pos);
    });

    // Autocompletado de búsqueda
    const input = document.getElementById('autocomplete');
    const autocomplete = new window.google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) return;

        const location = place.geometry.location;
        latitude.value = location.lat();
        longitude.value = location.lng();

        map.panTo(location);
        map.setZoom(15);
        marker.setPosition(location);
    });
}

function submit() {
    mapform.latitude = latitude.value;
    mapform.longitude = longitude.value;

    mapform.post(route('update.map', {tasca: props.tasca.id}), {
        onSuccess: () => {
            window.location.reload();
        },
        onError: (errors) => {
            Object.keys(errors).forEach((key) => {
                toast.add({
                    severity: 'error',
                    summary: t('messages.toast.error'),
                    detail: errors[key][0],
                    life: 3000,
                });
            });
        },
    });
}
</script>

<template>
    <div class="location-wrapper">
        <div class="search-card">
            <h2 class="title">📍 {{ t('messages.tasca.map.search_location') }}</h2>
            <input
                id="autocomplete"
                type="text"
                placeholder="Escribe una dirección, ciudad o lugar..."
                class="search-input"
            />
        </div>

        <div ref="mapContainer" class="map-container"></div>

        <form @submit.prevent="submit" class="form-wrapper">
            <input type="hidden" v-model="latitude" />
            <input type="hidden" v-model="longitude" />
            <Button
                icon="pi pi-map-marker"
                :label="t('messages.tasca.map.set_location')"
                type="submit"
            />
        </form>
    </div>
</template>


<style scoped>
.location-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    padding: 2rem;
}

.search-card {
    background-color: #f7ffe0;
    border: 2px solid black;
    border-radius: 16px;
    padding: 1rem 1.5rem;
    width: 100%;
    max-width: 700px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

.title {
    font-weight: bold;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: #4b5563;
}

.search-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #4ade80;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.2s ease-in-out;
}

.search-input:focus {
    border-color: #facc15;
    box-shadow: 0 0 0 4px rgba(250, 204, 21, 0.2);
    outline: none;
}

.map-container {
    height: 420px;
    width: 100%;
    max-width: 700px;
    border: 4px solid black;
    border-radius: 20px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    background: #ffffff;
    transition: all 0.3s ease;
}

.form-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

.submit-btn {
    background-color: #4ade80;
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 1rem;
    transition: background-color 0.2s ease;
    border: none;
    cursor: pointer;
}

.submit-btn:hover {
    background-color: #22c55e;
}

</style>
