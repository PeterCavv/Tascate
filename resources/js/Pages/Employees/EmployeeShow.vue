<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { useToast } from 'primevue/usetoast';

const props = defineProps({
    employee: Object,
    can: Object,
});

const toast = useToast();
const showDeleteModal = ref(false);
const form = useForm({});

const employeeMenu = ref([
    {
        label: 'Editar',
        icon: 'pi pi-pencil',
        command: () => {
            if (!props.employee?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de empleado no disponible', life: 3000 });
                return;
            }
            router.visit(route('employees.edit', props.employee.id), {
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
                }
            });
        }
    },
    {
        label: 'Ascender',
        icon: 'pi pi-arrow-up',
        command: () => {
            if (!props.employee?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de empleado no disponible', life: 3000 });
                return;
            }
            router.visit(route('employees.promote', props.employee.id), {
                method: 'post',
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo ascender al empleado', life: 3000 });
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

const deleteEmployee = () => {
    form.delete(route('employees.destroy', props.employee.id), {
        onSuccess: () => {
            closeDeleteModal();
        },
    });
};

function edit_employee() {
    if (!props.employee?.id) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'ID de empleado no disponible', life: 3000 });
        return;
    }
    router.visit(route('employees.edit', props.employee.id), {
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
  <Head :title="employee.user.name" />

  <MainLayoutTemp>
      <ProfileLayout :user="employee.user">
          <template #nombre>
              {{ employee.user.name }}
          </template>

          <template #correo>
              {{ employee.user.email }}
          </template>

          <template #manager>
              {{ employee.manager ? employee.manager.user.name : 'No tiene manager asignado' }}
          </template>

          <template #tasca>
              {{ employee.tasca.name }}
          </template>

          <!-- Botones de acción -->
          <template #actions>
              <SplitButton
                  label="Editar"
                  :model="employeeMenu"
                  @click="edit_employee"
              />
          </template>

          <!-- Diálogo de confirmación de eliminación -->
          <Dialog v-model:visible="showDeleteModal" modal header="Confirmar eliminación" :style="{ width: '450px' }">
              <div class="p-6">
                  <p class="text-sm text-gray-600">
                      ¿Estás seguro de que quieres eliminar este empleado? Esta acción no se puede deshacer.
                  </p>
                  <div class="mt-6 flex justify-end space-x-3">
                      <Button label="Cancelar" severity="secondary" @click="closeDeleteModal" />
                      <Button
                          label="Eliminar"
                          severity="danger"
                          :loading="form.processing"
                          :disabled="form.processing"
                          @click="deleteEmployee"
                      />
                  </div>
              </div>
          </Dialog>
      </ProfileLayout>
  </MainLayoutTemp>
</template>
