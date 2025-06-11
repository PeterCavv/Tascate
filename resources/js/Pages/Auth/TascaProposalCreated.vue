<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {router} from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

const { t } = useI18n();

defineOptions({
    layout: MainLayoutTemp,
});

</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-lg w-full text-center space-y-6">
            <h1 class="text-2xl font-bold text-green-600">
                {{ t('messages.tasca_proposal.success') }}
            </h1>
            <p class="text-gray-700">
                {{ t('messages.tasca_proposal.success_message') }}
            </p>
            <Button
                @click="router.visit('/login')"
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
                <i class="pi pi-arrow-left"/>
                {{ t('messages.tasca_proposal.return_to_home') }}
            </Button>
        </div>
    </div>
</template>

