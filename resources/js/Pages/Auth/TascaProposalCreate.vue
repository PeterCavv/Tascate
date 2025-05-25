<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import PoliticaPrivacidadModal from "@/Components/PoliticaPrivacidadModal.vue";

defineOptions({
    layout: MainLayoutTemp,
});

const form = useForm({
    tasca_name: "",
    address: "",
    telephone: "",
    cif: "",
    cif_picture_path: "",
    owner_name: "",
    owner_email: "",
    dni: "",
    dni_picture_path: ""
});

const acceptedPrivacy = ref(false);
const openModal = ref(false);

const submitForm = () => {
    form.submit("post", route('tascas-proposals.store'), {
        preserveScroll: true,
        forceFormData: true,
        onError: (errors) => {
            console.error('Errores:', errors);
        },
        preserveState: true,
    });

};
function handleImage1Upload(event) {
    const file = event.target.files[0]
    form.cif_picture_path = file ? file : null
}

function handleImage2Upload(event) {
    const file = event.target.files[0]
    form.dni_picture_path = file ? file : null
}
</script>

<template>
    <form @submit.prevent="submitForm" class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">

        <h2 id="privacyTitle" class="text-xl sm:text-2xl font-bold mb-6 text-center sm:text-left">
            Propuesta de registro de Tasca
        </h2>

        <div>
            <label class="block font-semibold mb-1">Nombre de la Tasca*</label>
            <input
                v-model="form.tasca_name"
                type="text"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                required
            />
        </div>

        <div>
            <label class="block font-semibold mb-1">Dirección*</label>
            <input
                v-model="form.address"
                type="text"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
                required
            />
        </div>

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

        <div>
            <label class="block font-semibold mb-1" for="image1">CIF de la Empresa</label>
            <input
                id="image1"
                type="file"
                accept="image/*"
                @change="handleImage1Upload"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
            />
        </div>

        <div>
            <label class="block font-semibold mb-1" for="image2">DNI del Representante Legal</label>
            <input
                id="image2"
                type="file"
                accept="image/*"
                @change="handleImage2Upload"
                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-xs text-gray-400">
            <p>De conformidad con el Reglamento (UE) 2016/679 (RGPD) y la Ley Orgánica 3/2018 (LOPDGDD), le informamos de que los datos personales proporcionados serán tratados por TASCATE con la finalidad de gestionar el registro del establecimiento en nuestra plataforma.
                Los datos no se cederán a terceros salvo obligación legal. Puede ejercer sus derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad escribiendo a tascate_administracion@gmail.com.
                Puede consultar la información completa sobre el tratamiento de datos en nuestra
                <span
                    @click="openModal = true"
                    class="underline text-blue-400 transition hover:text-blue-600 cursor-pointer"
                >
                    Política de Privacidad
                </span>.
            </p>
        </div>

        <div>
            <input
                v-model="acceptedPrivacy"
                type="checkbox"
                id="reservation"
                class="w-5 h-5 accent-green-600 "
            ><span class="text-l ml-3">He leído y acepto la Política de Privacidad</span>
        </div>

        <button
            type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition disabled:cursor-not-allowed disabled:bg-gray-400 disabled:text-gray-700 disabled:hover:bg-gray-400"
            :disabled="!acceptedPrivacy"
        >
            Enviar Propuesta
        </button>
    </form>
    <PoliticaPrivacidadModal v-if="openModal" @close="openModal = false"/>
</template>
