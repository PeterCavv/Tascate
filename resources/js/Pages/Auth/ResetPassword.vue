<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <main class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <section aria-labelledby="reset-password-heading" class="space-y-4">
                <h2 id="reset-password-heading" class="sr-only">Reset Password</h2>

                <form
                    @submit.prevent="submit"
                    class="p-2 space-y-2 w-full max-w-sm mx-auto"
                    aria-label="Password reset form"
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

                    <!-- Password -->
                    <div class="p-1"></div>
                    <div class="space-y-1">
                        <FloatLabel>
                            <InputText
                                id="password"
                                type="password"
                                v-model="form.password"
                                class="w-full"
                                autocomplete="new-password"
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.password"
                                aria-describedby="password-error"
                            />
                            <label for="password">Password</label>
                        </FloatLabel>
                        <small
                            v-if="form.errors.password"
                            id="password-error"
                            class="p-error block mt-1 text-xs"
                            role="alert"
                        >
                            {{ form.errors.password }}
                        </small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="p-1"></div>
                    <div class="space-y-1">
                        <FloatLabel>
                            <InputText
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                class="w-full"
                                autocomplete="new-password"
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.password_confirmation"
                                aria-describedby="password-confirmation-error"
                            />
                            <label for="password_confirmation">Confirm Password</label>
                        </FloatLabel>
                        <small
                            v-if="form.errors.password_confirmation"
                            id="password-confirmation-error"
                            class="p-error block mt-1 text-xs"
                            role="alert"
                        >
                            {{ form.errors.password_confirmation }}
                        </small>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end mt-3">
                        <Button
                            type="submit"
                            label="Reset Password"
                            :loading="form.processing"
                            :disabled="form.processing"
                            size="small"
                            class="w-full sm:w-auto"
                            aria-busy="form.processing"
                        />
                    </div>
                </form>
            </section>
        </main>
    </GuestLayout>
</template>
