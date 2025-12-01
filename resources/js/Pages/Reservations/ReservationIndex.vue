<script setup>
import {Head, router} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import pickBy from 'lodash/pickBy';
import { Paginator } from "primevue";
import { watchThrottled } from '@vueuse/core';
import { ref, reactive } from "vue";
import {useI18n} from "vue-i18n";

defineOptions({
    layout: MainLayout,
});

const { t } = useI18n();

const props = defineProps({
    filters: Object,
    pagination: Object,
});

const params = reactive({
    search: props.filters.search,
});

const rows = ref(props.pagination.per_page);
const first = ref((props.pagination.current_page - 1) * props.pagination.per_page);

watchThrottled(
    params,
    () => {
        first.value = 0;
        router.get(
            route('reservations.index'),
            pickBy({
                ...params,
            }),
            {
                preserveState: true,
                replace: true,
            }
        )
    },
    { throttle: 300 }
)
</script>

<template>
    <Head title="Mis Reservas" />

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <section aria-labelledby="proposals-heading">
            <h1 id="proposals-heading" class="text-3xl font-bold text-gray-800">Mis Reservas</h1>
        </section>

        <section class="flex items-center" aria-labelledby="search-filter">
            <IconField>
                <InputIcon class="pi pi-search" />
                <InputText
                    v-model="params.search"
                    placeholder="Busca una reserva"
                    class="w-full max-w-[800px]"
                />
            </IconField>
        </section>

        <div v-if="pagination?.data?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-6">
            <div
                v-for="reservation in pagination.data"
                :key="reservation.id"
                @click="router.visit(`/reservations/${reservation.id}`, { preserveState: true, preserveScroll: true })"
                class="cursor-pointer"
            >
                <div class="bg-white shadow-md rounded-xl p-4 h-40 hover:shadow-lg transition flex items-end">
                    <div class="text-left">
                        <h2 class="text-2xl font-extrabold mb-1 text-gray-900">
                            Reserva en {{ reservation.tasca.name }}
                        </h2>
                        <p class="text-sm text-gray-500">{{ reservation.tasca.address }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 text-gray-500">
            {{ t('messages.reservation.no_reservations') }}
        </div>

        <div class="w-full mt-6">
            <Paginator
                v-model:first="first"
                :rows="rows"
                :totalRecords="props.pagination.total"
                currentPageReportTemplate="{first} de {last}"
                template="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                class="w-full bg-transparent border-0"
            />
        </div>
    </div>
</template>

<style scoped>
::v-deep(.p-paginator) {
    background-color: transparent !important;
    box-shadow: none !important;
    border: none !important;
}
::v-deep(.p-paginator-pages) {
    background-color: transparent !important;
}
</style>

