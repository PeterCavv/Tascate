<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <main class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <section aria-labelledby="verification-heading" class="space-y-4">
                <h2 id="verification-heading" class="sr-only">Email Verification</h2>

                <div 
                    class="mb-3 text-sm text-gray-600 leading-relaxed max-w-md mx-auto text-center"
                    role="alert"
                    aria-live="polite"
                >
                    Thanks for signing up! Before getting started, could you verify your
                    email address by clicking on the link we just emailed to you? If you
                    didn't receive the email, we will gladly send you another.
                </div>

                <div
                    v-if="verificationLinkSent"
                    class="mb-3 text-sm font-medium text-green-600 text-center"
                    role="alert"
                    aria-live="polite"
                >
                    A new verification link has been sent to the email address you
                    provided during registration.
                </div>

                <form 
                    @submit.prevent="submit" 
                    class="p-2 space-y-2 w-full max-w-sm mx-auto"
                    aria-label="Email verification form"
                >
                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-3">
                        <Button
                            type="submit"
                            label="Resend Verification Email"
                            :loading="form.processing"
                            :disabled="form.processing"
                            size="small"
                            class="w-full sm:w-auto"
                            aria-busy="form.processing"
                        />

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-xs text-blue-600 hover:underline w-full sm:w-auto text-center"
                            aria-label="Log out of your account"
                        >
                            Log Out
                        </Link>
                    </div>
                </form>
            </section>
        </main>
    </GuestLayout>
</template>
