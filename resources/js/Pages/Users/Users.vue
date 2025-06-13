<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, router} from '@inertiajs/vue3';
import {computed, ref} from "vue";
import InputText from 'primevue/inputtext'
import Listbox from 'primevue/listbox';
import Button from 'primevue/button'
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import 'primeicons/primeicons.css';
import EditUserDialog from "@/Components/EditUserForm.vue";

const showEditDialog = ref(false);

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    authUserId: {
        type: [Number, null],
        required: false,
    },
});

const roleOptions = [
    {label: 'Todos', value: ''},
    {label: 'Administrador', value: 'admin'},
    {label: 'Cliente', value: 'customer'},
    {label: 'Tasca', value: 'tasca'},
    {label: 'Empleado', value: 'employee'},
    {label: 'Manager', value: 'manager'},
];

const filterEmail = ref('')
const filterRole = ref('')
const selectedUser = ref(null)

const filteredUsers = computed(() => {
    return props.users.filter(user => {
        const matchesEmail = user.email.toLowerCase().includes(filterEmail.value.toLowerCase())
        const matchesRole = user.role_name.toLowerCase().includes(filterRole.value.toLowerCase())
        return matchesEmail && matchesRole
    })
})
</script>

<template>
    <Head title="Usuarios" />

    <div class="max-w-6xl mx-auto px-4 py-10">
        <!-- Tarjeta envolvente -->
        <div class="bg-white shadow-2xl rounded-2xl p-8 ring-1 ring-gray-200 space-y-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Gestión de Usuarios</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Filtros -->
                <section class="bg-gray-50 p-6 rounded-xl shadow-inner space-y-4">
                    <h3 class="text-xl font-semibold text-gray-700">Filtrar Usuarios</h3>

                    <IconField>
                        <InputIcon class="pi pi-search" />
                        <InputText
                            v-model="filterEmail"
                            placeholder="Filtrar por email"
                            class="w-full"
                        />
                    </IconField>

                    <Select
                        id="status"
                        v-model="filterRole"
                        :options="roleOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Seleccionar rol"
                        class="w-full"
                    />

                    <div class="relative">
                        <Listbox
                            :options="filteredUsers"
                            v-model="selectedUser"
                            class="w-full bg-white border border-gray-300 rounded-md min-h-60 max-h-60 overflow-auto focus:ring-2 focus:ring-blue-500"
                            :aria-labelledby="'users-listbox-label'"
                        >
                            <template #option="{ option, active, selected }">
                                <li
                                    :aria-selected="selected"
                                    tabindex="0"
                                    @keydown.enter="$event.target.click()"
                                    class="px-3 py-1 cursor-pointer"
                                >
                                    {{ option.email }}
                                </li>
                            </template>
                            <template #empty>
                                <li class="text-gray-500 py-2">No hay usuarios que coincidan</li>
                            </template>
                        </Listbox>
                    </div>
                </section>

                <!-- Detalles -->
                <section class="bg-gray-50 p-6 rounded-xl shadow-inner space-y-4">
                    <h3 class="text-xl font-semibold text-gray-700">Detalles del Usuario</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nombre</label>
                            <InputText :value="selectedUser?.name" disabled class="w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <InputText :value="selectedUser?.email" disabled class="w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Rol</label>
                            <InputText :value="selectedUser?.role_name" disabled class="w-full" />
                        </div>
                    </div>

                    <Button
                        :disabled="!selectedUser || selectedUser.id === authUserId || selectedUser.role_name === 'admin'"
                        label="Impersonar"
                        icon="pi pi-user"
                        class="w-full mt-2"
                        @click="router.get(route('impersonate.start', selectedUser.id))"
                        :class="selectedUser && selectedUser.role_name !== 'admin' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-300 text-gray-600 cursor-not-allowed'"
                    />

                    <div class="grid grid-cols-2 gap-4">
                        <Button
                            label="Editar"
                            icon="pi pi-pencil"
                            severity="info"
                            class="w-full"
                            @click="showEditDialog = true"
                            :disabled="!selectedUser"
                        />
                        <Button
                            label="Eliminar"
                            icon="pi pi-trash"
                            class="w-full"
                            severity="danger"
                            @click="router.delete(route('users.destroy', { user: selectedUser.id }))"
                            :disabled="!selectedUser || selectedUser.role_name === 'admin'"
                        />
                    </div>
                </section>
            </div>
        </div>
    </div>

    <EditUserDialog
        :user="selectedUser"
        v-model:visible="showEditDialog"
    />
</template>

