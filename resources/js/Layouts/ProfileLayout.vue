<script setup>
import { Link, router } from '@inertiajs/vue3';
import ApplicationTextLogo from "@/Components/ApplicationTextLogo.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ref} from "vue";
import { useToast } from 'primevue/usetoast';

const props = defineProps({
    user: {
        type: Object,
        required: true,
        default: () => ({
            id: null,
            is_manager: false,
            is_employee: false
        })
    }
});

const toast = useToast();

const employee = ref([
    {
        label: 'Editar',
        icon: 'pi pi-pencil',
        command: () => {
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.visit(route('employees.edit', props.user.id), {
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
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.visit(route('employees.promote', props.user.id), {
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
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.visit(route('employees.destroy', props.user.id), {
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo despedir al empleado', life: 3000 });
                }
            });
        }
    }
]);

const manager = ref([
    {
        label: 'Editar',
        icon: 'pi pi-pencil',
        command: () => {
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.visit(route('managers.edit', props.user.id), {
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
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.visit(route('managers.demote', props.user.id), {
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
            if (!props.user?.id) {
                toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
                return;
            }
            router.visit(route('managers.destroy', props.user.id), {
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo despedir al manager', life: 3000 });
                }
            });
        }
    }
]);

function edit_employee() {
    if (!props.user?.id) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
        return;
    }
    router.visit(route('employees.edit', props.user.id), {
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
        }
    });
}

function edit_manager() {
    if (!props.user?.id) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'ID de usuario no disponible', life: 3000 });
        return;
    }
    router.visit(route('managers.edit', props.user.id), {
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo acceder a la edición', life: 3000 });
        }
    });
}
</script>

<template>
    <div class="min-h-screen bg-cover bg-left bg-no-repeat">
        <Toast />
        <!-- Contenedor principal con el slot -->
        <div class="bg-white/30 dark:bg-[#121212]/30 backdrop-blur-md rounded-xl overflow-hidden">
            <!-- Banner de perfil -->
            <div class="h-48 bg-gray-300 dark:bg-gray-700 relative">
                <slot name="banner">
                    <img src="/images/default_background.png" alt="Banner de perfil" class="w-full h-full object-cover">
                </slot>
            </div>

            <!-- Foto de perfil y acciones -->
            <div class="px-4 relative">
                <div class="absolute -top-16 left-4 border-4 border-white dark:border-[#121212] rounded-full">
                    <slot name="profile-image">
                        <img src="/images/default_profile.png" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover">
                    </slot>
                </div>
                <div class="flex justify-end pt-3">
                    <SplitButton v-if="$page.props.user && $page.props.user.is_manager"
                               label="Editar"
                               :model="manager"
                               @click="edit_manager" />
                    <SplitButton v-if="$page.props.user && $page.props.user.is_employee"
                               label="Editar"
                               :model="employee"
                               @click="edit_employee" />
                </div>
            </div>

            <!-- Contenido principal inyectado -->
            <div class="px-4 pt-16 pb-4">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
