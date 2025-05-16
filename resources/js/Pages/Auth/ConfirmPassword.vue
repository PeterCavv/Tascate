<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit.prevent="submit" class="p-4 space-y-4 w-full max-w-md mx-auto">
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
                        autofocus
                        required
                    />
                    <label for="password">Password</label>
                </FloatLabel>
                <small v-if="form.errors.password" class="p-error block mt-1">{{ form.errors.password }}</small>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-6">
                <Button
                    type="submit"
                    label="Confirm"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </div>
        </form>
    </GuestLayout>
</template>
