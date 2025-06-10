<script setup>
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";

defineOptions({
    layout: MainLayoutTemp,
});

const form = useForm({
    title: '',
    content: '',
    pictures: [],
});

const handleFileUpload = (event) => {
    const files = event.target.files;
    if (files.length > 0) {
        form.pictures = Array.from(files);
    }
};

const submitForm = () => {
    form.post(route('posts.store'), {
        forceFormData: true,
        onSuccess: () => {
            alert('Post creado exitosamente.');
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

form.post
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-4">Crear Post</h1>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input
                    type="text"
                    id="title"
                    v-model="form.title"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                />
                <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Contenido</label>
                <textarea
                    id="content"
                    v-model="form.content"
                    rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                ></textarea>
                <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
            </div>
            <div class="mb-4">
                <label for="pictures" class="block text-sm font-medium text-gray-700">Imágenes</label>
                <input
                    type="file"
                    id="pictures"
                    multiple
                    @change="handleFileUpload"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                />
                <p v-if="form.errors.pictures" class="mt-1 text-sm text-red-600">{{ form.errors.pictures }}</p>
            </div>
            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Guardar
            </button>
        </form>
    </div>
</template>

<style scoped>

</style>
