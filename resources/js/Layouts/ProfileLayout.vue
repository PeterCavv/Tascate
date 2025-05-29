<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationTextLogo from "@/Components/ApplicationTextLogo.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ref} from "vue";

const items = ref([
    {
        label: 'Editar',
        icon: 'pi pi-pencil',
        command: () => {
            route('employees.edit');
        }
    },
    {
        label: 'Ascender',
        icon: 'pi pi-arrow-up',
        command: () => {
            route('employees.promote');
        }
    },
    {
        separator: true
    },
    {
        label: 'Despedir',
        icon: 'pi pi-user-minus',
        command: () => {
            route('employees.destroy');
        }
    }
]);

function edit() {
    console.log('Guardar cambios');
}
</script>

<template>
        <div class="min-h-screen bg-cover bg-left bg-no-repeat ">
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
                        <SplitButton label="Editar" :model="items" @click="edit" />
                    </div>
                </div>

                <!-- Contenido principal inyectado -->
                <div class="px-4 pt-16 pb-4">
                    <slot></slot>
                </div>
            </div>
        </div>
</template>
