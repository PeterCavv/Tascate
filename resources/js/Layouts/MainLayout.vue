<script setup>
import { Link, router } from '@inertiajs/vue3'
import {ref, onMounted, onUnmounted, watch, computed} from 'vue'
import Loading from "@/Components/Loading.vue";
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";
import {usePage} from "@inertiajs/vue3";
// import AppBreadcrumb from "@/Components/BreadCrumb.vue";

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

// Breadcrumb items based on current route
// const breadcrumbItems = computed(() => {
//     const path = page.url;
//     const segments = path.split('/').filter(Boolean);
//
//     const items = [];
//     let currentPath = '';
//
//     // Helper function to get model name from segment
//     const getModelName = (segment) => {
//         const modelMap = {
//             'employees': 'Employee',
//             'managers': 'Manager',
//             'tascas': 'Tasca',
//             'users': 'User',
//             'posts': 'Post',
//             'reservations': 'Reservation'
//         };
//         return modelMap[segment] || segment;
//     };
//
//     // Helper function to get display name from props
//     const getDisplayName = (segment, index) => {
//         const props = page.props;
//
//         // Check if we have the specific item in props
//         if (props[segment] && props[segment].name) {
//             return props[segment].name;
//         }
//
//         // Check if we have a collection of items
//         if (props[segment + 's'] && Array.isArray(props[segment + 's'])) {
//             const item = props[segment + 's'].find(item => item.id === parseInt(segments[index + 1]));
//             if (item && item.name) {
//                 return item.name;
//             }
//         }
//
//         // If no name found, return the model name
//         return getModelName(segment);
//     };
//
//     segments.forEach((segment, index) => {
//         currentPath += `/${segment}`;
//
//         // If this is an ID segment (numeric) and we have a previous segment
//         if (!isNaN(segment) && index > 0) {
//             const previousSegment = segments[index - 1];
//             const displayName = getDisplayName(previousSegment, index - 1);
//             items.push({
//                 label: displayName,
//                 route: currentPath
//             });
//         } else {
//             // For non-ID segments, capitalize and format
//             items.push({
//                 label: segment.charAt(0).toUpperCase() + segment.slice(1).replace(/-/g, ' '),
//                 route: currentPath
//             });
//         }
//     });
//
//     return items;
// });
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
                !isAuthenticated || isCustomer ? 'bg-white/50' : '',
                isAdmin ? 'bg-green-100/50' : '',
                isTasca ? 'bg-blue-100/50' : '',
                isManager ? 'bg-yellow-100/50' : '',
                isEmployee ? 'bg-yellow-100/50' : ''
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
                        v-tooltip="isSidebarCollapsed ? 'Tascas' : null"
                        href="/tascas"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/tascas'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/tascas'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/tascas'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/tascas'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Tascas Guardadas' : null"
                        v-if="isAuthenticated && !isEmployee && !isManager"
                        href="/tascas/favorites"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/tascas/favorites'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/tascas/favorites'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/tascas/favorites'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/tascas/favorites'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Usuarios' : null"
                        v-if="isAuthenticated && isAdmin"
                        href="/users"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/users'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/users'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/users'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/users'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Posts' : null"
                        href="/posts"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/posts'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/posts'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/posts'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/posts'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Posts Favoritos' : null"
                        v-if="isAuthenticated"
                        href="/liked-posts"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/liked-posts'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/liked-posts'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/liked-posts'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/liked-posts'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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

                        >
                            Posts Favoritos
                        </span>

                    </Link>

                    <Link
                        v-tooltip="isSidebarCollapsed ? 'Propuestas de Tascas' : null"
                        v-if="isAuthenticated && isAdmin"
                        href="/tascas-proposals"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/tascas-proposals'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/tascas-proposals'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/tascas-proposals'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/tascas-proposals'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        >
                            Peticiones de Tascas
                        </span>

                    </Link>

                    <Link
                        v-tooltip="isSidebarCollapsed ? 'Empleados' : null"
                        v-if="(isAuthenticated && isAdmin) || (isAuthenticated && isTasca) || (isAuthenticated && isManager)"
                        href="/employees"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/employees'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/employees'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/employees'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/employees'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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

                        >
                        Empleados
                      </span>

                    </Link>

                    <Link
                        v-tooltip="isSidebarCollapsed ? 'Managers' : null"
                        v-if="(isAuthenticated && isAdmin) || (isAuthenticated && isTasca)"
                        href="/managers"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/managers'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/managers'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/managers'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/managers'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Reservas' : null"
                        v-if="isAuthenticated && isCustomer"
                        href="/reservations"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/reservations'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/reservations'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/reservations'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/reservations'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Perfil' : null"
                        v-if="$page.props.auth.user"
                        :href="`/users/${$page.props.auth.user.id}`"

                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === (`/users/${$page.props.auth.user.id}`),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === (`/users/${$page.props.auth.user.id}`),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === (`/users/${$page.props.auth.user.id}`),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === (`/users/${$page.props.auth.user.id}`),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
                        }"
                    >
                        <i class="pi pi-user text-lg"></i>
                        <span
                          v-show="!isSidebarCollapsed"
                          class="ml-3 transition-all duration-300 ease-soft text-sm inline-block opacity-0 translate-x-[-10px] link-text"
                          :class="{
                            'opacity-100 translate-x-0': !isSidebarCollapsed,
                            'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed,
                            'jelly': !isSidebarCollapsed
                          }"
                        >Perfil</span>

                    </Link>

                    <Link
                        v-tooltip="isSidebarCollapsed ? 'Login' : null"
                        v-if="!isAuthenticated"
                        href="/login"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/login'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/login'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/login'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/login'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Empleados' : null"
                        v-if="!isAuthenticated"
                        href="/employees"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/employees'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/employees'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/employees'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/employees'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Mi Tasca' : null"
                        :href="`/tascas/${currentUser?.tasca?.id}`"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === (`/tascas/${currentUser?.tasca?.id}`),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === (`/tascas/${currentUser?.tasca?.id}`),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === (`/tascas/${currentUser?.tasca?.id}`),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === (`/tascas/${currentUser?.tasca?.id}`),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'Empleados' : null"
                        href="/employees"

                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/employees'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/employees'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/employees'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/employees'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                        v-tooltip="isSidebarCollapsed ? 'stock' : null"
                        href="/gestion"

                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 ease-bounce hover:scale-[1.02]"
                        :class="{
                            'bg-green-300/70 text-green-900': isAdmin && currentUrl === ('/gestion'),
                            'bg-blue-300/70 text-blue-900': isTasca && currentUrl === ('/gestion'),
                            'bg-yellow-300/70 text-yellow-900': (isManager || isEmployee) && currentUrl === ('/gestion'),
                            'bg-gray-300/70 text-gray-900': (!isAuthenticated || isCustomer) && currentUrl === ('/gestion'),
                            'text-green-600': isAdmin,
                            'text-blue-600': isTasca,
                            'text-yellow-700': isManager || isEmployee,
                            'text-gray-600': !isAuthenticated || isCustomer,
                            'hover:bg-green-200/50 hover:text-green-900': isAdmin,
                            'hover:bg-blue-200/50 hover:text-blue-900': isTasca,
                            'hover:bg-yellow-200/50 hover:text-yellow-900': isManager || isEmployee,
                            'hover:bg-gray-200/50 hover:text-gray-900': !isAuthenticated || isCustomer
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
                    v-tooltip="isSidebarCollapsed ? 'Logout' : null"
                    v-if="isAuthenticated"
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex items-center px-4 py-3 rounded-xl bg-transparent transition-all duration-300 ease-bounce hover:scale-[1.02] w-full text-red-600 hover:bg-red-200 hover:text-red-700"
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
                    <span
                        class="ml-2 inline-block"
                        :class="{
                            'opacity-100 translate-x-0 transition-all duration-1000 ease-soft': !isSidebarCollapsed,
                            'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed
                        }"
                    >
                        Accesibilidad
                    </span>
                </Link>

                <div v-if="$page.props.auth.impersonating" class="mt-2 text-xs text-yellow-600">
                    <div class="flex items-center">
                        <i class="pi pi-user"></i>
                        <span
                            class="ml-2 inline-block"
                            :class="{
                                'opacity-100 translate-x-0 transition-all duration-1000 ease-soft': !isSidebarCollapsed,
                                'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed
                            }"
                        >
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
                        <span
                            class="ml-2 inline-block"
                            :class="{
                                'opacity-100 translate-x-0 transition-all duration-1000 ease-soft': !isSidebarCollapsed,
                                'opacity-0 -translate-x-2 pointer-events-none': isSidebarCollapsed
                            }"
                        >
                            Volver
                        </span>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-0 overflow-hidden">
            <!-- Breadcrumb and User Profile -->
