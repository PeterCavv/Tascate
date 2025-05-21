<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {useForm, usePage} from '@inertiajs/vue3';
import {computed} from "vue";

const {tasca, isEdit, reservation} = defineProps({
    tasca: Object,
    isEdit: Boolean,
    reservation: Object
});

const { auth } = usePage().props

const form = useForm({
    tasca_id: tasca.id,
    reservation_price: tasca.reservation_price,
    reservation_date: reservation ? reservation.reservation_date : '',
    reservation_time: reservation ? reservation.reservation_time : '',
    people: reservation ? reservation.people : 1,
    observations: reservation ? reservation.observations : '',
});

const openingTimeFormatted = computed(() => tasca.opening_time?.slice(0, 5))
const closingTimeFormatted = computed(() => tasca.closing_time?.slice(0, 5))

const today = new Date().toISOString().split('T')[0]

defineOptions({
layout: MainLayoutTemp,
});

const submitReservation = () => {
    !isEdit ? form.post(route('reservations.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    }) : form.put(route('reservations.update', reservation), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

</script>

<template>

    <div class="max-w-2xl mx-auto p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <span v-if="!isEdit">Reservar en {{ tasca.name }}</span>
            <span v-else>Reserva en {{ tasca.name }}</span>
        </h2>

        <form @submit.prevent="submitReservation">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input
                        :value="auth.user.name"
                        name="name"
                        type="text"
                        id="name"
                        placeholder="Nombre"
                        class="border border-gray-300 rounded-md p-2 w-full"
                        disabled
                    />
                </div>

                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de reserva</label>
                        <input
                            v-model="form.reservation_date"
                            name="reservation_date"
                            required
                            type="date"
                            id="date"
                            class="border border-gray-300 rounded-md p-2 w-full"
                            :min="today"
                        />
                    </div>

                    <div class="flex-1">
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Hora de la reserva</label>
                        <input
                            v-model="form.reservation_time"
                            required
                            name="reservation_time"
                            type="time"
                            id="time"
                            class="border border-gray-300 rounded-md p-2 w-full"
                            :min="openingTimeFormatted"
                            :max="closingTimeFormatted"
                        />
                    </div>

                    <div class="flex-1">
                        <label for="people" class="block text-sm font-medium text-gray-700 mb-1">Nº Personas</label>
                        <input
                            v-model="form.people"
                            name="people"
                            required
                            type="number"
                            id="people"
                            class="border border-gray-300 rounded-md p-2 w-full"
                            :max="tasca.capacity"
                            :min="1"
                            @input="(e) => e.target.value = Math.max(1, Math.min(tasca.capacity, e.target.value))"
                        />
                    </div>
                </div>

                <div>
                    <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                    <textarea
                        v-model="form.observations"
                        name="observations"
                        id="observations"
                        class="border border-gray-300 rounded-md p-2 w-full resize-none"
                        rows="4" />
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        class="px-5 py-1.5 rounded-full bg-green-600 text-white font-semibold shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                    >
                        <span v-if="!isEdit">Reservar</span>
                        <span v-else>Actualizar Reserva</span>
                    </button>

                    <p v-if="!isEdit" class="font-bold text-green-950">Precio de la reserva: {{ tasca.reservation_price }}€</p>
                </div>

                <p v-if="!isEdit" class="text-xs text-gray-500 leading-relaxed"> El pago de la reserva se realiza a través de una plataforma segura. <br /> Si decides cancelar con más de 2 horas de antelación, te reembolsaremos el importe total de la reserva. En caso contrario, no será posble realizar el reembolso. </p>
            </div>
        </form>
    </div>
</template>
