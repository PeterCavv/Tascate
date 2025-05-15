<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

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

        <div v-if="status" class="mb-4 text-green-600 text-sm font-medium">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="p-4 space-y-4 w-full max-w-md mx-auto">
            <div class="p-1"></div>

            <!-- Email -->
            <div>
                <FloatLabel >
                    <InputText
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full"
                        autocomplete="username"
                        autofocus
                        required
                    />
                    <label for="email">Username</label>
                </FloatLabel>
                <small v-if="form.errors.email" class="p-error block mt-1">{{ form.errors.email }}</small>
            </div>

            <div class="p-1"></div>

            <!-- Password -->
            <div>
                <FloatLabel>
                <InputText
                    id="password"
                    type="password"
                    v-model="form.password"
                    class="w-full"
                    autocomplete="current-password"
                    required
                />
                <label for="password">Password</label>
                </FloatLabel>
                <small v-if="form.errors.password" class="p-error block mt-1">{{ form.errors.password }}</small>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
                <Checkbox
                    inputId="remember"
                    v-model="form.remember"
                    :binary="true"
                />
                <label for="remember" class="text-sm text-gray-700">Remember me</label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-6">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-blue-600 hover:underline"
                >
                    Forgot your password?
                </Link>

                <Button
                    type="submit"
                    label="Log in"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </GuestLayout>
</template>
