<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { ref, watch } from 'vue';
import FormLayout from "@/Layouts/FormLayout.vue";
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import FloatLabel from 'primevue/floatlabel';
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";
import Message from "primevue/message";

defineOptions({
    layout: MainLayoutTemp,
});

const props = defineProps({
    tascas: Array,
    auth: Object,
});

const form = useForm({
    name: '',
    email: '',
    tasca_id: '',
    manager_id: null,
});

const showManagerField = ref(false);
const selectedTasca = ref(null);
const toast = useToast();
const {t} = useI18n();

watch(() => form.tasca_id, (newTascaId) => {
    if (newTascaId) {
        selectedTasca.value = props.tascas.find(t => t.id === newTascaId);
        if (selectedTasca.value?.manager) {
            form.manager_id = selectedTasca.value.manager.id;
            showManagerField.value = true;
        } else {
            form.manager_id = null;
            showManagerField.value = false;
        }
    } else {
        selectedTasca.value = null;
        form.manager_id = null;
        showManagerField.value = false;
    }
});

const submit = () => {
    form.submit("post", route('employees.store'), {
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
        preserveState: true,
    });
};
</script>

<template>
    <Toast/>
    <FormLayout>
        <div class="w-full max-w-md mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">{{ t('messages.employee_form.employee_create') }}</h2>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="field">
                    <FloatLabel>
                        <InputText
                            id="name"
                            v-model="form.name"
                            class="w-full"
                            autofocus
                            :invalid="form.errors.name"
                        />
                        <label for="name">{{ t('messages.employee_form.name') }}*</label>
                    </FloatLabel>
                    <Message severity="error"
                             size="small"
                             variant="simple"
                             v-if="form.errors.name">{{ form.errors.name }}
                    </Message>
                </div>

                <div class="field">
                    <FloatLabel>
                        <InputText
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="w-full"
                            :invalid="form.errors.email"
                        />
                        <label for="email">{{ t('messages.employee_form.email') }}*</label>
                    </FloatLabel>
                    <Message
                        severity="error"
                        size="small"
                        variant="simple"
                        v-if="form.errors.email">{{ form.errors.email }}</Message>
                </div>

                <div class="field">
                    <FloatLabel>
                        <Dropdown
                            id="tasca_id"
                            v-model="form.tasca_id"
                            :options="tascas"
                            optionLabel="name"
                            optionValue="id"
                            class="w-full"
                            :invalid="form.errors.tasca_id"
                        />
                        <label for="tasca_id">{{ t('messages.employee_form.tasca') }}*</label>
                    </FloatLabel>
                    <Message severity="error"
                             size="small"
                             variant="simple"
                             v-if="form.errors.tasca_id">{{ form.errors.tasca_id }}</Message>
                </div>

                <div v-if="showManagerField" class="field">
                    <FloatLabel>
                        <InputText
                            id="manager_id"
                            v-model="selectedTasca.manager.user.name"
                            class="w-full"
                            disabled
                        />
                        <label for="manager_id">{{ t('messages.employee_form.manager') }}</label>
                    </FloatLabel>
                    <small class="text-gray-500">
                        {{ t('messages.employee_form.manager_assigned_auto') }}
                    </small>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <Link :href="route('employees.index')">
                        <Button :label="t('messages.employee_form.cancel')" severity="secondary" text />
                    </Link>
                    <Button
                        type="submit"
                        :label="t('messages.employee_form.create_employee')"
                        severity="primary"
                        :loading="form.processing"
                        :disabled="form.processing"
                    />
                </div>
            </form>
        </div>
    </FormLayout>
</template>

<style scoped>
.field {
    margin-bottom: 1.5rem;
}
</style>


