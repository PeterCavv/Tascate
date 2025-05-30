<template>
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-60">
        <ProgressSpinner aria-label="Cargando..." stroke-width="4"
         style="width: 50px; height: 50px" strokeWidth="8" fill="transparent"
         animationDuration=".3s"/>
    </div>
</template>

<script setup>
import ProgressSpinner from 'primevue/progressspinner';
</script>
