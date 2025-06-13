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
    form.pictures = event.files ?? [];
};

const submitForm = () => {
    form.post(route('posts.store'), {
        preserveScroll: true,
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
        transform: (data) => {
            const formData = new FormData();
            formData.append('title', data.title);
            formData.append('content', data.content);

            data.pictures.forEach((file, i) => {
                formData.append('pictures[]', file); // <- clave
            });

            return formData;
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
                    accept="image/*"
                    :auto="false"
                    :maxFileSize="2000000"
                    :showUploadButton="false"
                    @select="handleFileUpload"
                    class="w-full"
                    aria-labelledby="post_pictures"
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
