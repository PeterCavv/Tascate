<script setup>
import {Link, router} from "@inertiajs/vue3";
import {useForm} from "@inertiajs/vue3";
import 'primeicons/primeicons.css';
import MainLayout from "@/Layouts/MainLayout.vue";

const form = useForm();

const postCommentForm = useForm({
    content: '',
});

const editCommentForm = useForm({
    content: '',
});

const createResponseForm = useForm({
    content: '',
});

function createResponse(comment) {
    comment.showResponseForm = !comment.showResponseForm ;
}

defineOptions({
    layout: MainLayout,
});

const { post } = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

console.log(JSON.stringify(post.comments, null, 2));



const deletePost = () => {
    form.delete(route('posts.destroy', post.id));
};

function toggleFavorite(post) {
    // Optimistic UI update
    post.is_favorite = !post.is_favorite;

    router.post(`/posts/${post.id}/toggle-like`, {}, {
        preserveScroll: true,
        onError: () => {
            // Revert in case of error
            post.is_favorite = !post.is_favorite;
        },
    });
}

function submitComment() {
    const comment = postCommentForm.content;

    if (comment.trim() === '') {
        alert('Please enter a comment.');
        return;
    }
    postCommentForm.post(`/posts/${post.id}/comment`, { comment }, {
        onSuccess: () => {
            document.querySelector('textarea').value = '';
            // Optionally, you can refresh the comments section here
        },
        onError: () => {
            alert('Failed to submit comment.');
        },
        onFinish: () => {
            postCommentForm.reset('content');
        },
    });
}

function editComment(comment) {
    comment.originalContent = comment.content;
    comment.isEditing = true;
}

function cancelEdit(comment) {
    comment.content = comment.originalContent;
    comment.isEditing = false;
}

function showResponses(comment) {
    comment.showResponses = !comment.showResponses;
}

function saveResponse(comment){
    createResponseForm.post(route('posts.comment.response', comment), {
        content: createResponseForm.content,
    }, {
        onSuccess: () => {
            comment.showResponseForm = false;
            createResponseForm.reset('content');
        },
        onError: () => {
            alert('Error al enviar la respuesta.');
        },
    });
}
function saveComment(comment) {
    editCommentForm.content = comment.content;

    editCommentForm.put(route('posts.comment.update', comment.id), {
        content: editCommentForm.content,
    }, {
        onSuccess: () => {
            comment.isEditing = false;
        },
        onError: () => {
            alert('Error al guardar el comentario.');
        },
    });
}
</script>

