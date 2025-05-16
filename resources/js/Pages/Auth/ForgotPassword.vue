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

        <div class="mb-3 text-sm text-gray-600 leading-relaxed max-w-md mx-auto text-center">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-3 text-sm font-medium text-green-600 text-center"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="p-2 space-y-2 w-full max-w-sm mx-auto">
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
                <small v-if="form.errors.email" class="p-error block mt-1 text-xs">{{ form.errors.email }}</small>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-3">
                <Button
                    type="submit"
                    label="Email Password Reset Link"
                    :loading="form.processing"
                    :disabled="form.processing"
                    size="small"
                />
            </div>
        </form>
    </GuestLayout>
</template>
