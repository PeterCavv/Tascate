<script setup>

import { Head } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";

defineOptions({
    layout: MainLayoutTemp,
});

</script>

<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-r from-blue-500 to-indigo-600 text-black">
        <Head title="Welcome" />

        <h1 class="text-5xl font-bold mb-4">¡Bienvenido a Nuestra Aplicación!</h1>
        <p class="text-lg mb-6">Explora las funcionalidades y disfruta de la experiencia.</p>

        <div class="flex space-x-4">

        </div>
    </div>
</template>

<style scoped>

</style>
