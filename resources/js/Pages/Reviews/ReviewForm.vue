<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {Head, router, useForm} from "@inertiajs/vue3";
import {usePage} from "@inertiajs/vue3";

const { auth } = usePage().props

const { tasca, review } = defineProps({
    tasca: Object,
    review: Object
});

defineOptions({
    layout: MainLayoutTemp,
});

const form = useForm({
    rating: review ? review.rating : 0,
    body: review ? review.body : '',
});

function setRating(value) {
    form.rating = value;
}

function submitReview() {
    !review ?
        form.post(route('reviews.store', tasca), {
            forceFormData: true,
            onError: (errors) => {
                console.error(errors);
            },
        })
    :
        form.put(route('reviews.update', [tasca, review.id]), {
            method: 'patch',
            onError: (errors) => {
                console.error(errors);
            },
        });
}

</script>

<template>
    <Head title="Crear Reseña" />

    <h1 class="text-2xl font-bold mb-6">
        {{ review ? 'Editar Reseña' : 'Crear Reseña' }}
    </h1>

    <form @submit.prevent="submitReview" class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-md space-y-6">
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700">Tu nombre</label>
            <input
                :value="auth.user.name"
                type="text"
                placeholder="Juan Pérez"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                disabled
            />
        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700">Tu valoración</label>
            <div class="flex items-center space-x-1">
                <template v-for="i in 5" :key="i">
                    <svg
                        @click="setRating(i)"
                        xmlns="http://www.w3.org/2000/svg"
                        :class="[
                            'w-8 h-8 cursor-pointer transition-all',
                            i <= form.rating ? 'text-yellow-400' : 'text-gray-300'
                        ]"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.11 3.405a1 1 0 00.95.69h3.584c.969 0 1.371 1.24.588 1.81l-2.9 2.108a1 1 0 00-.364 1.118l1.11 3.404c.3.922-.755 1.688-1.54 1.118L10 13.347l-2.9 2.108c-.784.57-1.838-.196-1.539-1.118l1.11-3.404a1 1 0 00-.364-1.118L3.407 8.832c-.783-.57-.38-1.81.588-1.81h3.584a1 1 0 00.95-.69l1.11-3.405z"/>
                    </svg>
                </template>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-gray-700">Tu comentario</label>
            <textarea
                v-model="form.body"
                rows="4"
                placeholder="Escribe tu reseña aquí..."
                maxlength="1000"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            ></textarea>
        </div>

        <button
            type="submit"
            class="px-4 py-1.5 w-full rounded-full bg-green-600 text-white text-sm font-semibold shadow-md hover:bg-green-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
        >
            Enviar reseña
        </button>
        <button
            v-if="review"
            type="button"
            @click="router.visit(route('tascas.show', tasca.id), { preserveState: true, preserveScroll: true })"
            class="px-4 py-1.5 w-full rounded-full bg-gray-300 text-gray-700 text-sm font-semibold shadow-md hover:bg-gray-400 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-1"
            >
            Cancelar
        </button>
        <button
            v-if="review"
            type="button"
            @click="form.delete(route('reviews.destroy', [tasca, review.id]), {
                forceFormData: true,
                onError: (errors) => {
                    console.error(errors);
                },
            })"
            class="px-4 py-1.5 w-full rounded-full bg-red-600 text-white text-sm font-semibold shadow-md hover:bg-red-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
        >
            Eliminar reseña
        </button>
    </form>
</template>
