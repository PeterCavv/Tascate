<script setup>

import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: MainLayoutTemp,
});

defineProps({
    users: {
        type: Array,
        required: true,
    },
    authUserId: {
        type: [Number, null],
        required: false,
    },
});
</script>

<template>
    <div class="flex gap-4">
        <Link
            v-for="user in users"
            :key="user.id"
            :href="`/users/${user.id}`"
            class="border border-gray-300 rounded-lg p-4 bg-gray-100 w-52 text-center hover:bg-gray-200 transition"
        >
            <h3 class="text-lg font-semibold">{{ user.name }}</h3>
            <p class="text-sm text-gray-600">{{ user.email }}</p>
            <p class="text-sm text-gray-600">{{ user.role }}</p>
            <p v-if="authUserId === user.id" class="text-green-600 font-bold">Este es tu usuario</p>
        </Link>
    </div>
</template>

<style scoped>

</style>
