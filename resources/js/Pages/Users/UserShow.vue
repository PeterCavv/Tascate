<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { defineProps } from "vue";

defineOptions({
    layout: MainLayoutTemp,
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
</script>

<template>
    <Head title="Mi Perfil" />

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
                    :href="route('users.edit', user.id)"
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
</template>
