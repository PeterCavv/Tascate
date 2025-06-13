<script setup>
import { useForm, } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import {defineProps, ref, watch} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import IconField from "primevue/iconfield";
import FloatLabel from "primevue/floatlabel";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import 'primeicons/primeicons.css';
import FileUpload from "primevue/fileupload";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import {useI18n} from "vue-i18n";
import {useToast} from "primevue/usetoast";
import Message from "primevue/message";

const {t} = useI18n();
const toast = useToast();

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    user: Object,
    visible: Boolean,
});

const localVisible = ref(props.visible);

const emit = defineEmits(['update:visible']);

function closeDialog() {
    localVisible.value = false;
    form.reset();
    form.clearErrors();
    emit('update:visible', false);
}

const form = useForm({
    name: props.user?.name || "",
    email: props.user?.email || "",
    avatar: null,
});

const handleFileUpload = (event) => {
    form.avatar = event.files.length ? event.files[0] : null
};

const handCleanFile = () => {
    form.avatar = null;
}

const undoChanges = () => {
    form.name = props.user.name;
    form.email = props.user.email;
    handCleanFile();
}

const submitForm = () => {
    form._method = 'PATCH';

    form.submit("post", route("users.update", props.user.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            closeDialog();
        },
        onError: (errors) => {
            Object.keys(errors).forEach(key => {
                toast.add({
                    severity: 'error',
                    summary: t('messages.toast.error'),
                    detail: errors[key],
                    life: 3000,
                });
            })
        },
        preserveState: true,
    });
};

watch(() => props.visible, (newVal) => {
    localVisible.value = newVal;

    if (newVal && props.user) {
        form.name = props.user.name || "";
        form.email = props.user.email || "";
        form.avatar = null;
    }
});
</script>

<template>
    <Dialog
        v-model:visible="localVisible"
        :header="t('messages.user_data.edit')"
        modal
        class="w-full max-w-3xl"
        :aria-label="t('messages.user_data.edit')"
        role="dialog"
        aria-modal="true"
        @hide="closeDialog"
    >
        <div class="max-w-7xl mx-auto p-6">
            <form
                @submit.prevent="submitForm"
                class="space-y-6 max-w-7xl"
                autocomplete="off"
                role="form"
                aria-labelledby="form-title"
            >
                <h2 id="form-title" class="sr-only">{{ t('messages.user_data.edit') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <FloatLabel variant="on">
                            <IconField>
                                <InputIcon class="pi pi-user" />
                                <InputText
                                    id="name"
                                    v-model="form.name"
                                    class="w-full"
                                    maxlength="100"
                                    autocomplete="off"
                                    aria-required="true"
                                    :aria-label="t('messages.user_data.name')"
                                    :invalid="form.errors.name"
                                />
                            </IconField>
                            <label for="name" class="font-medium">
                                {{ t('messages.user_data.name') }}
                                <span aria-hidden="true">*</span>
                            </label>
                        </FloatLabel>
                        <Message
                            v-if="form.errors.name"
                            severity="error"
                            size="small"
                            variant="simple"
                        >
                            {{ form.errors.name }}
                        </Message>
                    </div>

                    <div>
                        <FloatLabel variant="on">
                            <IconField>
                                <InputIcon class="pi pi-envelope" />
                                <InputText
                                    id="email"
                                    v-model="form.email"
                                    class="w-full"
                                    maxlength="255"
                                    autocomplete="off"
                                    aria-required="true"
                                    :aria-label="t('messages.user_data.email')"
                                    :invalid="form.errors.email"
                                />
                            </IconField>
                            <label for="email" class="font-medium">{{ t('messages.user_data.email') }}
                                <span aria-hidden="true">*</span>
                            </label>
                        </FloatLabel>
                        <Message
                            v-if="form.errors.email"
                            severity="error"
                            size="small"
                            variant="simple"
                        >
                            {{ form.errors.email }}
                        </Message>
                    </div>
                </div>

                <div>
                    <label for="user_avatar" class="block font-medium mb-1">{{ t('messages.user_data.avatar') }}</label>
                    <FileUpload
                        inputId="avatar"
                        name="avatar"
                        accept="image/*"
                        mode="basic"
                        :auto="false"
                        :maxFileSize="1000000"
                        :maxFiles="1"
                        :showUploadButton="false"
                        @select="handleFileUpload"
                        @clear="handCleanFile"
                        class="w-full"
                        aria-labelledby="avatar"
                        :chooseLabel="t('messages.input.choose_file')"
                        :invalidFileSizeMessage="t('messages.input.limit_data')"
                        :invalidFileTypeMessage="t('messages.input.type_data')"
                    />
                </div>

                <div class="w-full md:flex md:justify-end space-y-2 md:space-y-0 md:space-x-3">
                    <Button
                        icon="pi pi-undo"
                        severity="secondary"
                        :label="t('messages.input.undo')"
                        @click="undoChanges"
                        class="w-full md:w-auto"
                        :aria-label="t('messages.input.undo_aria_label')"
                    />
                    <Button
                        icon="pi pi-save"
                        :label="t('messages.input.update')"
                        type="submit"
                        class="w-full md:w-auto"
                        :aria-label="t('messages.input.update_aria_label')"
                    />
                </div>
            </form>
        </div>
    </Dialog>
</template>

