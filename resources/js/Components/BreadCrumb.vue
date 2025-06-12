<!-- components/AppBreadcrumb.vue -->
<template>
    <Breadcrumb :home="home" :model="items">
        <template #item="{ item, props }">
            <Link v-if="item.route" :href="item.route" v-bind="props.action">
                <span :class="[item.icon, 'text-color']" />
                <span class="text-primary font-semibold">{{ item.label }}</span>
            </Link>
            <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
            </a>
        </template>
        <template #separator> / </template>
    </Breadcrumb>
</template>

<script setup>
import Breadcrumb from 'primevue/breadcrumb';
import { Link } from '@inertiajs/vue3';

defineProps({
    items: {
        type: Array,
        required: true
    }
});

const home = {
    icon: 'pi pi-home',
    route: '/'
};
</script>
