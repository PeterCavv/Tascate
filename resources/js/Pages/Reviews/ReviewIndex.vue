<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    reviews: Object,
    user: Object,
})

defineOptions({
    layout: MainLayoutTemp,
});
</script>

<template>
    <Head :title="`Reseñas de ${user.name}`"/>

    <h2 class="text-2xl font-bold mb-4">Reseñas de {{ user.name }}</h2>

    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="review in reviews" :key="review.id" class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-semibold">{{ review.tasca.name }}</h3>
                <p class="text-gray-600">{{ review.body }}</p>
                <p class="text-sm text-gray-500">Calificación: {{ review.rating }}</p>
                <p class="text-sm text-gray-500">Fecha: {{ review.created_at }}</p>
            </div>
        </div>
    </div>
</template>


