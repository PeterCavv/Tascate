<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const sidebarOpen = ref(false);

</script>

<template>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <transition name="slide">
            <aside
                :class="[
                    'fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 text-white p-4 flex flex-col transform transition-transform duration-300 ease-in-out',
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                    'md:translate-x-0 md:static md:inset-0'
                  ]"
            >
                <nav class="flex-1 space-y-2 overflow-y-auto">
                    <Link href="/" class="flex items-center px-4 py-2 text-lg font-semibold hover:bg-gray-700 transition">Welcome</Link>
                    <Link href="/tascas" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Tascas</Link>
                    <Link href="/users" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Usuarios</Link>
                    <Link href="/posts" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Posts</Link>
                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.user.role === 'customer'"
                        href="/reservations"
                        class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                    >
                        Mis Reservas
                    </Link>
                    <Link href="" class="block px-4 py-2 rounded hover:bg-gray-700 transition">About Us</Link>
                    <Link v-if="!$page.props.auth.user" href="/login" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Login</Link>
                    <Link v-if="!$page.props.auth.user" href="/register" class="block px-4 py-2 rounded hover:bg-gray-700 transition">Registro</Link>
                    <Link
                        v-else
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
                    </div>
                </nav>
            </aside>
        </transition>

        <!-- Main Content -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-40 z-20 md:hidden"
        ></div>
        <div class="flex-1 flex flex-col w-0 overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-md px-4 py-3 flex items-center justify-between md:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <!-- icono hamburguesa -->
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

