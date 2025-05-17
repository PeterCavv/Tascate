<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="w-screen min-h-screen bg-cover bg-left bg-no-repeat flex items-center p-4 sm:p-10"
        style="background-image: url('/images/login_v5.svg');"
    >
        <!-- Contenedor principal alineado a la izquierda en desktop -->
        <div class="w-full max-w-xs mx-auto sm:mx-auto sm:ml-10 md:ml-20 lg:ml-32 xl:ml-40 text-center sm:text-left">
            <!-- Logo (centrado en móvil, alineado a la izquierda en desktop) -->
            <div class="flex justify-center sm:justify-start mb-106 sm:mb-4">
                <Link href="/" class="mx-auto sm:mx-0 w-full max-w-md">
                    <ApplicationLogo class="sm:translate-y-[15%] mx-auto sm:mx-0" />
                </Link>
            </div>

            <!-- Formulario (centrado en móvil, alineado a la izquierda en desktop) -->
            <div class="mx-auto sm:mx-0 w-full max-w-md bg-white/30 rounded-xl p-4 backdrop-blur-md">
                <slot/>
            </div>
        </div>
<!--        <div class="w-full max-w-5xl mx-auto sm:ml-10 md:ml-20 lg:ml-32 xl:ml-40 align-center">-->
<!--            &lt;!&ndash; Logo alineado a la izquierda en desktop, centrado en mobile &ndash;&gt;-->
<!--            <div class="flex justify-center sm:justify-start mb-6 sm:mb-4">-->
<!--                <Link href="/">-->
<!--                    <ApplicationLogo class="sm:translate-y-[15%]" />-->
<!--                </Link>-->
<!--            </div>-->

<!--            &lt;!&ndash; Formulario alineado a la izquierda &ndash;&gt;-->
<!--            <div class="w-full max-w-md bg-white/30 rounded-xl p-4 backdrop-blur-md">-->
<!--                <slot/>-->
<!--            </div>-->
<!--        </div>-->

        <!-- Iconos sociales (centrados en mobile, posición fija) -->
        <div class="fixed bottom-4 left-0 right-0 flex justify-center gap-6 sm:absolute sm:bottom-16 sm:left-1/2 sm:transform sm:-translate-x-1/2">
            <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="text-3xl text-yellow-400 hover:text-yellow-200 transition-transform duration-300 hover:scale-110">
                <i class="pi pi-github"></i>
            </a>
            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-3xl text-yellow-400 hover:text-yellow-200 transition-transform duration-300 hover:scale-110">
                <i class="pi pi-linkedin"></i>
            </a>
        </div>
    </div>
</template>

<!--<template>-->

<!--    <div-->
<!--        class="w-screen h-[100vh] bg-cover bg-left bg-no-repeat flex items-center justify-center"-->
<!--        style="background-image: url('/images/login_v5.svg');"-->
<!--    >-->
<!--        <div-->
<!--            class="-->
<!--            m-5-->
<!--            sm:m-10 md:m-20-->
<!--            transform-->
<!--            sm:-translate-x-[110%] sm:-translate-y-[10%]-->
<!--            sm:text-left-->
<!--            translate-x-0 translate-y-0-->
<!--            text-center-->
<!--          "-->
<!--        >-->
<!--            <div>-->
<!--                <Link href="/">-->
<!--                    <ApplicationLogo class="sm:translate-y-[15%] translate-y-10 mx-auto" />-->
<!--                </Link>-->
<!--            </div>-->
<!--            <div class=" max-w-md w-full bg-white/30 rounded-xl p-6 backdrop-blur-md ">-->

<!--            <slot/>-->
<!--        </div>-->
<!--        </div>-->
<!--        <div class="absolute bottom-16 left-1/2 transform -translate-x-1/2 flex gap-6">-->
<!--            <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="text-3xl text-white hover:text-yellow-400 transition-transform duration-300 hover:scale-110">-->
<!--                <i class="pi pi-github"></i>-->
<!--            </a>-->
<!--            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-3xl text-white hover:text-yellow-400 transition-transform duration-300 hover:scale-110">-->
<!--                <i class="pi pi-linkedin"></i>-->
<!--            </a>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->



<!--        <div style="transform: translateX(-110%) translateY(-10%);" class="m-20">-->
<!--                    <div>-->
<!--                        <Link href="/">-->
<!--                            <ApplicationLogo style="transform: translateY(5%);" />-->
<!--                        </Link>-->
<!--                    </div>-->
<!--            <slot />-->
<!--        </div>-->

<!--<template>-->
<!--    <div-->
<!--        class="w-screen min-h-screen bg-cover bg-left bg-no-repeat flex items-center justify-center relative p-4 sm:p-8"-->
<!--        style="background-image: url('/images/login_v5.svg');"-->
<!--    >-->
<!--        &lt;!&ndash; Contenedor del logo y contenido &ndash;&gt;-->
<!--        <div class="max-w-md w-full bg-white/70 rounded-xl p-6 backdrop-blur-md sm:translate-x-0 sm:translate-y-0 -translate-x-[50%] -translate-y-[10%] relative">-->
<!--            <div class="mb-6 flex justify-center">-->
<!--                <Link href="/">-->
<!--                    <ApplicationLogo style="transform: translateY(5%);" />-->
<!--                </Link>-->
<!--            </div>-->
<!--            <slot />-->
<!--        </div>-->

<!--        &lt;!&ndash; Íconos sociales en la parte inferior &ndash;&gt;-->
<!--        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-6">-->
<!--            <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="text-2xl sm:text-3xl text-white hover:text-yellow-400 transition-transform duration-300 hover:scale-110">-->
<!--                <i class="pi pi-github"></i>-->
<!--            </a>-->
<!--            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-2xl sm:text-3xl text-white hover:text-yellow-400 transition-transform duration-300 hover:scale-110">-->
<!--                <i class="pi pi-linkedin"></i>-->
<!--            </a>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->
