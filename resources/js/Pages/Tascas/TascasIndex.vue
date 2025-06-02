<script setup>
import {Head, router} from '@inertiajs/vue3'
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { useRatingCalculator } from "@/Composables/useRatingCalculator.js";
import {useI18n} from "vue-i18n";

const {tascas} = defineProps({
    tascas: Array,
})

const { t } = useI18n();

const { getRoundedRating } = useRatingCalculator();

defineOptions({
        layout: MainLayoutTemp,
});

function toggleFavorite(tasca) {
  router.post(route('tascas.toggle-favorite', tasca), {}, {
    preserveState: true,
    preserveScroll: true,
  });
}


</script>

<template>
    <Head title="Tascas" />

    <div>
        <h1 class="text-2xl font-bold mb-6">
            {{ t('messages.tascas.list')}}
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-6">
            <div
                v-if="tascas.length > 0"
                v-for="tasca in tascas"
                :key="tasca.id"
                :title="t('messages.tascas.show_details')"
                @click="router.visit(`/tascas/${tasca.id}`, { preserveState: true, preserveScroll: true })"
                class="mb-4 cursor-pointer relative"
            >
                <div class="bg-white shadow-md rounded-xl p-4 h-40 hover:shadow-lg transition flex items-end relative">

                    <button
                        :title="tasca.is_favorite ? t('messages.tascas.unbookmark') : t('messages.tascas.bookmark')"
                        @click.stop
                        @click="toggleFavorite(tasca)"
                        class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-transform duration-200"
                    >
                        <i :class="tasca.is_favorite ? 'pi pi-bookmark-fill text-gray-600' : 'pi pi-bookmark'"></i>
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
                                    <span class="text-sm text-gray-400 ml-2">
                                        {{ t('messages.tascas.no_ratings') }}
                                    </span>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">{{ tasca.address }}</p>
                    </div>
                </div>
            </div>
            <div v-else class="col-span-1 sm:col-span-2 lg:col-span-2 text-center">
                <p class="text-gray-500">{{ t('messages.tascas.no_tascas') }}</p>
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


