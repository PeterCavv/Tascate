<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { useToast } from 'primevue/usetoast';

const props = defineProps({
    manager: Object,
    can: Object,
});

const toast = useToast();
const showDeleteModal = ref(false);
const form = useForm({});

const managerMenu = ref([
    {
        label: 'Editar',
        icon: 'pi pi-pencil',
        command: () => {
            if (!props.manager?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de manager no disponible', life: 3000 });
                return;
            }
            router.visit(route('managers.edit', props.manager.id), {
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
                }
            });
        }
    },
    {
        label: 'Descender',
        icon: 'pi pi-arrow-down',
        command: () => {
            if (!props.manager?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de manager no disponible', life: 3000 });
                return;
            }
            router.visit(route('managers.demote', props.manager.id), {
                method: 'post',
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo descender al manager', life: 3000 });
                }
            });
        }
    },
    {
        separator: true
    },
    {
        label: 'Despedir',
        icon: 'pi pi-user-minus',
        command: () => {
            confirmDelete();
        }
    }
]);

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

function edit_manager() {
    if (!props.manager?.id) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'ID de manager no disponible', life: 3000 });
        return;
    }
    router.visit(route('managers.edit', props.manager.id), {
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
        }
    });
}
</script>

<style scoped>
.field {
    margin-bottom: 1rem;
}
</style>

<template>
  <Head :title="manager.user.name" />

  <MainLayoutTemp>
      <ProfileLayout :user="manager.user">
          <template #nombre>
              {{ manager.user.name }}
          </template>

          <template #correo>
              {{ manager.user.email }}
          </template>

          <template #tasca>
              {{ manager.tasca.name }}
          </template>

          <!-- Botones de acción -->
          <template #actions>
              <SplitButton
                  label="Editar"
                  :model="managerMenu"
                  @click="edit_manager"
              />
          </template>

          <!-- Diálogo de confirmación de eliminación -->
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
