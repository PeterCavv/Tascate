<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormLayout from "@/Layouts/FormLayout.vue";
import Message from 'primevue/message'
import InputLabel from '@/Components/InputLabel.vue';
import Button from 'primevue/button';
import InputText from "primevue/inputtext";
import MainLayout from "@/Layouts/MainLayout.vue";
import Select from 'primevue/select';
import { watch, ref } from 'vue';
import FloatLabel from "primevue/floatlabel";
import {useI18n} from "vue-i18n";
import {useToast} from "primevue/usetoast";

defineOptions({
    layout: MainLayout
})

const {t} = useI18n();
const toast = useToast();

const props = defineProps({
    employee: {
        type: Object,
        required: true,
        default: () => ({
            id: null,
            user: {
                name: '',
                email: ''
            },
            manager_id: null,
            tasca: {
                id: null,
                name: ''
            }
        })
    },
    managers: {
        type: Array,
    },
});

const selectedManager = ref(null);

const form = useForm({
    name: props.employee?.user?.name ?? '',
    email: props.employee?.user?.email ?? '',
    manager_id: props.employee?.manager_id ?? null,
});

watch(() => props.employee, (newEmployee) => {
    if (newEmployee) {
        form.name = newEmployee.user?.name ?? '';
        form.email = newEmployee.user?.email ?? '';
        form.manager_id = newEmployee.manager_id ?? null;

        if (newEmployee.manager_id) {
            selectedManager.value = props.managers.find(m => m.id === newEmployee.manager_id);
        }
    }
}, { immediate: true });

const submit = () => {
    form.post(route('employees.update', props.employee.id), {
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
    });
};
</script>

<template>
  <Head title="Editar Empleado" />

  <FormLayout>
      <div class="w-full max-w-md mx-auto">
          <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Editar Empleado</h2>
          </div>

            <form @submit.prevent="submit" class="space-y-6">
              <div>
                  <FloatLabel variant="on">
                      <InputText
                          id="name"
                          type="text"
                          class="mt-1 block w-full"
                          v-model="form.name"
                          :invalid="form.errors.name"
                      />
                      <label for="name">{{ t('messages.employee_form.name') }}*</label>
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
                      <label for="email">{{ t('messages.employee_form.email') }}*</label>
                      <InputText
                          id="email"
                          type="email"
                          class="mt-1 block w-full"
                          v-model="form.email"
                          :invalid="form.errors.email"
                      />
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

              <div>
                  <FloatLabel variant="on">
                      <InputText
                          id="tasca"
                          type="text"
                          class="mt-1 block w-full"
                          v-model="employee.tasca.name"
                          disabled
                      />
                      <label for="tasca">{{ t('messages.employee_form.tasca') }}*</label>
                  </FloatLabel>
              </div>

              <div>
                  <FloatLabel variant="on">
                      <Select
                          id="manager_id"
                          class="mt-1 block w-full"
                          v-model="form.manager_id"
                          :options="props.managers.map(manager => ({ label: manager.user.name, value: manager.id }))"
                          :option-label="'label'"
                          :option-value="'value'"
                          required
                      />
                      <label for="manager_id">{{ t('messages.employee_form.manager')}}</label>
                  </FloatLabel>
                  <Message
                      v-if="form.errors.manager_id"
                      severity="error"
                      size="small"
                      variant="simple"
                  >
                      {{ form.errors.manager_id }}
                  </Message>
              </div>

              <div class="flex items-center justify-end mt-4">
                <Link
                  :href="route('employees.show', employee.id)"
                  class="text-gray-600 hover:text-gray-900 mr-4"
                >
                    {{ t('messages.employee_form.cancel') }}
                </Link>
                <Button
                    :label="t('messages.employee_form.update_employee')"
                    icon="pi pi-save"
                    type="submit"
                    :loading="form.processing"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                />
              </div>
            </form>
          </div>
  </FormLayout>
</template>
