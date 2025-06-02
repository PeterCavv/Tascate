<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const { t } = useI18n();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <main class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <section aria-labelledby="login-heading" class="space-y-4">
                <h2 id="login-heading" class="sr-only">Log in</h2>

                <div
                    v-if="status"
                    class="mb-3 text-sm font-medium text-green-600 dark:text-green-400 text-center"
                    role="alert"
                    aria-live="polite"
                >
                    {{ status }}
                </div>

                <form
                    @submit.prevent="submit"
                    class="p-2 space-y-2 w-full max-w-sm mx-auto"
                    aria-label="Login form"
                >
                    <!-- Email -->
                    <div class="p-1"></div>
                    <div class="space-y-1">
                        <FloatLabel>
                            <InputText
                                id="email"
                                type="email"
                                v-model="form.email"
                                class="w-full dark:text-gray-100 dark:placeholder-gray-400"
                                autocomplete="username"
                                autofocus
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.email"
                                aria-describedby="email-error"
                            />
                            <label for="email" class="dark:text-gray-300">
                                {{ t('messages.user_data.email') }}
                            </label>
                        </FloatLabel>
                        <small
                            v-if="form.errors.email"
                            id="email-error"
                            class="p-error block mt-1 text-xs dark:text-red-300"
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
                                class="w-full dark:text-gray-100 dark:placeholder-gray-400"
                                autocomplete="current-password"
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.password"
                                aria-describedby="password-error"
                            />
                            <label for="password" class="dark:text-gray-300">
                                {{ t('messages.auth.password') }}
                            </label>
                        </FloatLabel>
                        <small
                            v-if="form.errors.password"
                            id="password-error"
                            class="p-error block mt-1 text-xs dark:text-red-300"
                            role="alert"
                        >
                            {{ form.errors.password }}
                        </small>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2">
                        <Checkbox
                            inputId="remember"
                            v-model="form.remember"
                            :binary="true"
                            aria-label="Remember me"
                        />
                        <label
                            for="remember"
                            class="text-xs text-gray-700 dark:text-gray-300"
                        >
                            {{ t('messages.auth.remember') }}
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] items-center gap-3 mt-3">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline text-center sm:text-left"
                            aria-label="Reset your password"
                        >
                            {{ t('messages.auth.forgot_password') }}
                        </Link>

                        <Button
                            type="submit"
                            :label="t('messages.auth.login_button')"
                            :loading="form.processing"
                            :disabled="form.processing"
                            size="small"
                            aria-busy="form.processing"
                        />
                    </div>
                </form>
            </section>
        </main>
    </GuestLayout>
</template>
