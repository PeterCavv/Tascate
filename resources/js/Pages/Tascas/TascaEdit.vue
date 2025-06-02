<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {computed} from 'vue'
import {Head, useForm} from '@inertiajs/vue3'
import 'primeicons/primeicons.css'
import {Link} from '@inertiajs/vue3'

const {tasca} = defineProps({
    tasca: Object
})

defineOptions({
    layout: MainLayoutTemp,
})

const openingTimeFormatted = computed(() => tasca.opening_time?.slice(0, 5))
const closingTimeFormatted = computed(() => tasca.closing_time?.slice(0, 5))

const form = useForm({
    name: tasca ? tasca.name : '',
    address: tasca ? tasca.address : '',
    telephone: tasca ? tasca.telephone : '',
    reservation: tasca ? tasca.reservation : false,
    reservation_price: tasca ? tasca.reservation_price : 0,
    opening_time: tasca ? openingTimeFormatted.value : '',
    closing_time: tasca ? closingTimeFormatted.value : '',
    capacity: tasca ? tasca.capacity : 0,
    picture: null,
})



function handleFileUpload(e) {
    form.picture = e.target.files[0] ?? null;
}

const submitForm = () => {
    form._method = 'PATCH';

    form.submit("post", route("tascas.update", tasca.id), {
        preserveScroll: true,
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
    <Head title="Editar Tasca" />

    <form @submit.prevent="submitForm" class="w-full max-w-4xl mx-auto space-y-6 p-6 bg-white rounded shadow">
        <Link :href="`/tascas/${tasca.id}`" class="text-blue-600 hover:underline">
            <i class="pi pi-arrow-left mr-2 text-xs"></i>
            <span>Volver</span>
        </Link>
        <h1 class="text-2xl font-bold mb-6">Editar Tasca</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div class="w-full">
                <label class="block font-semibold mb-1">Nombre*</label>
                <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" required />
            </div>

            <!-- Dirección -->
            <div class="w-full">
                <label class="block font-semibold mb-1">Dirección*</label>
                <input v-model="form.address" type="text" class="w-full border rounded px-3 py-2" required />
            </div>

            <div class="w-full flex flex-wrap md:flex-nowrap items-end gap-4">
                <!-- Teléfono -->
                <div class="flex flex-col flex-1 min-w-[120px]">
                    <label class="block font-semibold text-sm mb-1">Teléfono*</label>
                    <input
                        v-model="form.telephone"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        pattern="\d{9}"
                        maxlength="9"
                        placeholder="9 dígitos"
                        required
                    />
                </div>

                <!-- Teléfono -->
                <div class="flex flex-col flex-1 min-w-[120px]">
                    <label class="block font-semibold text-sm mb-1">Capacidad*</label>
                    <input
                        v-model="form.capacity"
                        type="number"
                        class="w-full border rounded px-3 py-2"
                        maxlength="9"
                        placeholder="9 dígitos"
                        required
                    />
                </div>

                <!-- Hora apertura -->
                <div class="flex flex-col flex-1 w-full">
                    <label class="block font-semibold text-sm mb-1">Apertura*</label>
                    <input
                        v-model="form.opening_time"
                        type="time"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <!-- Hora cierre -->
                <div class="flex flex-col flex-1 w-full">
                    <label class="block font-semibold text-sm mb-1">Cierre*</label>
                    <input
                        v-model="form.closing_time"
                        type="time"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                </div>

                <!-- Precio de reserva -->
                <div class="flex flex-col flex-1 min-w-[120px]">
                    <label class="block font-semibold text-sm mb-1">Precio</label>
                    <input
                        v-model="form.reservation_price"
                        type="number"
                        class="w-full border rounded px-3 py-2 disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed"
                        :disabled="!form.reservation"
                        min="0"
                        step="0.01"
                    />
                </div>

                <!-- ¿Permite reservas? -->
                <div class="flex items-center space-x-2 whitespace-nowrap">
                    <input
                        v-model="form.reservation"
                        type="checkbox"
                        id="reservation"
                        class="w-5 h-5 accent-green-600"
                    />
                    <label for="reservation" class="font-medium">¿Permite Reservas?</label>
                </div>
            </div>

            <!-- Imagen -->
            <div class="w-full md:col-span-2">
                <label class="block font-semibold mb-1">Imagen</label>
                <input type="file" @change="handleFileUpload" accept="image/*" name="picture">
                <p class="text-sm text-gray-500 mt-1">Formato: JPG, PNG. Tamaño máximo: 2MB.</p>
            </div>
        </div>

        <!-- Botón de envío -->
        <button
            type="submit"
            class="w-full md:w-auto mt-4 py-2 px-6 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition"
        >
            Guardar Cambios
        </button>
    </form>
</template>

