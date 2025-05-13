<script setup>
import { useForm } from "@inertiajs/vue3";
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { route } from "ziggy-js";
import { defineProps } from "vue";

defineOptions({
    layout: MainLayoutTemp,
});

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.user.name || "",
    email: props.user.email || "",
    avatar: null,
});

const handleFileUpload = (event) => {
    form.avatar = event.target.files[0];
};

const submitForm = () => {
    form.patch(route("users.update", props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            alert("Usuario actualizado exitosamente.");
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-6">Actualizar Usuario</h1>
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                />
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input
                    type="email"
                    id="email"
                    v-model="form.email"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                />
            </div>

            <!-- Avatar -->
            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
                <input
                    type="file"
                    id="avatar"
                    @change="handleFileUpload"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                />
            </div>

            <!-- Botón de Enviar -->
            <div class="mt-6">
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</template>
