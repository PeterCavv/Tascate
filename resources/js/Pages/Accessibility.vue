<script setup>
import { Head } from '@inertiajs/vue3'
import Tag from 'primevue/tag';
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({
    layout: MainLayout,
});
</script>
<template>
    <Head title="Accesibilidad" />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-gray-800">
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:bg-blue-600 focus:text-white px-2 py-1 rounded"
        >
            Ir al contenido principal
        </a>

        <div class="bg-white shadow-xl rounded-2xl p-8 space-y-10 ring-1 ring-gray-200">
            <h1 id="page-title" class="text-4xl font-extrabold text-gray-900 mb-2">
                Declaración de accesibilidad
            </h1>
            <p class="text-gray-600 text-base">
                En <strong class="font-semibold text-green-600">Tascate</strong> nos comprometemos a ofrecer una experiencia
                digital inclusiva y accesible para todas las personas, incluidas aquellas con discapacidad.
            </p>

            <main id="main-content" aria-labelledby="page-title" class="space-y-10">
                <section aria-labelledby="status-heading" class="space-y-3">
                    <h2 id="status-heading" class="text-2xl font-semibold text-gray-800">Estado de accesibilidad</h2>
                    <p>
                        Este sitio web ha sido desarrollado siguiendo las pautas de accesibilidad
                        <strong class="text-green-600">WCAG 2.1 nivel AA</strong>.
                    </p>
                    <p>
                        Actualmente se encuentra en un estado de:
                        <Tag severity="info">Accesibilidad en desarrollo</Tag>
                    </p>
                    <p class="text-sm text-gray-600">
                        El sitio cumple con aproximadamente el 80% de los criterios aplicables. Estamos comprometidos con su mejora continua.
                    </p>
                </section>

                <section aria-labelledby="measures-heading" class="space-y-3">
                    <h2 id="measures-heading" class="text-2xl font-semibold text-gray-800">Medidas adoptadas</h2>
                    <ul class="list-disc pl-6 space-y-1 text-gray-700">
                        <li>Navegación mediante teclado.</li>
                        <li>Colores con contraste adecuado.</li>
                        <li>Alternativas textuales en imágenes y controles.</li>
                        <li>Evaluaciones periódicas con herramientas automáticas y pruebas manuales.</li>
                    </ul>
                </section>

                <section aria-labelledby="limitations-heading" class="space-y-3">
                    <h2 id="limitations-heading" class="text-2xl font-semibold text-gray-800">Limitaciones conocidas</h2>
                    <ul class="list-disc pl-6 text-sm text-gray-600">
                        <li>No compatible con lectores de pantalla en toda la web.</li>
                        <li>Elementos interactivos sin etiquetas en algunas vistas.</li>
                    </ul>
                    <p class="text-sm text-gray-600 mt-1">Estamos trabajando activamente para solucionarlas.</p>
                </section>

                <!-- Contacto -->
                <section aria-labelledby="contact-heading" class="space-y-2">
                    <h2 id="contact-heading" class="text-2xl font-semibold text-gray-800">Contacto</h2>
                    <p>
                        Si encuentras algún problema de accesibilidad o tienes sugerencias, escríbenos a:
                    </p>
                    <a
                        href="mailto:administracion_tascate@gmail.com"
                        class="text-blue-600 underline focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        📧 administracion_tascate@gmail.com
                    </a>
                </section>

                <footer class="text-sm text-gray-500 border-t pt-4 mt-6">
                    Última revisión: <time datetime="2025-05-19">13 de Junio de 2025</time>
                </footer>
            </main>
        </div>
    </div>
</template>


