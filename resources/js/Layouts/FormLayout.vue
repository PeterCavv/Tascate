<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="w-screen h-screen bg-cover bg-center flex items-center justify-center"
        style="background-image: url('/images/circles.svg'); background-size: 150%;"
    >
           <div class="w-full max-w-md mx-auto bg-white p-4 rounded-xl ">
               <slot />
           </div>
    </div>
</template>
