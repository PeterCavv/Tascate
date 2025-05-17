<script setup>

import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Link} from "@inertiajs/vue3";
import {useForm} from "@inertiajs/vue3";

const form = useForm();
defineOptions({
    layout: MainLayoutTemp,
});

const { post } = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

const deletePost = () => {
    form.delete(route('posts.destroy', post.id));
};


</script>

<template>

    <div class="post-show">
        <h1 class="text-3xl font-bold mb-4">{{ post.title }}</h1>
        <p class="text-gray-700 mb-6">{{ post.content }}</p>

        <div class="creator-info mb-6">
            <h2 class="text-xl font-semibold">Created by:</h2>
            <p class="text-gray-600">{{ post.user.name }}</p>

            <Link
                v-if="$page.props.auth && $page.props.auth.user && $page.props.auth.user.id === post.user_id"
                :href="`/posts/${post.id}/edit`"
                class="text-indigo-600 hover:underline"
            >
                Editar
            </Link>
            <button
                class="text-red-600 hover:underline ml-4"
                @click="deletePost"
            >
                Eliminar
            </button>
        </div>

        <div class="pictures">
            <h2 class="text-xl font-semibold mb-4">Pictures:</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <img
                    v-for="(picture, index) in post.pictures"
                    :key="index"
                    :src="`/storage/${picture.picture_path}`"
                    alt="Post Picture"
                    class="rounded-lg shadow-md"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
