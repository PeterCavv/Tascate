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
    manager: Object,
    tascas: Array,
});

const form = useForm({
    name: props.manager.user.name,
    email: props.manager.user.email,
    tasca_id: props.manager.tasca_id,
});

const submit = () => {
    form.put(route('managers.update', props.manager.id), {
        onSuccess: () => {
            // TO DO
        },
    });
};
</script>

<template>
  <Head title="Editar Manager" />

  <MainLayoutTemp>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Manager</h2>
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
                <InputLabel for="tasca_id" value="Tasca" />
                <SelectInput
                  id="tasca_id"
                  class="mt-1 block w-full"
                  v-model="form.tasca_id"
                  required
                >
                  <option value="">Selecciona una tasca</option>
                  <option
                    v-for="tasca in tascas"
                    :key="tasca.id"
                    :value="tasca.id"
                  >
                    {{ tasca.name }}
                  </option>
                </SelectInput>
                <InputError :message="form.errors.tasca_id" class="mt-2" />
              </div>

              <div class="flex items-center justify-end mt-4">
                <Link
                  :href="route('managers.show', manager.id)"
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
