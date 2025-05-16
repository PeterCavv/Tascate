<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>

    <div
        class="w-screen h-[100vh] bg-cover bg-center bg-no-repeat flex items-center justify-center"
        style="background-image: url('/images/login_v5.svg');"
    >
        <div style="transform: translateX(-50vh) translateY(-10vh);">
                    <div>
                        <Link href="/">
                            <ApplicationLogo style="transform: translateY(5vh);" />

                        </Link>
                    </div>
            <slot />
        </div>
    </div>

</template>