<!--            <div-->
<!--                class="flex items-center justify-between px-6 py-4 transition-all duration-500 ease-out transform"-->
<!--                :class="{-->
<!--                // comentado temporalmente hasta que decida si se queda mejor con o sin el margen-->
<!--    // 'ml-20 scale-100': isSidebarCollapsed,-->
<!--    // 'ml-20 scale-[1.01]': !isSidebarCollapsed-->
<!--  }"-->
<!--            >-->
<!--                <AppBreadcrumb :items="breadcrumbItems" class="!bg-transparent" />-->

                <!-- User Profile Avatar -->
<!--                <div v-if="$page.props.auth.user" class="flex items-center space-x-2">-->
<!--                    <Link-->
<!--                        :href="`/users/${$page.props.auth.user.id}`"-->
<!--                        class="flex items-center space-x-2 hover:scale-105 transition-transform duration-300 ease-bounce"-->
<!--                    >-->
<!--                        <div-->
<!--                            class="w-10 h-10 rounded-full bg-[#10B981] flex items-center justify-center text-white font-semibold text-lg shadow-elegant"-->
<!--                        >-->
<!--                            {{ $page.props.auth.user.name ? $page.props.auth.user.name.charAt(0).toUpperCase() : 'U' }}-->
<!--                        </div>-->
<!--                    </Link>-->
<!--                </div>-->
<!--            </div>-->

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
    <Toast />
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
