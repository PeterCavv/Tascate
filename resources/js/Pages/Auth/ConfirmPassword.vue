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

        <main class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <section aria-labelledby="confirm-password-heading" class="space-y-4">
                <h2 id="confirm-password-heading" class="sr-only">Confirm Password</h2>

                <div 
                    class="mb-3 text-sm text-gray-600 leading-relaxed max-w-md mx-auto text-center"
                    role="alert"
                    aria-live="polite"
                >
                    This is a secure area of the application. Please confirm your
                    password before continuing.
                </div>

                <form 
                    @submit.prevent="submit" 
                    class="p-2 space-y-2 w-full max-w-sm mx-auto"
                    aria-label="Password confirmation form"
                >
                    <!-- Password -->
                    <div class="p-1"></div>
                    <div class="space-y-1">
                        <FloatLabel>
                            <InputText
                                id="password"
                                type="password"
                                v-model="form.password"
                                class="w-full"
                                autocomplete="current-password"
                                autofocus
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

                    <!-- Actions -->
                    <div class="flex items-center justify-end mt-3">
                        <Button
                            type="submit"
                            label="Confirm"
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
