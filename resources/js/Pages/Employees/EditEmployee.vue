<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";

const props = defineProps({
    employee: Object,
    managers: Array,
});

const form = useForm({
    name: props.employee.user.name,
    email: props.employee.user.email,
    manager_id: props.employee.manager_id,
});

const submit = () => {
    form.put(route('employees.update', props.employee.id), {
        onSuccess: () => {
            // TO DO
        },
    });
};
</script>

<template>
  <Head title="Editar Empleado" />

  <MainLayoutTemp>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Empleado</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <InputLabel for="name" value="Nombre" />
                <TextInput
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  required
                  autofocus
                />
                <InputError :message="form.errors.name" class="mt-2" />
              </div>

              <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                />
                <InputError :message="form.errors.email" class="mt-2" />
              </div>

              <div>
                <InputLabel for="manager_id" value="Manager" />
                <SelectInput
                  id="manager_id"
                  class="mt-1 block w-full"
                  v-model="form.manager_id"
                  required
                >
                  <option value="">Selecciona un manager</option>
                  <option
                    v-for="manager in managers"
                    :key="manager.id"
                    :value="manager.id"
                  >
                    {{ manager.name }}
                  </option>
                </SelectInput>
                <InputError :message="form.errors.manager_id" class="mt-2" />
              </div>

              <div class="flex items-center justify-end mt-4">
                <Link
                  :href="route('employees.show', employee.id)"
                  class="text-gray-600 hover:text-gray-900 mr-4"
                >
                  Cancelar
                </Link>
                <PrimaryButton
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Guardar Cambios
                </PrimaryButton>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </MainLayoutTemp>
</template>
