<script setup>

import { Head } from '@inertiajs/vue3';

import { Link } from '@inertiajs/vue3';

</script>

<template>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <nav class="flex flex-col p-4 space-y-2">

                <Link
                    href="/"
                    class="flex items-center px-4 py-2 text-lg font-semibold text-white hover:bg-gray-700 transition"
                >
                    Welcome
                </Link>

                <Link
                    href=""
                    class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                    About Us
                </Link>
                <Link
                    href="/users"
                    class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                    Usuarios
                </Link>
                <Link
                    href="/posts"
                    class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                    Posts
                </Link>
                <Link
                    v-if="!$page.props.auth.user"
                    href="/login"
                    class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                    Login
                </Link>
                <Link
                    v-if="!$page.props.auth.user"
                    href="/register"
                    class="block px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                    Registro
                </Link>
                <Link
                    v-else
                    href="/logout"
                    method="post"
                    as="button"
                    class="block px-4 py-2 rounded hover:bg-gray-700 transition text-red-600"
                >
                    Logout
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->

        <main class="flex-1 p-6">
            <div v-if="$page.props.flash.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ $page.props.flash.success }}
            </div>
            <slot />
        </main>
    </div>
</template>

<style scoped>

</style>
