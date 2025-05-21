<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Head} from "@inertiajs/vue3";

defineProps({
    reservation: Object,
});

defineOptions({
    layout: MainLayoutTemp,
});

</script>

<template>
    <Head :title="`Reserva ${reservation.id}`" />
</template>
