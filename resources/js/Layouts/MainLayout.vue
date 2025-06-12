<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import {ref, onMounted, onUnmounted, watch, computed} from 'vue'
import Loading from "@/Components/Loading.vue";
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const page = usePage()
const toast = useToast()
const {t} = useI18n();

// Computed properties for auth state
const isAdmin = computed(() => page.props.auth?.is_admin)
const isTasca = computed(() => page.props.auth?.is_tasca)
const isManager = computed(() => page.props.auth?.is_manager)
const isEmployee = computed(() => page.props.auth?.is_employee)
const isCustomer = computed(() => page.props.auth?.is_customer)
const isAuthenticated = computed(() => !!page.props.auth?.user)
const currentUser = computed(() => page.props.auth?.user)
const currentUrl = computed(() => page.url)

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
<!--    <div class="flex h-screen overflow-hidden" :class="{-->
<!--        'bg-gray-50': !isAuthenticated || isCustomer,-->
<!--        'bg-green-50': isAdmin,-->
<!--        'bg-red-50': isTasca,-->
<!--        'bg-yellow-50': isManager,-->
<!--        'bg-blue-50': isEmployee-->
<!--    }">-->
        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-500 ease-bounce',
                isSidebarCollapsed ? 'w-20' : 'w-64',
                'backdrop-blur-md shadow-elegant',
                'md:translate-x-0 md:static md:inset-0',
                !isAuthenticated || isCustomer ? 'bg-white/90' : '',
                isAdmin ? 'bg-green-100/90' : '',
                isTasca ? 'bg-red-100/90' : '',
                isManager ? 'bg-yellow-100/90' : '',
                isEmployee ? 'bg-blue-100/90' : ''
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
                <div v-if="!isTasca" class="space-y-2">
                    <Link
                        href="/tascas"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/tascas'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/tascas'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/tascas'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/tascas'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/tascas'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/tascas'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/tascas'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/tascas'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/tascas'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/tascas')
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
                        v-if="isAuthenticated && !isEmployee && !isManager"
                        href="/tascas/favorites"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/tascas/favorites'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/tascas/favorites'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/tascas/favorites'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/tascas/favorites'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/tascas/favorites'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/tascas/favorites'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/tascas/favorites'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/tascas/favorites'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/tascas/favorites'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/tascas/favorites')
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
                        v-if="isAuthenticated && isAdmin"
                        href="/users"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/users'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/users'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/users'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/users'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/users'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/users'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/users'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/users'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/users'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/users')
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
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/posts'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/posts'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/posts'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/posts'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/posts'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/posts'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/posts'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/posts'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/posts'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/posts')
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
                        v-if="isAuthenticated"
                        href="/liked-posts"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/liked-posts'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/liked-posts'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/liked-posts'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/liked-posts'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/liked-posts'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/liked-posts'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/liked-posts'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/liked-posts'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/liked-posts'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/liked-posts')
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
                        v-if="isAuthenticated && isAdmin"
                        href="/tascas-proposals"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/tascas-proposals'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/tascas-proposals'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/tascas-proposals'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/tascas-proposals'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/tascas-proposals'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/tascas-proposals'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/tascas-proposals'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/tascas-proposals'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/tascas-proposals'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/tascas-proposals')
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
                        v-if="(isAuthenticated && isAdmin) || (isAuthenticated && isTasca) || (isAuthenticated && isManager)"
                        href="/employees"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/employees'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/employees'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/employees'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/employees'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/employees'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/employees'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/employees'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/employees'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/employees'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/employees')
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
                        v-if="(isAuthenticated && isAdmin) || (isAuthenticated && isTasca)"
                        href="/managers"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/managers'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/managers'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/managers'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/managers'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/managers'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/managers'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/managers'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/managers'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/managers'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/managers')
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
                        v-if="isAuthenticated && isCustomer"
                        href="/reservations"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/reservations'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/reservations'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/reservations'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/reservations'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/reservations'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/reservations'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/reservations'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/reservations'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/reservations'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/reservations')
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
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/about'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/about'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/about'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/about'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/about'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/about'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/about'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/about'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/about'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/about')
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
                        v-if="!isAuthenticated"
                        href="/login"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/login'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/login'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/login'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/login'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/login'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/login'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/login'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/login'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/login'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/login')
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
                        v-if="!isAuthenticated"
                        href="/register"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/register'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/register'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/register'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/register'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/register'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/register'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/register'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/register'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/register'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/register')
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
                        :href="`/tascas/${currentUser?.tasca?.id}`"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith(`/tascas/${currentUser?.tasca?.id}`)
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
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/register'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/register'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/register'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/register'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/register'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/register'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/register'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/register'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/register'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/register')
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
                        href="/gestion"
                        class="flex items-center px-4 py-3 rounded-xl text-gray-600 bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-200/70 text-green-900 hover:bg-green-300/70': isAdmin && currentUrl.startsWith('/register'),
                            'bg-red-200/70 text-red-900 hover:bg-red-300/70': isTasca && currentUrl.startsWith('/register'),
                            'bg-yellow-200/70 text-yellow-900 hover:bg-yellow-300/70': isManager && currentUrl.startsWith('/register'),
                            'bg-blue-200/70 text-blue-900 hover:bg-blue-300/70': isEmployee && currentUrl.startsWith('/register'),
                            'bg-green-100/70 text-green-800 hover:bg-green-200/70': isAdmin && !currentUrl.startsWith('/register'),
                            'bg-red-100/70 text-red-800 hover:bg-red-200/70': isTasca && !currentUrl.startsWith('/register'),
                            'bg-yellow-100/70 text-yellow-800 hover:bg-yellow-200/70': isManager && !currentUrl.startsWith('/register'),
                            'bg-blue-100/70 text-blue-800 hover:bg-blue-200/70': isEmployee && !currentUrl.startsWith('/register'),
                            'bg-gray-100/70 text-gray-800 hover:bg-gray-200/70': (!isAuthenticated || isCustomer) && currentUrl.startsWith('/register'),
                            'hover:bg-gray-200/50 hover:text-gray-900': (!isAuthenticated || isCustomer) && !currentUrl.startsWith('/register')
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
                    v-if="isAuthenticated"
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex items-center px-4 py-3 rounded-xl bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02] w-full"
                    :class="{
                        'text-red-600 hover:bg-red-50 hover:text-red-700': !isTasca,
                        'text-orange-600 hover:bg-orange-50 hover:text-orange-700': isTasca
                    }"
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
