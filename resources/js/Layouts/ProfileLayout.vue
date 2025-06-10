<script setup>
import {Link, router, useForm} from '@inertiajs/vue3';
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

const form = useForm({});

</script>

<template>
    <div class="min-h-screen bg-cover bg-left bg-no-repeat">
        <Toast />

        <div class="bg-white/30 dark:bg-[#121212]/30 backdrop-blur-md rounded-xl overflow-hidden">
            <!-- Banner superior -->
            <div class="h-48 bg-gray-300 dark:bg-gray-700 relative">
                <slot name="banner">
                    <img src="/images/default_background.png" alt="Banner de perfil" class="w-full h-full object-cover" />
                </slot>
            </div>
            <div class="px-3 pt-3 pb-3 space-y-1"></div>
            <div class="relative px-6 -mt-16 flex items-center justify-between">
                <!-- Imagen -->
                <div class="flex-shrink-0 border-4 border-white dark:border-[#121212] rounded-full w-32 h-32 overflow-hidden">
                    <slot name="profile-image">
                        <img src="/images/tascate-profile.png" alt="Foto de perfil" class="w-full h-full object-cover" />
                    </slot>
                </div>



                <!-- Nombre + correo -->
                <div class="ml-6 flex-1">
                    <div class="px-3 pt-3 pb-3 space-y-1"></div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <slot name="nombre">Nombre no disponible</slot>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                        <slot name="correo">Correo no disponible</slot>
                    </p>
                </div>

                <!-- Botones -->
                <div class="ml-auto pt-4">
                    <slot name="actions" />
                </div>
            </div>

            <!-- Contenido adicional -->
            <div class="px-6 pt-6 pb-6 space-y-2">
                <!-- Datos personales opcionales -->

                <div v-if="$slots.tasca">
                    <strong class="text-gray-700 dark:text-gray-200">Tasca: </strong>
                    <slot name="tasca" />
                </div>

                <div v-if="$slots.manager">
                    <strong class="text-gray-700 dark:text-gray-200">Manager: </strong>
                    <slot name="manager" />
                </div>

                <div v-if="$slots.posts">
                    <strong class="text-gray-700 dark:text-gray-200">Posts: </strong>
                    <slot name="posts" />
                </div>

                <div v-if="$slots.favs">
                    <strong class="text-gray-700 dark:text-gray-200">Tascas Favoritas: </strong>
                    <slot name="favs" />
                </div>

                <div v-if="$slots.reviews">
                    <strong class="text-gray-700 dark:text-gray-200">Reviews: </strong>
                    <slot name="reviews" />
                </div>

                <slot />

            </div>
        </div>
    </div>
</template>

