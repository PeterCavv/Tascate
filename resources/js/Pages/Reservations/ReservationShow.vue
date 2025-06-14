<template>
    <Head title="Reserva" />

    <div class="flex flex-col-reverse lg:flex-row gap-8 p-4">
        <Calendar :selectedDate="reservation_date" />
        <div class="w-full lg:w-2/3">
            <div class="text-4xl font-bold text-gray-800 mb-4">
                {{ formateDateToDDMMYYYY(reservation.reservation_date) }} - {{ reservation.reservation_time }}
            </div>

            <div class="relative h-44 rounded-xl overflow-hidden shadow-lg mb-4">
                <img
                    :src="reservation_path"
                    :alt="t('messages.reservation.picture_alt')"
                    class="absolute inset-0 object-cover w-full h-full"
                />
                <div class="absolute inset-0 bg-black bg-opacity-40 p-4 flex flex-col justify-end text-white">
                    <h2
                        class="text-2xl font-bold underline hover:text-green-300 transition cursor-pointer"
                    @click="router.visit(
                        `/tascas/${reservation.tasca.id}`,
                        { preserveState: true, preserveScroll: true })"
                    >
                        {{ reservation.tasca.name }}
                    </h2>
                    <p class="text-sm">{{ reservation.tasca.address }}</p>
                </div>
            </div>

            <div class="text-sm text-gray-700 mb-6 space-y-1">
                <p>
                    <strong>
                        {{ t('messages.reservation.labels.people') }}:
                    </strong>
                    {{ reservation.people }}
                </p>
                <p>
                    <strong>
                        {{ t('messages.reservation.labels.observations') }}:
                    </strong>
                    {{ reservation.observations || '—' }}
                </p>
            </div>

            <div class="flex flex-col space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
                <Button
                    :label="t('messages.reservation.buttons.edit')"
                    @click="openReservation = true"
                    class="flex-1 px-4 py-2"
                    icon="pi pi-file-edit"
                    raised
                />
                <Button
                    :label="t('messages.reservation.buttons.cancel')"
                    @click="router.delete(
                        `/reservations/${reservation.id}`,
                        { preserveState: true, preserveScroll: true })"
                    class="flex-1 px-4 py-2"
                    icon="pi pi-ban"
                    severity="contrast"
                    variant="outlined"
                />
            </div>
        </div>
    </div>

    <transition name="fade">
        <div
            v-if="openReservation"
            class="fixed inset-0 bg-black bg-opacity-40 z-40"
        />
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
            <ReservationForm
                :tasca="reservation.tasca"
                :isEdit="true"
                :reservation="reservation"
                @close="openReservation = false"
            />
        </div>
    </transition>
</template>

<script setup>
import {Head, router} from "@inertiajs/vue3";
import ReservationForm from "@/Components/ReservationForm.vue";
import { useDateFormatter } from "@/Composables/useDateFormatter.js";
import {ref} from "vue";
import Button from "primevue/button";
import {useI18n} from "vue-i18n";
import 'primeicons/primeicons.css'
import Calendar from "@/Components/Calendar.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const {t} = useI18n();

const {reservation} = defineProps({
    reservation: Object,
    reservation_path: String,
});

const { formateDateToDDMMYYYY } = useDateFormatter();

const openReservation = ref(false);

defineOptions({
    layout: MainLayout,
});

function cancelReservation(){
    router.delete(`/reservations/${reservation.id}`, { preserveState: true, preserveScroll: true })
}

</script>

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
</style>


