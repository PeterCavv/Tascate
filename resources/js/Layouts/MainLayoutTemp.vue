<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const sidebarOpen = ref(false);

const visible = ref(false)

function showModal() {
    visible.value = true
}

</script>

<template>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <transition name="slide">
            <aside
                :class="[
                    'fixed inset-y-0 left-0 z-30 w-64 text-white p-4 flex flex-col transform transition-transform duration-300 ease-in-out',
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                    $page.props.auth?.is_tasca ? 'bg-blue-950' : 'bg-gray-800',
                    'md:translate-x-0 md:static md:inset-0'
                  ]"
            >
                <nav class="flex-1 space-y-2 overflow-y-auto">
                    <div class="flex items-center space-x-4">
                        <Link href="/" class="flex items-center px-4 py-2 text-lg font-semibold hover:bg-gray-700 transition">
                            Welcome
                        </Link>

                        <Link
                            v-if="$page.props.auth.user"
                            :href="`/users/${$page.props.auth.user.id}`"
                            class="block px-4 py-2 underline hover:text-gray-300 transition text-xs"
                        >
                            Ver Perfil
                        </Link>
                    </div>

                    <!-- Sidebar Links Common User -->
                    <div v-if="!$page.props.auth?.is_tasca">
                        <Link href="/tascas" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Tascas</Link>
                        <Link
                            v-if="$page.props.auth.user"
                            href="/tascas/favorites"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Tascas guardadas
                        </Link>
                        <Link
                            v-if="$page.props.auth.user && $page.props.auth.is_admin"
                            href="/users"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Usuarios
                        </Link>
                        <Link href="/posts" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Posts</Link>
                        <Link
                            v-if="$page.props.auth.user"
                            href="/liked-posts"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Posts Favoritos
                        </Link>
                        <Link
                            v-if="$page.props.auth.user && $page.props.auth.is_admin"
                            href="/tascas-proposals"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Peticiones de Tascas
                        </Link>
                        <Link
                            v-if="($page.props.auth.user && $page.props.auth.is_admin) ||
                                    ($page.props.auth.user && $page.props.auth.is_tasca) ||
                                    ($page.props.auth.user && $page.props.auth.is_manager)"
                            href="/employees"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Empleados
                        </Link>
                        <Link
                            v-if="($page.props.auth.user && $page.props.auth.is_admin) ||
                                ($page.props.auth.user && $page.props.auth.is_tasca)"
                            href="/managers"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Managers
                        </Link>
                        <Link
                            v-if="$page.props.auth.user && $page.props.auth.is_customer"
                            href="/reservations"
                            class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                        >
                            Mis Reservas
                        </Link>
                        <Link href="" class="block px-4 py-2 rounded hover:bg-gray-700 transition">About Us</Link>
                        <Link v-if="!$page.props.auth.user" href="/login" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Login</Link>
                        <Link v-if="!$page.props.auth.user" href="/register" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Registro</Link>
                    </div>

                    <!-- Sidebar Links Tasca -->
                    <div v-else>
                        <Link :href="`/tascas/${$page.props.auth.user.tasca?.id}`" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Mi Tasca</Link>
                        <Link href="/register" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Empleados</Link>
                        <Link href="/register" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Gestión Stock</Link>
                    </div>

                    <Link
                        v-if="$page.props.auth.user"
                        href="/logout"
                        method="post"
                        as="button"
                        class="block px-4 py-2 rounded hover:bg-gray-700 transition text-red-600"
                    >
                        Logout
                    </Link>

                    <div class="mt-auto pt-4 border-t border-gray-700">
                        <Link href="/accessibility" class="block text-xs text-gray-400 hover:text-gray-200 hover:underline transition">
                            Declaración de accesibilidad
                        </Link>
                        <div v-if="$page.props.auth.impersonating" class="py-2 block text-xs text-yellow-400">
                            Impersonificando a <strong>{{ $page.props.auth.user.name }}</strong>
                            <Link
                                href="/impersonate/stop"
                                method="get"
                                as="button"
                                class="underline block text-xs text-yellow-400 hover:text-yellow-200 hover:underline transition"
                                preserveState
                            >Volver a mi cuenta</Link>
                        </div>
                    </div>
                </nav>
            </aside>
        </transition>

        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-40 z-20 md:hidden"
        ></div>
        <div class="flex-1 flex flex-col w-0 overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-md px-4 py-3 flex items-center justify-between md:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-800 focus:outline-none">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="font-semibold text-gray-800">Tascate</span>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ $page.props.flash.success }}
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}
</style>

