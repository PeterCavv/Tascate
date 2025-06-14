<script setup>
import {Head, router} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import { useRatingCalculator } from "@/Composables/useRatingCalculator.js";
import {useI18n} from "vue-i18n";
import {computed, ref} from "vue";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import ToggleSwitch from "primevue/toggleswitch";

const {tascas} = defineProps({
    tascas: Array,
})

const { t } = useI18n();

const { getRoundedRating } = useRatingCalculator();

defineOptions({
        layout: MainLayout,
});

function toggleFavorite(tasca) {
  router.post(route('tascas.toggle-favorite', tasca), {}, {
    preserveState: true,
    preserveScroll: true,
  });
}

const search = ref('')
const favoriteOnly = ref(false)

// Filtro por favoritos y nombre
const filteredTascas = computed(() => {
    const base = favoriteOnly.value
        ? tascas.filter(t => t.is_favorite)
        : tascas

    return base.filter(t =>
        t.name?.toLowerCase().includes(search.value.toLowerCase())
    )
})
</script>

<template>
    <Head title="Tascas" />

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <section aria-labelledby="proposals-heading">
            <h1 id="proposals-heading" class="text-3xl font-bold text-gray-800">{{ t('messages.tascas.list')}}</h1>
            <p class="text-gray-600 mt-1">{{ t('messages.tascas.desc') }}</p>
        </section>

        <section class="flex items-center gap-2" aria-labelledby="search-filter">

                <IconField>
                    <InputIcon class="pi pi-search" />
                    <InputText
                        v-model="search"
                        placeholder="Busca una propuesta"
                        class="w-full max-w-[800px]"
                    />
                </IconField>
                <template v-if="$page.props?.auth?.is_customer">
                    <ToggleSwitch inputId="checkbox" v-model="favoriteOnly" binary/>
                    <label for="checkbox" class="ml-1">Mostrar solo tascas guardadas..</label>
                </template>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-6">
            <div
                v-if="tascas.length > 0"
                v-for="tasca in filteredTascas"
                :key="tasca.id"
                :title="t('messages.tascas.show_details')"
                @click="router.visit(`/tascas/${tasca.id}`, { preserveState: true, preserveScroll: true })"
                class="mb-4 cursor-pointer relative"
            >
                <div class="bg-white shadow-md rounded-xl p-4 h-40 hover:shadow-lg transition flex items-end relative">

                    <img
                        :src="tasca.picture"
                        alt="Imagen de {{ tasca.name }}"
                        class="absolute inset-0 w-full h-full object-cover rounded-xl z-0"
                    />
                    <div class="absolute inset-0 rounded-xl bg-black bg-opacity-50 p-4 flex flex-col justify-end text-white">
                        <button
                            :title="tasca.is_favorite ? t('messages.tascas.unbookmark') : t('messages.tascas.bookmark')"
                            @click.stop
                            @click="toggleFavorite(tasca)"
                            class="absolute top-2 right-2 text-white hover:text-gray-200 transition-transform duration-200"
                        >
                            <i :class="tasca.is_favorite ? 'pi pi-bookmark-fill' : 'pi pi-bookmark'"></i>
                        </button>

                        <div class="text-left relative z-10 rounded-lg p-2 opacity-90">
                            <div class="flex items-center mb-1 ">
                                <h2 class="text-2xl font-extrabold truncate">{{ tasca.name }}</h2>
                                <div class="flex items-center ml-2">
                                    <template v-if="tasca.reviews.length > 0" v-for="i in 5" :key="i">
                                        <span v-if="i <= getRoundedRating(tasca)" class="text-yellow-400 text-base">★</span>
                                        <span v-else class="text-base">☆</span>
                                    </template>
                                    <template v-else>
                                        <span class="text-sm ml-2">
                                            {{ t('messages.tascas.no_ratings') }}
                                        </span>
                                    </template>
                                </div>
                            </div>
                            <p class="text-sm ">{{ tasca.address }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="col-span-1 sm:col-span-2 lg:col-span-2 text-center">
                <p>{{ t('messages.tascas.no_tascas') }}</p>
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


