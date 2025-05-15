<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
<!--    <div-->
<!--        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"-->
<!--    >-->
<!--        <div>-->
<!--            <Link href="/">-->
<!--                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />-->
<!--            </Link>-->
<!--        </div>-->

<!--        <div-->
<!--            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"-->
<!--        >-->
<!--            <slot />-->
<!--        </div>-->
<!--    </div>-->
    <div
        class="w-screen h-[100vh] bg-cover bg-center bg-no-repeat flex items-center justify-center"
        style="background-image: url('/images/login_v3.svg');"
    >
        <div style="transform: translateX(-50vh) translateY(-10vh);">
                    <div>
                        <Link href="/">
<!--                            <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />-->
                            <ApplicationLogo style="transform: translateY(5vh);" />
<!--                            <img src="{{ asset('images/tascate.svg') }}" alt="Tascate" />-->

                        </Link>
                    </div>
            <slot />
        </div>
    </div>
<!--        <div-->
<!--            class="w-screen h-[100vh] bg-cover bg-center bg-no-repeat flex items-center justify-center"-->
<!--            style="background-image: url('/images/login_v2.svg');"-->
<!--        >-->
</template>
