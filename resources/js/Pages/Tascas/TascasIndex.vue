<script setup>
import {Head, router} from '@inertiajs/vue3'
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";

const {tascas} = defineProps({
    tascas: Array,
})

defineOptions({
        layout: MainLayoutTemp,
});

/**
 * Devuelve la media de las valoraciones de una tasca.
 * @param {Object} tasca
 * @returns {number} media (decimal)
 */
function getAverageRating(tasca) {
    const reviews = tasca.reviews || []
    if (reviews.length === 0) return 0
    const sum = reviews.reduce((total, r) => total + r.rating, 0)
    return sum / reviews.length
}

/**
 * Media redondeada al entero más cercano
 * @param {Object} tasca
 * @returns {number} entero entre 0 y 5
 */
function getRoundedRating(tasca) {
    return Math.round(getAverageRating(tasca))
}

</script>

<template>
    <Head title="Tascas" />

    <div>
        <h1 class="text-2xl font-bold mb-6">Lista de Tascas</h1>

        <div class="flex flex-wrap -mx-2 mt-6">
            <div
                v-for="tasca in tascas"
                :key="tasca.id"
                @click="router.visit(`/tascas/${tasca.id}`, { preserveState: true, preserveScroll: true })"
                class="w-1/2 px-2 mb-4 cursor-pointer relative"
            >
                <div class="bg-white shadow-md rounded-xl p-4 h-40 hover:shadow-lg transition flex items-end relative">

                    <button
                        class="absolute top-2 right-2 text-red-400 hover:text-red-600 transition-transform duration-200"
                    >
                        <span>🤍</span>
                    </button>

                    <div class="text-left w-full">
                        <div class="flex items-center mb-1">
                            <h2 class="text-xl font-extrabold text-gray-900 truncate">{{ tasca.name }}</h2>
                            <div class="flex items-center ml-2">
                                <template v-if="tasca.reviews.length > 0" v-for="i in 5" :key="i">
                                    <span v-if="i <= getRoundedRating(tasca)" class="text-yellow-400 text-base">★</span>
                                    <span v-else class="text-gray-300 text-base">☆</span>
                                </template>
                                <template v-else>
                                    <span class="text-sm text-gray-400 ml-2">Sin calificar</span>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">{{ tasca.address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}
.slide-enter-from {
    transform: translateX(100%);
}
.slide-leave-to {
    transform: translateX(100%);
}
</style>


