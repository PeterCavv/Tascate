<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const { t } = useI18n();

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <main class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <section aria-labelledby="register-heading" class="space-y-4">
                <h2 id="register-heading" class="sr-only">Register</h2>

                <form
                    @submit.prevent="submit"
                    class="p-2 space-y-2 w-full max-w-sm mx-auto"
                    aria-label="Registration form"
                >
                    <!-- Name -->
                    <div class="p-1"></div>
                    <div class="space-y-1">
                        <FloatLabel>
                            <InputText
                                id="name"
                                type="text"
                                v-model="form.name"
                                class="w-full"
                                autocomplete="name"
                                autofocus
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.name"
                                aria-describedby="name-error"
                            />
                            <label for="name">
                                {{ t('messages.user_data.name') }}
                            </label>
                        </FloatLabel>
                        <small
                            v-if="form.errors.name"
                            id="name-error"
                            class="p-error block mt-1 text-xs"
                            role="alert"
                        >
                            {{ form.errors.name }}
                        </small>
                    </div>

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
                                required
                                aria-required="true"
                                aria-invalid="!!form.errors.email"
                                aria-describedby="email-error"
                            />
                            <label for="email">
                                {{ t('messages.user_data.email') }}
                            </label>
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
                            <label for="password">
                                {{ t('messages.auth.password') }}
                            </label>
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
                            <label for="password_confirmation">
                                {{ t('messages.auth.confirm_password') }}
                            </label>
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
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-3">
                        <Link
                            :href="route('login')"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline w-full sm:w-auto text-center"
                            aria-label="Go to login page"
                        >
                            {{ t('messages.auth.already_registered') }}
                        </Link>

                        <Link
                            :href="route('tascas-proposals.create')"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            ¿Quieres registrar una Tasca?
                        </Link>

                        <Button
                            type="submit"
                            :label="t('messages.auth.register_button')"
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
