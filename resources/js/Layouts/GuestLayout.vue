<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="w-screen min-h-screen bg-cover bg-left bg-no-repeat flex items-center p-4 sm:p-10"
        style="background-image: url('/images/login_v5.svg');"
    >
        <div class="w-full max-w-xs mx-auto sm:mx-auto sm:ml-10 md:ml-20 lg:ml-32 xl:ml-40 text-center sm:text-left">
            <div class="flex justify-center sm:justify-start mb-106 sm:mb-4">
                <Link href="/" class="mx-auto sm:mx-0 w-full max-w-md transition-transform duration-300 hover:scale-110">
                    <ApplicationLogo class="sm:translate-y-[30%] mx-auto sm:mx-0" />
                </Link>
            </div>
            <div class="mx-auto sm:mx-0 w-full max-w-md bg-white/30 dark:bg-[#121212]/30 rounded-xl p-4 backdrop-blur-md">
                <slot/>
            </div>
        </div>

        <div class="fixed bottom-4 left-0 right-0 flex justify-center gap-6 sm:absolute sm:bottom-16 sm:left-1/2 sm:transform sm:-translate-x-1/2">
            <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="text-3xl text-yellow-400 hover:text-yellow-200 transition-transform duration-300 hover:scale-110 dark:text-yellow-300 dark:hover:text-yellow-600">
                <i class="pi pi-github"></i>
            </a>
            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-3xl text-yellow-400 hover:text-yellow-200 transition-transform duration-300 hover:scale-110 dark:text-yellow-300 dark:hover:text-yellow-600">
                <i class="pi pi-linkedin"></i>
            </a>
        </div>
    </div>
</template>
