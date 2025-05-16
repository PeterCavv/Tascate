<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="p-4 space-y-4 w-full max-w-md mx-auto">
            <div class="p-1"></div>

            <!-- Name -->
            <div>
                <FloatLabel>
                    <InputText
                        id="name"
                        type="text"
                        v-model="form.name"
                        class="w-full"
                        autocomplete="name"
                        autofocus
                        required
                    />
                    <label for="name">Name</label>
                </FloatLabel>
                <small v-if="form.errors.name" class="p-error block mt-1">{{ form.errors.name }}</small>
            </div>

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
            <div class="flex items-center justify-between mt-6">
                <Link
                    :href="route('login')"
                    class="text-sm text-blue-600 hover:underline"
                >
                    Already registered?
                </Link>

                <Button
                    type="submit"
                    label="Register"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </GuestLayout>
</template>
