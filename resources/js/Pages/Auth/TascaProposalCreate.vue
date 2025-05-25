<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {useForm} from "@inertiajs/vue3";

defineOptions({
    layout: MainLayoutTemp,
});

const form = useForm({
    tasca_name: "",
    address: "",
    telephone: "",
    cif: "",
    owner_name: "",
    owner_email: "",
    dni: "",
});

const submitForm = () => {
    form.post(route('tascas-proposals.store'), {
        onError: (errors) => {
            console.error('Errores:', errors);
        },
        preserveScroll: true,
        preserveState: true,
    });

};

</script>

<template>
    <form @submit.prevent="submitForm" class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">

        <!-- Fila: Nombre Tasca -->
        <div>
            <label class="block font-semibold mb-1">Nombre de la Tasca*</label>
            <input
                v-model="form.tasca_name"
                type="text"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                required
            />
        </div>

        <!-- Fila: Dirección -->
        <div>
            <label class="block font-semibold mb-1">Dirección*</label>
            <input
                v-model="form.address"
                type="text"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                required
            />
        </div>

        <!-- Fila: Teléfono, CIF, DNI -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold mb-1">Teléfono*</label>
                <input
                    v-model="form.telephone"
                    type="tel"
                    class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    pattern="[0-9]{9}"
                    maxlength="9"
                    required
                />
            </div>
            <div>
                <label class="block font-semibold mb-1">CIF*</label>
                <input
                    v-model="form.cif"
                    type="text"
                    class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 uppercase"
                    maxlength="9"
                    required
                />
            </div>
            <div>
                <label class="block font-semibold mb-1">DNI*</label>
                <input
                    v-model="form.dni"
                    type="text"
                    class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 uppercase"
                    maxlength="9"
                    required
                />
            </div>
        </div>

        <!-- Fila: Owner Name & Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Nombre del Propietario*</label>
                <input
                    v-model="form.owner_name"
                    type="text"
                    class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required
                />
            </div>
            <div>
                <label class="block font-semibold mb-1">Email del Propietario*</label>
                <input
                    v-model="form.owner_email"
                    type="email"
                    class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                    required
                />
            </div>
        </div>

        <!-- Botón -->
        <button
            type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition"
        >
            Enviar Propuesta
        </button>
    </form>
</template>
