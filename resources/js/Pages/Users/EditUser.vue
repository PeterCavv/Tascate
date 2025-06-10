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
    form._method = 'PATCH';

    form.submit("post", route("users.update", props.user.id), {
        preserveScroll: true,
        forceFormData: true,
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
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Actualizar Usuario</h1>
        <form @submit.prevent="submitForm" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    placeholder="Nombre"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    placeholder="Correo Electrónico"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
            </div>

            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
                <input
                    id="avatar"
                    type="file"
                    @change="handleFileUpload"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                />
                <div v-if="form.avatar" class="mt-2 text-sm text-gray-600">
                    <p>{{ form.avatar.name }}</p>
                </div>
            </div>

            <button
                type="submit"
                class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Actualizar
            </button>
        </form>
    </div>
</template>
