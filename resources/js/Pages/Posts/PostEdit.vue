<script setup>

import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";

defineOptions({
    layout: MainLayoutTemp,
});

const { post } = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: post.title || "",
    content: post.content || "",
});
</script>

<template>
    <div class="post-edit">
        <h1 class="text-3xl font-bold mb-6">Edit Post</h1>
        <form @submit.prevent="form.patch(route('posts.update', post.id))" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input
                    v-model="form.title"
                    id="title"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter the post title"
                />
                <span v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</span>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea
                    v-model="form.content"
                    id="content"
                    rows="5"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter the post content"
                ></textarea>
                <span v-if="form.errors.content" class="text-red-600 text-sm">{{ form.errors.content }}</span>
            </div>

            <div class="flex justify-end">
                <Link
                    :href="route('posts.show', post.id)"
                    class="text-gray-600 hover:underline mr-4"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
