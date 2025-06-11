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

// Add a watcher to trigger the jelly effect when sidebar state changes
watch(isSidebarCollapsed, (newValue) => {
    if (!newValue) {
        // Add a small delay to ensure the text is visible before animation
        setTimeout(() => {
            const linkTexts = document.querySelectorAll('.link-text')
            linkTexts.forEach(text => {
                text.classList.remove('jelly')
                // Force a reflow
                void text.offsetWidth
                text.classList.add('jelly')
            })
        }, 50)
    }
})
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
            <div class="-p-5 flex items-center justify-center border-b border-gray-200 relative h-20 overflow-hidden">
                <button
                    @click="isSidebarCollapsed = !isSidebarCollapsed"
                    class="focus:outline-none hover:scale-105 transition-transform duration-300 ease-bounce relative w-full h-full"
                >
                    <img
                        src="/images/tascate.svg"
                        :class="[
                            'absolute w-24 h-24 transition-all duration-500 ease-bounce -mt-12',
                            isSidebarCollapsed ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-full'
                        ]"
                        alt="Tascate Logo"
                    />
                    <img
                        src="/images/tascate-letra-oscura-v3.svg"
                        :class="[
                            'absolute w-72 h-24 transition-all duration-500 ease-bounce -mt-11',
                            isSidebarCollapsed ? 'opacity-0 translate-y-full' : 'opacity-100 translate-y-0'
                        ]"
                        alt="Tascate Text"
                    />
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-2 p-4 overflow-y-auto custom-scrollbar">
                <!-- Common User Links -->
                <div v-if="!$page.props.auth?.is_tasca" class="space-y-2">
                    <Link
                        href="/tascas"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/tascas'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/tascas')
                        }"
                    >
                        <i class="pi pi-list text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Tascas</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && !$page.props.auth.is_employee && !$page.props.auth.is_manager"
                        href="/tascas/favorites"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/tascas/favorites'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/tascas/favorites')
                        }"
                    >
                        <i class="pi pi-bookmark text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Tascas Guardadas</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.is_admin"
                        href="/users"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/users'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/users')
                        }"
                    >
                        <i class="pi pi-users text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Usuarios</span>
                    </Link>

                    <Link
                        href="/posts"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/posts'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/posts')
                        }"
                    >
                        <i class="pi pi-comments text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Posts</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user"
                        href="/liked-posts"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/liked-posts'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/liked-posts')
                        }"
                    >
                        <i class="pi pi-heart text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Posts Favoritos</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.is_admin"
                        href="/tascas-proposals"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/tascas-proposals'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/tascas-proposals')
                        }"
                    >
                        <i class="pi pi-send text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Peticiones de Tascas</span>
                    </Link>

                    <Link
                        v-if="($page.props.auth.user && $page.props.auth.is_admin) || ($page.props.auth.user && $page.props.auth.is_tasca) || ($page.props.auth.user && $page.props.auth.is_manager)"
                        href="/employees"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/employees'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/employees')
                        }"
                    >
                        <i class="pi pi-hammer text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Empleados</span>
                    </Link>

                    <Link
                        v-if="($page.props.auth.user && $page.props.auth.is_admin) || ($page.props.auth.user && $page.props.auth.is_tasca)"
                        href="/managers"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/managers'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/managers')
                        }"
                    >
                        <i class="pi pi-bolt text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Managers</span>
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.is_customer"
                        href="/reservations"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/reservations'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/reservations')
                        }"
                    >
                        <i class="pi pi-calendar text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Reservas</span>
                    </Link>

                    <Link
                        href="/about"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/about'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/about')
                        }"
                    >
                        <i class="pi pi-info-circle text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>About Us</span>
                    </Link>

                    <Link
                        v-if="!$page.props.auth.user"
                        href="/login"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/login'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/login')
                        }"
                    >
                        <i class="pi pi-sign-in text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Login</span>
                    </Link>

                    <Link
                        v-if="!$page.props.auth.user"
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/register'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/register')
                        }"
                    >
                        <i class="pi pi-user-plus text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Registro</span>
                    </Link>
                </div>

                <!-- Tasca Links -->
                <div v-else class="space-y-2">
                    <Link
                        :href="`/tascas/${$page.props.auth.user.tasca?.id}`"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith(`/tascas/${$page.props.auth.user.tasca?.id}`),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith(`/tascas/${$page.props.auth.user.tasca?.id}`)
                        }"
                    >
                        <i class="pi pi-home text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Mi Tasca</span>
                    </Link>

                    <Link
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/register'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/register')
                        }"
                    >
                        <i class="pi pi-users text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Empleados</span>
                    </Link>

                    <Link
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-100/70 text-green-800 hover:bg-green-200': $page.url.startsWith('/register'),
                            'hover:bg-gray-200/50 hover:text-gray-900': !$page.url.startsWith('/register')
                        }"
                    >
                        <i class="pi pi-box text-lg"></i>
                        <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Stock</span>
                    </Link>
                </div>

                <Link
                    v-if="$page.props.auth.user"
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex items-center px-4 py-3 rounded-xl text-red-600 bg-transparent hover:bg-red-50 hover:text-red-700 transition-all duration-300 ease-bounce hover:scale-[1.02] w-full"
                >
                    <i class="pi pi-sign-out text-lg"></i>
                    <span
  v-show="!isSidebarCollapsed"
  class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
  :class="{
    'opacity-100 translate-x-0': !isSidebarCollapsed,
    'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
    'jelly': !isSidebarCollapsed
  }"
>Logout</span>
                </Link>
            </nav>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-200">
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

            <!-- User Profile Avatar -->
            <div v-if="$page.props.auth.user" class="absolute top-7 right-10 flex items-center space-x-2">
                <Link
                    :href="`/users/${$page.props.auth.user.id}`"
                    class="flex items-center space-x-2 hover:scale-105 transition-transform duration-300 ease-bounce"
                >
                    <div class="w-10 h-10 rounded-full bg-[#10B981] flex items-center justify-center text-white font-semibold text-lg shadow-elegant">
                        {{ $page.props.auth.user.name ? $page.props.auth.user.name.charAt(0).toUpperCase() : 'U' }}
                    </div>
                </Link>
            </div>

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

/* Jelly animation for links */
@keyframes jelly {
    0% { transform: scale(1); }
    20% { transform: scale(1.3); }
    40% { transform: scale(0.9); }
    70% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.link-text {
    display: inline-block;
    transform-origin: left center;
}

.link-text.jelly {
    animation: jelly 0.6s cubic-bezier(0.68, -0.6, 0.32, 1.6);
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

.ease-soft {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
