<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Head, Link, router} from '@inertiajs/vue3';
import {computed, ref} from "vue";
import InputText from 'primevue/inputtext'
import Listbox from 'primevue/listbox';
import Button from 'primevue/button'

defineOptions({
    layout: MainLayoutTemp,
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
        const matchesRole = user.role.toLowerCase().includes(filterRole.value.toLowerCase())
        return matchesEmail && matchesRole
    })
})

</script>

<template>
    <Head title="Usuarios" />
    <h2 id="usersTitle" class="text-xl sm:text-2xl font-bold mb-6 text-center sm:text-left">
        Usuarios
    </h2>
    <div class="flex gap-6 w-full">
        <div class="w-1/2 flex flex-col gap-4">
            <InputText
                v-model="filterEmail"
                placeholder="Filtrar por email"
            />
            <Select
                id="status"
                v-model="filterRole"
                :options="roleOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="Seleccionar estado"
            />

            <Listbox
                :options="filteredUsers"
                v-model="selectedUser"
            >
                <template #option="{ option }">
                    {{ option.email }}
                </template>
            </Listbox>
        </div>

        <div class="w-1/2 flex flex-col gap-4">
            <label class="font-semibold">Nombre</label>
            <InputText :value="selectedUser?.name" disabled class="w-full" />

            <label class="font-semibold">Email</label>
            <InputText :value="selectedUser?.email" disabled class="w-full" />

            <label class="font-semibold">Rol</label>
            <InputText :value="selectedUser?.role" disabled class="w-full" />

            <Button
                :disabled="!selectedUser || selectedUser?.id === authUserId || selectedUser.role === 'admin'"
                label="Impersonar"
                icon="pi pi-user"
                class="w-fit mt-2"
                @click="router.get(route('impersonate.start', selectedUser.id))"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
