<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";

const props = defineProps({
    employees: Array,
    can: Object,
});

const showDeleteModal = ref(false);
const employeeToDelete = ref(null);

const form = useForm({
    employee_id: null,
});

const confirmDelete = (employee) => {
    employeeToDelete.value = employee;
    form.employee_id = employee.id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    employeeToDelete.value = null;
    form.reset();
};

const deleteEmployee = () => {
    form.delete(route('employees.destroy', form.employee_id), {
        onSuccess: () => closeDeleteModal(),
    });
};
</script>

<template>
  <Head title="Empleados" />

  <MainLayoutTemp>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Empleados</h2>
        <Link :href="route('employees.create')">
          <Button label="Crear Empleado" icon="pi pi-plus" severity="primary" />
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card v-for="employee in employees" :key="employee.id" class="hover:shadow-lg transition-shadow">
            <template #header>
              <div class="flex items-center space-x-4 p-4">
                <Avatar :image="employee.avatar || '/default-avatar.png'" :label="employee.name.charAt(0)" size="large" shape="circle" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ employee.name }}
                  </p>
                  <p class="text-sm text-gray-500 truncate">
                    {{ employee.email }}
                  </p>
                </div>
              </div>
            </template>
            <template #footer>
              <div class="flex justify-end space-x-2">
                <Link :href="route('employees.show', employee.id)">
                  <Button icon="pi pi-eye" severity="info" text rounded />
                </Link>
                <Link :href="route('employees.edit', employee.id)">
                  <Button icon="pi pi-pencil" severity="success" text rounded />
                </Link>
                <Button icon="pi pi-trash" severity="danger" text rounded @click="confirmDelete(employee)" />
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
  </MainLayoutTemp>
</template>
