<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { usePage } from '@inertiajs/vue3';

const { auth } = usePage().props

defineProps({
    tasca: Object
});

defineOptions({
layout: MainLayoutTemp,
});

const today = new Date().toISOString().split('T')[0]

</script>

<template>

    <div class="max-w-2xl mx-auto p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800"> Reservar en {{ tasca.name }}</h2>

        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input
                    type="text"
                    id="name"
                    placeholder="Nombre"
                    class="border border-gray-300 rounded-md p-2 w-full"
                    :value="auth.user.name"
                    disabled
                />
            </div>

            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Fecha de reserva</label>
                    <input
                        type="date"
                        id="date"
                        class="border border-gray-300 rounded-md p-2 w-full"
                        :min="today"
                    />
                </div>

                <div class="flex-1">
                    <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Hora de la reserva</label>
                    <input
                        type="time"
                        id="time"
                        class="border border-gray-300 rounded-md p-2 w-full"
                        :max="tasca.opening_time"
                        :min="tasca.closing_time"
                    />
                </div>

                <div class="flex-1">
                    <label for="people" class="block text-sm font-medium text-gray-700 mb-1">Nº Personas</label>
                    <input
                        type="number"
                        id="people"
                        class="border border-gray-300 rounded-md p-2 w-full"
                        :max="tasca.capacity"
                        :min="1"
                        value="1"
                        @input="(e) => e.target.value = Math.max(1, Math.min(tasca.capacity, e.target.value))"
                    />
                </div>
            </div>

            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea id="observations" class="border border-gray-300 rounded-md p-2 w-full resize-none" rows="4" />
            </div>

        </div>
    </div>
</template>
