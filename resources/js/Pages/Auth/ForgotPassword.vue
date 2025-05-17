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

        <main class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <section aria-labelledby="forgot-password-heading" class="space-y-4">
                <h2 id="forgot-password-heading" class="sr-only">Forgot Password</h2>

                <div
                    class="mb-3 text-sm text-gray-600 leading-relaxed max-w-md mx-auto text-center"
                    role="alert"
                    aria-live="polite"
                >
                    Forgot your password? No problem. Just let us know your email
                    address and we will email you a password reset link that will allow
                    you to choose a new one.
                </div>

                <div
                    v-if="status"
                    class="mb-3 text-sm font-medium text-green-600 text-center"
                    role="alert"
                    aria-live="polite"
                >
                    {{ status }}
                </div>

                <form
                    @submit.prevent="submit"
                    class="p-2 space-y-2 w-full max-w-sm mx-auto"
                    aria-label="Password reset request form"
                >
                    <!-- Email -->
                    <div class="p-1"></div>
                    <div class="space-y-1">
                        <FloatLabel>
                            <InputText
                                id="email"
                                type="email"
                                v-model="form.email"
                                class="w-full"
                                autocomplete="username"
                                autofocus
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.email"
                                aria-describedby="email-error"
                            />
                            <label for="email">Email</label>
                        </FloatLabel>
                        <small
                            v-if="form.errors.email"
                            id="email-error"
                            class="p-error block mt-1 text-xs"
                            role="alert"
                        >
                            {{ form.errors.email }}
                        </small>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end mt-3">
                        <Button
                            type="submit"
                            label="Email Password Reset Link"
                            :loading="form.processing"
                            :disabled="form.processing"
                            size="small"
                            class="w-full sm:w-full"
                            aria-busy="form.processing"
                        />
                    </div>
                </form>
            </section>
        </main>
    </GuestLayout>
</template>
