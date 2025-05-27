<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { onMounted, ref } from 'vue';
import FormLayout from "@/Layouts/FormLayout.vue";

const props = defineProps({
    tascas: Array,
    managers: Array,
    auth: Object,
});

const form = useForm({
    name: '',
    email: '',
    tasca_id: '',
});

const isTascaAssigned = ref(false);

onMounted(() => {
    if (props.auth.user.tasca_id) {
        isTascaAssigned.value = true;
        form.tasca_id = props.auth.user.tasca_id;
    }
});

const submit = () => {
    form.post(route('managers.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<style scoped>
.field {
    margin-bottom: 1.5rem;
}
</style>

<template>
  <MainLayoutTemp>
<!--  <Head title="Crear Empleado" />-->

  <FormLayout>
    <div class="w-full max-w-md mx-auto">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Crear Manager</h2>
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
              :disabled="isTascaAssigned"
            />
            <label for="tasca_id">Tasca*</label>
          </FloatLabel>
          <small class="p-error" v-if="form.errors.tasca_id">{{ form.errors.tasca_id }}</small>
          <small v-if="isTascaAssigned" class="text-gray-500">
            Tasca asignada automáticamente
          </small>
        </div>

        <div class="flex items-center justify-end space-x-4">
          <Link :href="route('managers.index')">
            <Button label="Cancelar" severity="secondary" text />
          </Link>
          <Button
            type="submit"
            label="Crear Manager"
            severity="primary"
            :loading="form.processing"
            :disabled="form.processing"
          />
        </div>
      </form>
    </div>
  </FormLayout>
</MainLayoutTemp>
</template>


