<script setup>
import { Head } from '@inertiajs/vue3'
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import Tag from 'primevue/tag';

defineOptions({
    layout: MainLayoutTemp,
});
</script>
<template>
    <Head title="Accesibilidad"/>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-800">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Declaración de accesibilidad</h1>

        <section class="mb-8 space-y-4">
            <p>
                En <strong class="font-semibold">Tascate</strong>, nos comprometemos a ofrecer una experiencia digital accesible para todas las personas,
                incluidas aquellas con discapacidad. Nuestro objetivo es garantizar que todas las funcionalidades sean perceptibles,
                operables, comprensibles y robustas para el mayor número posible de usuarios.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Estado de accesibilidad</h2>
            <p>Este sitio web ha sido desarrollado siguiendo las pautas de accesibilidad <strong>WCAG 2.1 nivel AA</strong>.</p>
            <p class="mt-1">Actualmente, se encuentra en un estado de:
                <Tag severity="warn">En proceso de mejora</Tag>.</p>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Medidas adoptadas</h2>
            <ul class="list-disc pl-6 space-y-1">
                <li>Compatibilidad con lectores de pantalla.</li>
                <li>Navegación mediante teclado.</li>
                <li>Colores con contraste suficiente.</li>
                <li>Alternativas textuales en imágenes y controles.</li>
                <li>Evaluaciones periódicas con herramientas automáticas y pruebas manuales.</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Limitaciones conocidas</h2>
            <ul class="list-disc pl-6 space-y-1 text-sm text-gray-700">
                <li>Elementos interactivos sin etiquetas en algunas vistas.</li>
                <li>Algunos documentos PDF aún no están etiquetados correctamente.</li>
            </ul>
            <p class="mt-2">Estamos trabajando activamente para solucionarlas.</p>
        </section>

        <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Contacto</h2>
            <p>
                Si encuentras algún problema de accesibilidad o tienes sugerencias de mejora, por favor escríbenos a:
            </p>
            <p class="mt-1 text-blue-600 underline">📧 administracion_tascate@gmail.com</p>
        </section>

        <footer class="text-sm text-gray-500 mt-10">
            Última revisión: <time datetime="2025-05-19">19 de mayo de 2025</time>
        </footer>
    </div>
</template>
