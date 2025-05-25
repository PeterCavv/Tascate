<script setup>
    defineProps({
        imageUrl: String,
        visible: Boolean
    });
</script>

<template>
    <!-- Backdrop: sólo un v-if, cubre toda la pantalla -->
    <div
        v-if="visible"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm"
        @click.self="$emit('close')"
    >
        <!-- Animación sólo para el contenido -->
        <transition
            enter-active-class="transition transform duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition transform duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="visible"
                class="relative bg-white rounded-2xl overflow-hidden shadow-xl max-w-full sm:max-w-4xl mx-4"
            >
                <img
                    :src="imageUrl"
                    alt="Imagen ampliada"
                    class="w-full h-auto"
                />
            </div>
        </transition>
    </div>
</template>


