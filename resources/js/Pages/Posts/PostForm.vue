<script setup>
import {router, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import FloatLabel from "primevue/floatlabel";
import InputText from "primevue/inputtext";
import Message from "primevue/message";
import FileUpload from "primevue/fileupload";
import Textarea from "primevue/textarea";
import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";

const {t} = useI18n();
const toast = useToast();

defineOptions({
    layout: MainLayout,
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
            form.reset();
        },
        onError: (errors) => {
            Object.keys(errors).forEach(key => {
                toast.add({
                    severity: 'error',
                    summary: t('messages.toast.error'),
                    detail: errors[key],
                    life: 3000,
                });
            });
        },
        preserveState: true,
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-4">Crear Post</h1>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <FloatLabel variant="on">
                    <InputText
                        type="text"
                        id="title"
                        v-model="form.title"
                        class="w-full"
                        :invalid="form.errors.title"
                    />
                    <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                </FloatLabel>
                <Message
                    v-if="form.errors.title"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.title }}
                </Message>
            </div>
            <div class="mb-4">
                <FloatLabel variant="on">
                    <Textarea
                        id="content"
                        v-model="form.content"
                        rows="4"
                        class="mt-1 block w-full"
                        :invalid="form.errors.content"
                    />
                    <label for="content" class="block text-sm font-medium text-gray-700">Contenido</label>
                </FloatLabel>
                <Message
                    v-if="form.errors.content"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.content }}
                </Message>
            </div>
            <div class="mb-4">
                <label for="pictures" class="block text-sm font-medium text-gray-700">Imágenes</label>
                <FileUpload
                    type="file"
                    id="pictures"
                    multiple
                    @change="handleFileUpload"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                />
                <Message
                    v-if="form.errors.pictures"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.pictures }}
                </Message>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <Button
                    label="Cancelar"
                    severity="secondary"
                    variant="text"
                    @click="router.visit('/posts')"
                />
                <Button
                    label="Publicar"
                    icon="pi pi-upload"
                    type="submit"
                />
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
