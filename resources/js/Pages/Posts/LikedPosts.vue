<script setup>

import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Link, router} from "@inertiajs/vue3";
import 'primeicons/primeicons.css';

defineOptions({
    layout: MainLayoutTemp,
});

defineProps({
    posts: {
        type: Array,
        required: true,
    },
    countLikes: {
        type: Number,
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
            v-for="post in posts"
            :key="post.id"
            class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300"
        >
            <img
                v-if="post.pictures && post.pictures.length"
                :src="`/storage/${post.pictures[0].picture_path}`"
                alt="Post Image"
                class="w-full h-48 object-cover"
            />
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-800 truncate">
                    {{ post.title }}
                </h2>
                <p class="text-sm text-gray-600 mt-2 line-clamp-3">
                    {{ post.content }}
                </p>
                <Link
                    :href="`/posts/${post.id}`"
                    class="text-blue-500 hover:underline"
                >
                    Ver más
                </Link>
                <button
                    @click="toggleFavorite(post)"
                    :class="post.is_favorite ? 'text-red-500' : 'text-gray-500'"
                    class="focus:outline-none"
                >
                    <i :class="post.is_favorite ? 'pi pi-heart-fill' : 'pi pi-heart'"></i>
                </button>
                <span class="ml-2 text-sm text-gray-500">
                    {{ post.like_count }} {{ post.likes_count === 1 ? 'like' : 'likes' }}
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
