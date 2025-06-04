<template>
    <div>
        <h1 class="text-xl font-bold mb-2">Mapa </h1>
        <div ref="mapContainer"  class="map-container" style="height: 500px; width: 50%"></div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

const mapContainer = ref(null);

const lat = 19.4326;
const lng = -99.1332;

onMounted(() => {
    if (!window.google) {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyCT_1eMwNr8Cw9gDXCaRdjaOEGAgQzjuNQ`;
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
            stylers: [{ color: "#e4fdd9" }] // fondo verde lima claro
        },
        {
            elementType: "labels.text.fill",
            stylers: [{ color: "#6b9b2e" }] // texto verde vibrante
        },
        {
            elementType: "labels.text.stroke",
            stylers: [{ color: "#ffffff" }] // borde blanco
        },
        {
            featureType: "administrative",
            elementType: "geometry",
            stylers: [{ color: "#c5f277" }] // lime intenso
        },
        {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{ color: "#faffb4" }] // amarillo suave
        },
        {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{ color: "#b7f77d" }] // verde hoja
        },
        {
            featureType: "road",
            elementType: "geometry",
            stylers: [{ color: "#fce764" }] // amarillo pastel
        },
        {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [{ color: "#a0a000" }] // texto amarillo oscuro
        },
        {
            featureType: "transit.line",
            elementType: "geometry",
            stylers: [{ color: "#ffe46b" }]
        },
        {
            featureType: "water",
            elementType: "geometry",
            stylers: [{ color: "#aef1dd" }] // agua turquesa clara
        },
        {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{ color: "#53c190" }]
        }
    ];

    const map = new window.google.maps.Map(mapContainer.value, {
        center: { lat, lng },
        zoom: 14,
        styles: summerStyle,
    });

    new window.google.maps.Marker({
        position: { lat, lng },
        map,
        title: "Ubicación central",
    });
}
</script>

<style scoped>
.map-container {
    height: 500px;
    width: 100%;
    border: 2px solid black;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>

