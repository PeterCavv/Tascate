<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="p-4 space-y-4 w-full max-w-md mx-auto">
            <div class="p-1"></div>

            <!-- Email -->
            <div>
                <FloatLabel>
                    <InputText
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        autocomplete="username"
                        autofocus
                        required
                    />
                    <label for="email">Email</label>
                </FloatLabel>
                <small v-if="form.errors.email" class="p-error block mt-1">{{ form.errors.email }}</small>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6">
                <Button
                    type="submit"
                    label="Email Password Reset Link"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </GuestLayout>
</template>
