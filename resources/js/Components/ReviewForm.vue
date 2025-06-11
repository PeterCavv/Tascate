<script setup>
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import {useToast} from "primevue/usetoast";

import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import Rating from 'primevue/rating';
import {ref, watch} from "vue";
import 'primeicons/primeicons.css';

const props = defineProps({
    tasca: Object,
    review: Object,
    visible: Boolean,
});

const localVisible = ref(props.visible);

const emit = defineEmits(['update:visible']);

const { auth } = usePage().props;
const { t } = useI18n();
const toast = useToast();

const form = useForm({
    rating: props.review ? props.review.rating : 0,
    body: props.review ? props.review.body : '',
});

function closeDialog() {
    emit('update:visible', false);
}

function submitReview() {
    const options = {
        onSuccess: () => closeDialog(),
        onError: (errors) =>
            Object.keys(errors).forEach(key => {
                toast.add({
                    severity: 'error',
                    summary: t('messages.toast.error'),
                    detail: errors[key],
                    life: 3000,
                });
            })
        ,
        forceFormData: true,
    };

    if (!props.review) {
        form.post(route('reviews.store', props.tasca), options);
    } else {
        form.put(route('reviews.update', [props.tasca, props.review.id]), options);
    }
}

function deleteReview() {
    form.delete(route('reviews.destroy', [props.tasca, props.review.id]), {
        forceFormData: true,
        onSuccess: () => closeDialog(),
        onError: (errors) => console.error(errors),
    });
}

watch(() => props.visible, (newVal) => {
    localVisible.value = newVal;
});

watch(localVisible, (newVal) => {
    emit('update:visible', newVal);
});

watch(
    () => props.review,
    (newReview) => {
        form.rating = newReview ? newReview.rating : 0;
        form.body = newReview ? newReview.body : '';
    },
    { immediate: true }
);
</script>

<template>
    <Dialog
        v-model:visible="localVisible"
        :header="t(review ? 'messages.tasca.review_form.edit' : 'messages.tasca.review_form.create')"
        modal
        class="w-full max-w-lg"
        @hide="closeDialog"
    >
        <form @submit.prevent="submitReview" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('messages.tasca.review_form.name') }}</label>
                <InputText :value="auth.user.name" class="w-full" disabled />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ t('messages.tasca.review_form.rating') }}
                </label>
                <Rating v-model="form.rating" :cancel="false" class="text-xl" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ t('messages.tasca.review_form.comment') }}
                </label>
                <Textarea
                    v-model="form.body"
                    autoResize
                    rows="4"
                    maxlength="1000"
                    class="w-full"
                    :invalid="form.errors.body"/>
            </div>

            <div class="flex flex-col space-y-2">
                <Button
                    type="submit"
                    :label="t('messages.tasca.review_form.submit')"
                    class="p-button-success w-full"
                    icon="pi pi-save"
                />
                <Button
                    v-if="review"
                    @click="closeDialog"
                    :label="t('messages.tasca.review_form.cancel')"
                    class="p-button-secondary w-full"
                    icon="pi pi-undo"
                />
                <Button
                    v-if="review"
                    @click="deleteReview"
                    :label="t('messages.tasca.review_form.delete')"
                    class="p-button-danger w-full"
                    icon="pi pi-trash"
                />
            </div>
        </form>
    </Dialog>
</template>

<style scoped>
.p-rating {
    gap: 0.5rem;
}
</style>
