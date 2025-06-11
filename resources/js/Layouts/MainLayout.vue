<script setup>
import { Link, router } from '@inertiajs/vue3'
import {ref, onMounted, onUnmounted, watch} from 'vue'
import Loading from "@/Components/Loading.vue";
import { usePage } from '@inertiajs/vue3';
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const page = usePage()
const toast = useToast()
const {t} = useI18n();

const sidebarOpen = ref(false);
const visible = ref(false);
const isLoading = ref(false);
const isSidebarCollapsed = ref(false)

let destroyed = false

const start = () => {
    if (!destroyed) isLoading.value = true
}
const finish = () => {
    if (!destroyed) isLoading.value = false
}

onMounted(() => {
    destroyed = false
    router.on('start', start)
    router.on('finish', finish)
    router.on('error', finish)
})

onUnmounted(() => {
    destroyed = true
})

function showModal() {
    visible.value = true
}

const toastControl = (toastMessage) => {
    console.log(toastMessage);
    if (toastMessage) {
        toast.add({
            severity: toastMessage.severity ?? 'success',
            summary: toastMessage.summary ?? t('messages.toast.success'),
            detail: toastMessage.detail ?? '',
            life: 3000,
        })
    }
}

watch(
    () => page.props.toast,
    (toastMessage) => {
        console.log(toastMessage);
        toastControl(toastMessage);
    },
    { immediate: true }
)
</script>

<template>
    <transition name="fade">
        <Loading v-if="isLoading"/>
    </transition>

    <div class="flex h-screen overflow-hidden bg-gray-50">
        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-500 ease-bounce',
                isSidebarCollapsed ? 'w-20' : 'w-64',
                'bg-white/90 backdrop-blur-md shadow-elegant',
                'md:translate-x-0 md:static md:inset-0'
            ]"
        >
            <!-- Logo and Toggle -->
            <div class="p-4 flex items-center justify-center border-b border-gray-200">
                <button
                    @click="isSidebarCollapsed = !isSidebarCollapsed"
                    class="focus:outline-none hover:scale-105 transition-transform duration-300 ease-bounce"
                >
                    <ApplicationLogo
                        :class="[
                            'transition-all duration-500 ease-bounce transform',
                            isSidebarCollapsed
                                ? 'scale-75 opacity-80 rotate-0'
                                : 'scale-100 opacity-100 rotate-[2deg]',
                            'w-13 h-13 mx-auto'
                        ]"
                    />
                </button>
            </div>

            <!--            <div class=" p-3 flex items-center justify-center border-b border-gray-200">-->
<!--                <button @click="isSidebarCollapsed = !isSidebarCollapsed" class="focus:outline-none">-->
<!--                    <ApplicationLogo class="w-14 h-14 mx-auto" />-->
<!--                </button>-->
<!--            </div>-->

<!--            <div class="p-3 flex items-center justify-center border-b border-gray-200">-->
<!--                <button @click="isSidebarCollapsed = !isSidebarCollapsed" class="focus:outline-none">-->
<!--                    <ApplicationLogo-->
<!--                        :class="[-->
<!--                'transition-all duration-300 ease-in-out',-->
<!--                isSidebarCollapsed ? 'w-14 h-14 mx-auto' : 'w-13 h-13 mx-auto'-->
<!--            ]"-->
<!--                    />-->
<!--                </button>-->
<!--            </div>-->

            <!--            <div class="p-4 flex items-center justify-between border-b border-gray-200">-->
<!--                <Link href="/" class="flex items-center space-x-3">-->
<!--                    <ApplicationLogo class="w-15 h-15" />-->
<!--                    <span v-if="!isSidebarCollapsed" class="text-lg font-semibold text-gray-900">Tascate</span>-->
<!--                </Link>-->
<!--                <button-->
<!--                    @click="isSidebarCollapsed = !isSidebarCollapsed"-->
<!--                    class="text-gray-600 hover:text-gray-900 transition-colors"-->
<!--                >-->
<!--                    <i :class="['pi', isSidebarCollapsed ? 'pi-angle-right' : 'pi-angle-left']"></i>-->
<!--                </button>-->
<!--            </div>-->

            <!--            <div class="p-4 flex items-center justify-between border-b border-gray-200">-->
