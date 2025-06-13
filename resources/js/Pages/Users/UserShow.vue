<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import {defineProps, ref} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {useToast} from "primevue/usetoast";
import EditUserDialog from "@/Components/EditUserForm.vue";

const toast = useToast();

// const showDeleteModal = ref(false);

const showEditDialog = ref(false);

const employeeMenu = ref([
    {
        label: 'Eliminar',
        icon: 'pi pi-eraser',
        command: () => {
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.delete(route('users.destroy', props.user.id), {
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
                }
            });
        }
    },
]);

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    authUserId: {
        type: [Number, null],
        required: false,
    },
    csrfToken: {
        type: String,
        required: true,
    },
});

const deleteUser = () => {
    if (confirm("¿Seguro que quieres eliminar este usuario?")) {
        router.delete(route("users.destroy", props.user.id), {
            onSuccess: () => {
                alert("Usuario eliminado exitosamente.");
            },
            onError: (errors) => {
                console.error(errors);
            },
        });
    }
};

// function edit_user() {
//     console.log(props.user.id)
//     if (!props.user?.id) {
//         toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
//         return;
//     }
//     router.visit(route('users.edit', props.user.id), {
//         onError: () => {
//             toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
//         }
//     });
// }
</script>

<template>
    <Head title="Mi Perfil" />
    <ProfileLayout :user="props.user">
        <template #nombre>
            {{ props.user.name }}
        </template>
        <template #correo>
            {{ props.user.email }}
        </template>
        <template v-if="props.user.avatar.startsWith('http://tascate.test/storage/avatars')" #profile-image>
            <img :src="props.user.avatar" alt="Avatar" class="w-32 h-32 rounded-full object-cover" />
        </template>
        <template #actions>
            <SplitButton
                label="Editar"
                :model="employeeMenu"
                @click="() => showEditDialog = true"
            />
        </template>

    </ProfileLayout>

    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center space-x-4">
            <img
                :src="user.avatar"
                alt="Avatar"
                class="w-16 h-16 rounded-full border border-gray-300"
            />
            <div>
                <h2 class="text-xl font-semibold">{{ user.name }}</h2>
                <p class="text-gray-600">{{ user.email }}</p>
                <p class="text-gray-600">{{ user.role_name }}</p>
            </div>
        </div>

        <div class="mt-4 flex space-x-2">
            <div v-if="authUserId === user.id || $page.props.auth.is_admin">
                <Link
                    class="px-4 py-2 bg-blue-500 text-black rounded hover:bg-blue-600 transition"
                >
                    Editar
                </Link>
                <button
                    @click="deleteUser"
                    class="px-4 py-2 bg-red-500 text-black rounded hover:bg-red-600 transition"
                >
                    Eliminar
                </button>
                <Link
                    v-if="!$page.props.auth.impersonating && user.role_name !== 'admin'"
                    :href="route('impersonate.start', user.id)"
                    method="get"
                    class="btn px-4 py-2 bg-yellow-400 text-black rounded hover:bg-yellow-600 transition"
                    preserveState
                >Impersonar</Link>
            </div>
            <Link
                v-if="user.customer?.reviews && user.customer?.reviews.length > 0"
                :href="route('reviews.index', { user: user.id })"
                class="px-4 py-2 bg-green-500 text-black rounded hover:bg-green-600 transition"
            >
                Ver Reseñas
            </Link>
        </div>
    </div>

    <EditUserDialog
        :user="user"
        v-model:visible="showEditDialog"
        @updated="newUser => user = newUser"
    />
</template>
