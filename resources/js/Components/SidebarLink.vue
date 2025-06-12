<!--<template>-->
<!--    <Link-->
<!--        :href="href"-->
<!--        class="sidebar-link group flex items-center px-4 py-2 rounded-lg transition-colors duration-200"-->
<!--        :class="[-->
<!--            isActive ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-gray-700',-->
<!--            collapsed ? 'justify-center' : 'justify-start'-->
<!--        ]"-->
<!--    >-->
<!--        <i :class="[icon, 'text-lg', { 'mr-3': !collapsed }]"></i>-->
<!--        <span v-if="!collapsed" class="text-sm">{{ label }}</span>-->
<!--        <div-->
<!--            v-if="collapsed"-->
<!--            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-sm rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200"-->
<!--        >-->
<!--            {{ label }}-->
<!--        </div>-->
<!--    </Link>-->
<!--</template>-->

<!--<script setup lang="ts">-->
<!--import { Link } from '@inertiajs/vue3'-->
<!--import { computed } from 'vue'-->
<!--import { usePage } from '@inertiajs/vue3'-->

<!--const props = defineProps<{-->
<!--    href: string-->
<!--    label: string-->
<!--    icon: string-->
<!--    collapsed?: boolean-->
<!--}>()-->

<!--const page = usePage()-->
<!--// const isActive = computed(() => page.url.startsWith(props.href))-->
<!--</script>-->

<!--<style scoped>-->
<!--.sidebar-link {-->
<!--    position: relative;-->
<!--}-->
<!--</style>-->
