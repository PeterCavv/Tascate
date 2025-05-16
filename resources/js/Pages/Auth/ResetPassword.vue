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

            <div class="p-1"></div>

            <!-- Password -->
            <div>
                <FloatLabel>
                    <InputText
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full"
                        autocomplete="new-password"
                        required
                    />
                    <label for="password">Password</label>
                </FloatLabel>
                <small v-if="form.errors.password" class="p-error block mt-1">{{ form.errors.password }}</small>
            </div>

            <div class="p-1"></div>

            <!-- Confirm Password -->
            <div>
                <FloatLabel>
                    <InputText
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        class="w-full"
                        autocomplete="new-password"
                        required
                    />
                    <label for="password_confirmation">Confirm Password</label>
                </FloatLabel>
                <small v-if="form.errors.password_confirmation" class="p-error block mt-1">{{ form.errors.password_confirmation }}</small>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6">
                <Button
                    type="submit"
                    label="Reset Password"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </GuestLayout>
</template>
