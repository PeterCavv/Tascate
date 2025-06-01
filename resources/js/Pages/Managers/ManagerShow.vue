<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";

const props = defineProps({
    manager: Object,
});

const showDeleteModal = ref(false);

const form = useForm({});

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const deleteManager = () => {
    form.delete(route('managers.destroy', props.manager.id), {
        onSuccess: () => {
            closeDeleteModal();
        },
    });
};
</script>

<style scoped>
.field {
    margin-bottom: 1rem;
}
</style>

<template>

  <Head :title="manager.user.name" />

  <MainLayoutTemp>
    <ProfileLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Detalles del Manager
        </h2>
        <div class="flex space-x-4">
          <Link :href="route('managers.edit', manager.id)">
            <Button label="Editar" icon="pi pi-pencil" severity="primary" />
          </Link>
          <Button
            label="Eliminar"
            icon="pi pi-trash"
            severity="danger"
            @click="confirmDelete"
          />
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <Card header="Información Personal">
            <template #content>
              <div class="space-y-4">
                <div class="field">
                  <label class="block text-sm font-medium text-gray-700">Nombre</label>
                  <p class="mt-1 text-sm text-gray-900">{{ manager.user.name }}</p>
                </div>
                <div class="field">
                  <label class="block text-sm font-medium text-gray-700">Email</label>
                  <p class="mt-1 text-sm text-gray-900">{{ manager.user.email }}</p>
                </div>
              </div>
            </template>
          </Card>

          <Card header="Información Laboral">
            <template #content>
              <div class="space-y-4">
                <div class="field">
                  <label class="block text-sm font-medium text-gray-700">Tasca</label>
                  <p class="mt-1 text-sm text-gray-900">{{ manager.tasca.name }}</p>
                </div>
              </div>
            </template>
          </Card>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:visible="showDeleteModal" modal header="Confirmar eliminación" :style="{ width: '450px' }">
      <div class="p-6">
        <p class="text-sm text-gray-600">
          ¿Estás seguro de que quieres eliminar este manager? Esta acción no se puede deshacer.
        </p>
        <div class="mt-6 flex justify-end space-x-3">
          <Button label="Cancelar" severity="secondary" @click="closeDeleteModal" />
          <Button
            label="Eliminar"
            severity="danger"
            :loading="form.processing"
            :disabled="form.processing"
            @click="deleteManager"
          />
        </div>
      </div>
    </Dialog>
  </ProfileLayout>
  </MainLayoutTemp>
</template>