<template>

    <div class="post-show">
        <h1 class="text-3xl font-bold mb-4">{{ post.title }}</h1>
        <p class="text-gray-700 mb-6">{{ post.content }}</p>

        <div class="creator-info mb-6">
            <h2 class="text-xl font-semibold">Created by:</h2>
            <p class="text-gray-600">{{ post.user.name }}</p>

            <Link
                v-if="$page.props.auth && $page.props.auth.user && $page.props.auth.user.id === post.user_id"
                :href="`/posts/${post.id}/edit`"
                class="text-indigo-600 hover:underline"
            >
                Editar
            </Link>
            <button
                v-if="$page.props.auth && $page.props.auth.user && $page.props.auth.user.id === post.user_id"
                class="text-red-600 hover:underline ml-4"
                @click="deletePost"
            >
                Eliminar
            </button>
        </div>
        <button
            @click="toggleFavorite(post)"
            :class="post.is_favorite ? 'text-red-500' : 'text-gray-500'"
            class="focus:outline-none"
        >
            <i :class="post.is_favorite ? 'pi pi-heart-fill' : 'pi pi-heart'"></i>
        </button>
        <span class="ml-2 text-sm text-gray-500">
            {{ post.likedByUsers }} {{ post.likedByUsers === 1 ? 'like' : 'likes' }}
        </span>

        <div class="pictures">
            <h2 class="text-xl font-semibold mb-4">Pictures:</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <img
                    v-for="(picture, index) in post.pictures"
                    :key="index"
                    :src="`/storage/${picture.picture_path}`"
                    alt="Post Picture"
                    class="rounded-lg shadow-md"
                    @error="$event.target.src = '/images/post-default.jpg'"
                />
                <img
                    v-if="post.pictures.length === 0"
                    src="/images/post-default.jpg"
                    alt="Imagen por defecto"
                    class="rounded-lg shadow-md"
                />
            </div>
        </div>

        <div class="comments">
            <div class="flex space-x-3">
                <h1>Comentarios</h1>
                <i class="pi pi-comment mt-1"></i>
            </div>
            <div class="mt-4">
                <textarea
                    id="content"
                    v-model="postCommentForm.content"
                    placeholder="Escribe un comentario..."
                    class="w-full p-2 border rounded-lg"
                ></textarea>
                <button
                    @click="submitComment"
                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg"
                >
                    Comentar
                </button>
                <p v-if="postCommentForm.errors.content" class="text-red-500 text-sm mt-2">
                    {{ postCommentForm.errors.content }}
                </p>
            </div>
            <div>
                <h1>Lista de comentarios</h1>
                <div v-for="comment in post.comments" :key="comment.id"  >
                    <div v-if="!comment.id_comment_father" class="p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="font-semibold text-gray-800">{{ comment.user.name }}</div>
                        <div class="text-sm text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</div>
                    </div>
                    <div v-if="comment.isEditing">
                        <textarea
                            v-model="comment.content"
                            class="w-full p-2 border rounded-lg mt-2"
                        ></textarea>
                        <button
                            @click="saveComment(comment)"
                            class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg"
                        >
                            Guardar
                        </button>
                        <button
                            @click="cancelEdit(comment)"
                            class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-lg ml-2"
                        >
                            Cancelar
                        </button>
                    </div>
                    <div v-else>
                        <p class="mt-2 text-gray-700">{{ comment.content }}</p>
                        <div v-if="comment.user_id === $page.props.auth.user.id" class="mt-2">
                            <button
                                @click="editComment(comment)"
                                class="text-blue-600 hover:underline"
                            >
                                Editar
                            </button>
                            <button
                                @click="form.delete(route('posts.comment.delete', comment.id))"
                                class="text-red-600 hover:underline ml-2"
                            >
                                Eliminar
                            </button>
                            <button
                                @click="showResponses(comment)"
                                class="text-gray-600 hover:underline ml-2"
                            >
                                Respuestas
                            </button>
                            <button
                                @click="createResponse(comment)"
                                class="text-green-600 hover:underline ml-2"
                            >
                                Responder
                            </button>
                            <p v-if="editCommentForm.errors.content" class="text-red-500 text-sm mt-2">
                                {{ editCommentForm.errors.content }}
                            </p>
                        </div>

                        <div>
                            <div v-if="comment.showResponseForm" class="mt-4">
                                <textarea
                                    v-model="createResponseForm.content"
                                    placeholder="Escribe una respuesta..."
                                    class="w-full p-2 border rounded-lg"
                                ></textarea>
                                <p
                                v-if="createResponseForm.errors">
                                    {{ createResponseForm.errors.content }}
                                </p>
                                <button
                                    @click="saveResponse(comment)"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Responder
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 bg-gray-100 p-4 rounded-lg"
                             v-if="comment.showResponses"
                             v-for="child_comment in comment.child_comments" :key="child_comment.id">
                            <div class="flex items-center space-x-3">
                                <div class="font-semibold text-gray-800">{{ child_comment.user.name }}</div>
                                <div class="text-sm text-gray-500">{{ new Date(child_comment.created_at).toLocaleString() }}</div>
                            </div>
                            <div v-if="child_comment.isEditing">
        <textarea
            v-model="child_comment.content"
            class="w-full p-2 border rounded-lg mt-2"
        ></textarea>
                                <button
                                    @click="saveComment(child_comment)"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Guardar
                                </button>
                                <button
                                    @click="cancelEdit(child_comment)"
                                    class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-lg ml-2"
                                >
                                    Cancelar
                                </button>
                            </div>
                            <div v-else>
                                <p class="mt-2 text-gray-700">{{ child_comment.content }}</p>
                                <div v-if="child_comment.user_id === $page.props.auth.user.id" class="mt-2">
                                    <button
                                        @click="editComment(child_comment)"
                                        class="text-blue-600 hover:underline"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="form.delete(route('posts.comment.delete', child_comment.id))"
                                        class="text-red-600 hover:underline ml-2"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
