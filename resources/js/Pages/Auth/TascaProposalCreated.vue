<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Link} from '@inertiajs/vue3';

defineOptions({
    layout: MainLayoutTemp,
});

</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-lg w-full text-center space-y-6">
            <h1 class="text-2xl font-bold text-green-600">¡Tasca propuesta para su creación!</h1>
            <p class="text-gray-700">
                Hemos recibido tu propuesta de tasca. Un administrador la revisará pronto. Si es aprobada, recibirás un correo con instrucciones para acceder a tu cuenta.
            </p>
            <Link
                href="/login"
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
                <i class="pi pi-arrow-left"/>
                Volver al login
            </Link>
        </div>
    </div>
</template>