<!--                <Link href="/" class="flex items-center space-x-3">-->
<!--                    <ApplicationLogo class="w-15 h-15" />-->
<!--                    <span v-if="!isSidebarCollapsed" class="text-lg font-semibold text-gray-900">Tascate</span>-->
<!--                </Link>-->
<!--                <button-->
<!--                    @click="isSidebarCollapsed = !isSidebarCollapsed"-->
<!--                    class="text-gray-600 hover:text-gray-900 transition-colors"-->
<!--                >-->
<!--                    <i :class="['pi', isSidebarCollapsed ? 'pi-angle-right' : 'pi-angle-left']"></i>-->
<!--                </button>-->
<!--            </div>-->

            <!-- Navigation -->
            <nav class="flex-1 space-y-2 p-4 overflow-y-auto custom-scrollbar">
                <!-- Common User Links -->
                <div v-if="!$page.props.auth?.is_tasca" class="space-y-2">
                    <Link
                        href="/tascas"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/tascas') }"
                    >
                        <i class="pi pi-list text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Tascas</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && !$page.props.auth.is_employee && !$page.props.auth.is_manager"
                        href="/tascas/favorites"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/tascas/favorites') }"
                    >
                        <i class="pi pi-star text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Tascas Guardadas</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.is_admin"
                        href="/users"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/users') }"
                    >
                        <i class="pi pi-users text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Usuarios</span>
                    </Link>

                    <Link
                        href="/posts"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/posts') }"
                    >
                        <i class="pi pi-comments text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Posts</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user"
                        href="/liked-posts"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/liked-posts') }"
                    >
                        <i class="pi pi-heart text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Posts Favoritos</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.is_admin"
                        href="/tascas-proposals"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/tascas-proposals') }"
                    >
                        <i class="pi pi-send text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Peticiones</span>
                    </Link>

                    <Link
                        v-if="($page.props.auth.user && $page.props.auth.is_admin) || ($page.props.auth.user && $page.props.auth.is_tasca) || ($page.props.auth.user && $page.props.auth.is_manager)"
                        href="/employees"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/employees') }"
                    >
                        <i class="pi pi-id-card text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Empleados</span>
                    </Link>

                    <Link
                        v-if="($page.props.auth.user && $page.props.auth.is_admin) || ($page.props.auth.user && $page.props.auth.is_tasca)"
                        href="/managers"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/managers') }"
                    >
                        <i class="pi pi-user-plus text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Managers</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.is_customer"
                        href="/reservations"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/reservations') }"
                    >
                        <i class="pi pi-calendar text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Reservas</span>
                    </Link>

                    <Link
                        href="/about"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/about') }"
                    >
                        <i class="pi pi-info-circle text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">About Us</span>
                    </Link>

                    <Link
                        v-if="!$page.props.auth.user"
                        href="/login"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/login') }"
                    >
                        <i class="pi pi-sign-in text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Login</span>
                    </Link>

                    <Link
                        v-if="!$page.props.auth.user"
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/register') }"
                    >
                        <i class="pi pi-user-plus text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Registro</span>
                    </Link>
                </div>

                <!-- Tasca Links -->
                <div v-else class="space-y-2">
                    <Link
                        :href="`/tascas/${$page.props.auth.user.tasca?.id}`"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith(`/tascas/${$page.props.auth.user.tasca?.id}`) }"
                    >
                        <i class="pi pi-home text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Mi Tasca</span>
                    </Link>

                    <Link
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/register') }"
                    >
                        <i class="pi pi-users text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Empleados</span>
                    </Link>

                    <Link
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md"
                        :class="{ 'bg-green-100 text-green-700': $page.url.startsWith('/register') }"
                    >
                        <i class="pi pi-box text-lg"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-3">Stock</span>
                    </Link>
                </div>

                <Link
                    v-if="$page.props.auth.user"
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex items-center px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-300 ease-bounce hover:scale-[1.02] hover:shadow-md w-full"
                >
                    <i class="pi pi-sign-out text-lg"></i>
                    <span v-if="!isSidebarCollapsed" class="ml-3">Logout</span>
                </Link>
            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-200">
                <Link
                    href="/accessibility"
                    class="flex items-center text-xs text-gray-500 hover:text-gray-700 transition-all duration-300 ease-bounce hover:scale-[1.02]"
                >
                    <i class="pi pi-info-circle"></i>
                    <span v-if="!isSidebarCollapsed" class="ml-2">Accesibilidad</span>
                </Link>

                <div v-if="$page.props.auth.impersonating" class="mt-2 text-xs text-yellow-600">
                    <div class="flex items-center">
                        <i class="pi pi-user"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-2">
                            {{ $page.props.auth.user.name }}
                        </span>
                    </div>
                    <Link
                        href="/impersonate/stop"
                        method="get"
                        as="button"
                        class="flex items-center mt-1 text-yellow-600 hover:text-yellow-700 transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        preserveState
                    >
                        <i class="pi pi-undo"></i>
                        <span v-if="!isSidebarCollapsed" class="ml-2">Volver</span>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-0 overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white/90 backdrop-blur-md shadow-elegant px-6 py-4 flex items-center justify-between md:hidden">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-600 hover:text-gray-800 focus:outline-none hover:scale-105 transition-transform duration-300 ease-bounce"
                >
                    <i class="pi pi-bars text-xl"></i>
                </button>
                <span class="font-semibold text-gray-800">Tascate</span>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <div
                    v-if="$page.props.flash.success"
                    class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl shadow-elegant animate-bounce-in"
                >
                    {{ $page.props.flash.success }}
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Base transitions */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.5s cubic-bezier(0.68, -0.6, 0.32, 1.6);
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s cubic-bezier(0.68, -0.6, 0.32, 1.6);
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Custom scrollbar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.1) transparent; /* Más transparente */
}

.custom-scrollbar::-webkit-scrollbar {
    width: 3px; /* Más pequeña */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.1); /* Más transparente */
    border-radius: 20px;
}
/* Elegant shadow */
.shadow-elegant {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

/* Bounce animations */
@keyframes bounce-in {
    0% {
        transform: scale(0.9);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-bounce-in {
    animation: bounce-in 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);
}

/* Custom easing */
.ease-bounce {
    transition-timing-function: cubic-bezier(0.68, -0.6, 0.32, 1.6);
}

/* Smooth overscroll behavior */
.overflow-y-auto {
    overscroll-behavior-y: contain;
    -webkit-overflow-scrolling: touch;
}
</style>
