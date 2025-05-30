<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { onMounted, ref, watch } from 'vue';
import FormLayout from "@/Layouts/FormLayout.vue";
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import FloatLabel from 'primevue/floatlabel';

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
    manager_id: '',
});

const showManagerField = ref(false);
const selectedTasca = ref(null);

watch(() => form.tasca_id, (newTascaId) => {
    if (newTascaId) {
        selectedTasca.value = props.tascas.find(t => t.id === newTascaId);
        if (selectedTasca.value?.manager) {
            form.manager_id = selectedTasca.value.manager.id;
            showManagerField.value = true;
        } else {
            form.manager_id = '';
            showManagerField.value = false;
        }
    } else {
        selectedTasca.value = null;
        form.manager_id = '';
        showManagerField.value = false;
    }
});

const submit = () => {
    form.post(route('employees.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
  <FormLayout>
    <div class="w-full max-w-md mx-auto">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Crear Empleado</h2>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="field">
          <FloatLabel>
            <InputText
              id="name"
              v-model="form.name"
              class="w-full"
              required
              autofocus
            />
            <label for="name">Nombre*</label>
          </FloatLabel>
          <small class="p-error" v-if="form.errors.name">{{ form.errors.name }}</small>
        </div>

        <div class="field">
          <FloatLabel>
            <InputText
              id="email"
              type="email"
              v-model="form.email"
              class="w-full"
              required
            />
            <label for="email">Email*</label>
          </FloatLabel>
          <small class="p-error" v-if="form.errors.email">{{ form.errors.email }}</small>
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
              required
            />
            <label for="tasca_id">Tasca*</label>
          </FloatLabel>
          <small class="p-error" v-if="form.errors.tasca_id">{{ form.errors.tasca_id }}</small>
        </div>

        <div v-if="showManagerField" class="field">
          <FloatLabel>
            <InputText
              id="manager_id"
              v-model="selectedTasca.manager.user.name"
              class="w-full"
              disabled
            />
            <label for="manager_id">Manager</label>
          </FloatLabel>
          <small class="text-gray-500">
            Manager asignado automáticamente.
          </small>
        </div>

        <div class="flex items-center justify-end space-x-4">
          <Link :href="route('employees.index')">
            <Button label="Cancelar" severity="secondary" text />
          </Link>
          <Button
            type="submit"
            label="Crear Empleado"
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


