<script setup>

import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {router} from "@inertiajs/vue3";
import { defineProps } from 'vue';
import {Link} from "@inertiajs/vue3";
import 'primeicons/primeicons.css';


defineOptions({
    layout: MainLayoutTemp,
});

defineProps({
    posts: {
        type: Array,
        required: true,
    },
});

function toggleFavorite(post) {
    // Optimistic UI update
    post.is_favorite = !post.is_favorite;

    router.post(`/posts/${post.id}/toggle-like`, {}, {
        preserveScroll: true,
        onError: () => {
            // Revert in case of error
            post.is_favorite = !post.is_favorite;
        },
    });
}

</script>

<template>
    <h1 class="text-2xl font-bold">Lista de Posts</h1>
    <div class="mt-10">
        <Link
            v-if="$page.props.auth.user"
            href="/posts/create-post"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
            Crear Post
        </Link>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <div v-for="post in posts" :key="post.id" class="bg-white shadow-md rounded-md p-4">
            <img
                v-if="post.pictures.length > 0"
                :src="`/storage/${post.pictures[0].picture_path}`"
                alt="Imagen del Post"
                class="w-full h-48 object-cover rounded-md mb-4"
            />
            <h2 class="text-lg font-bold mb-2">{{ post.title }}</h2>
            <p class="text-gray-700 mb-4">{{ post.content }}</p>
            <div class="flex">
                <button
                    @click="toggleFavorite(post)"
                    :class="post.is_favorite ? 'text-red-500' : 'text-gray-500'"
                    class="focus:outline-none"
                >
                    <i :class="post.is_favorite ? 'pi pi-heart-fill' : 'pi pi-heart'"></i>
                </button>
                <span class="ml-2 text-sm text-gray-500">
                    {{ post.likes_count }} {{ post.likes_count === 1 ? 'like' : 'likes' }}
                </span>
            </div>
            <Link
                :href="`/posts/${post.id}`"
                class="text-blue-500 hover:underline"
            >
                Ver más
            </Link>
        </div>
    </div>

</template>

<style scoped>

</style>
