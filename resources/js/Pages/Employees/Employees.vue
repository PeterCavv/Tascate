<script setup>
import {computed, ref} from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import Card from 'primevue/card';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import {useI18n} from "vue-i18n";
import InputIcon from "primevue/inputicon";
import IconField from "primevue/iconfield";

const {t} = useI18n();

defineOptions({
    layout: MainLayout
})

const props = defineProps({
    employees: Array,
    manager: Object,
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

const tascas = computed(() => {
    return [
        ...new Map(
            props.employees.map(e => [e.tasca.id, e.tasca])
        ).values()
    ];
});

console.log(tascas)

const searchByName = ref('');
const searchByTascaName = ref('');

const filteredEmployees = computed(() => {
    return props.employees.filter(employee => {
        const name = employee.user?.name ?? '';
        const nameMatches = name.toLowerCase().includes(searchByName.value.toLowerCase());

        const tascaFilter = searchByTascaName.value;
        const tascaMatches = !tascaFilter || employee.tasca.id === tascaFilter.id;

        return nameMatches && tascaMatches;
    });
});
</script>

<template>
  <Head title="Empleados" />

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <section aria-labelledby="proposals-heading">
            <h1 id="proposals-heading" class="text-3xl font-bold text-gray-800">{{ t('messages.employees.title')}}</h1>
            <p class="text-gray-600 mt-1">{{ t('messages.employees.desc') }}</p>
        </section>

        <section class="flex items-center gap-3" aria-labelledby="search-filter">
            <IconField>
                <InputIcon class="pi pi-search" />
                <InputText
                    v-model="searchByName"
                    placeholder="Busca por nombre"
                    class="w-full max-w-[800px]"
                />
            </IconField>
            <Select
                v-if="$page.props.auth.is_admin"
                v-model="searchByTascaName"
                filter
                showClear
                :options="tascas"
                optionLabel="name"
                placeholder="Busca por tasca"
                class="w-full md:w-56"
            />
        </section>

        <Card v-if="manager" class="hover:shadow-lg transition-shadow ">
          <template #header>
              <div class="flex items-center space-x-4 p-4 bg-lime-200 border border-lime-400 rounded-lg">
                  <Avatar :image="manager.user.avatar || '/default-avatar.png'" :label="manager.user.name ? manager.user.name.charAt(0) : 'M'" size="large" shape="circle" class="border border-lime-500" />
                  <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-lime-700 truncate">
                          {{ manager.user.name }} <span class="text-xs text-lime-600">(Manager)</span>
                      </p>
                      <p class="text-sm text-lime-600 truncate">
                          {{ manager.user.email }}
                      </p>
                  </div>
              </div>
          </template>
          <template #footer>
              <div class="flex justify-end space-x-2">
                  <Link :href="route('managers.show', manager)" v-if="$page.props.auth.is_tasca || $page.props.auth.is_admin">
                      <Button icon="pi pi-eye" severity="info" text rounded />
                  </Link>
                  <Link :href="route('managers.edit', manager)" v-if="$page.props.auth.is_tasca || $page.props.auth.is_admin">
                      <Button icon="pi pi-pencil" severity="success" text rounded />
                  </Link>
              </div>
          </template>
        </Card>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <Card v-for="employee in filteredEmployees" :key="employee.id" class="hover:shadow-lg transition-shadow">
            <template #header>
              <div class="flex items-center space-x-4 p-4">
                  <Avatar :image="employee.user.avatar || '/default-avatar.png'" :label="employee.user.name ? employee.user.name.charAt(0) : 'T'" size="large" shape="circle" />
                  <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ employee.user.name }}
                  </p>
                  <p class="text-sm text-gray-500 truncate">
                    {{ employee.user.email }}
                  </p>
                  <p class="text-sm text-gray-500 truncate">
                      {{ employee.tasca.name }}
                  </p>
                </div>
              </div>
            </template>
            <template #footer>
              <div class="flex justify-end space-x-2">
                <Link :href="route('employees.show', employee)">
                  <Button icon="pi pi-eye" severity="info" text rounded />
                </Link>
                <Link :href="route('employees.edit', employee)">
                  <Button icon="pi pi-pencil" severity="success" text rounded />
                </Link>
                <Button icon="pi pi-trash" severity="danger" text rounded @click="confirmDelete(employee)" v-if="$page.props.auth.is_tasca || $page.props.auth.is_admin" />
              </div>
            </template>
          </Card>
            <Link
                :href="route('employees.create')"
                class="fixed bottom-10 right-12 z-50"
            >
                <Button label="Nuevo Empleado" icon="pi pi-user" />
            </Link>
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
</template>
