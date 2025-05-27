<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import PoliticaPrivacidadModal from "@/Components/PoliticaPrivacidadModal.vue";
import FileUpload from 'primevue/fileupload';
import CheckBox from 'primevue/checkbox';

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
    dni_picture_path: "",
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
function onFileSelectCif(event) {
    form.cif_picture_path = event.files.length ? event.files[0] : null
}

function onFileSelectDni(event) {
    form.dni_picture_path = event.files.length ? event.files[0] : null
}

function onFileClearCif() {
    form.cif_picture_path = null
}

function onFileClearDni() {
    form.dni_picture_path = null
}
</script>

<template>
    <form @submit.prevent="submitForm" class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">

        <h2 id="privacyTitle" class="text-xl sm:text-2xl font-bold mb-6 text-center sm:text-left">
            Propuesta de registro de Tasca
        </h2>

        <div>
            <label for="tasca_name" class="block font-semibold">Nombre de la Tasca*</label>
            <InputText
                id="tasca_name"
                v-model="form.tasca_name"
                v-keyfilter="/[a-zA-Z0-9 ]/"
                class="w-full"
                maxlength="100"
                placeholder="Nombre de la Tasca"
                required
            />
        </div>

        <div>
            <label for="address" class="block font-semibold">Dirección*</label>
            <InputText
                id="address"
                v-model="form.address"
                v-keyfilter="/[a-zA-Z0-9 ]/"
                class="w-full"
                maxlength="200"
                placeholder="Dirección de la Tasca"
                required
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="telephone" class="block font-semibold">Teléfono*</label>
                <InputText
                    id="telephone"
                    v-model="form.telephone"
                    v-keyfilter.int
                    class="w-full"
                    pattern="[0-9]{9}"
                    maxlength="9"
                    placeholder="Teléfono de contacto"
                    required
                />
            </div>
            <div>
                <label for="cif" class="block font-semibold">CIF*</label>
                <InputText
                    id="cif"
                    v-model="form.cif"
                    v-keyfilter="/[A-Z0-9]/"
                    class="w-full"
                    maxlength="9"
                    placeholder="CIF de la empresa"
                    required
                />
            </div>
            <div>
                <label for="nif" class="block font-semibold">NIF*</label>
                <InputText
                    id="nif"
                    v-model="form.dni"
                    v-keyfilter="/[A-Z0-9]/"
                    class="w-full"
                    maxlength="9"
                    placeholder="NIF del propietario"
                    required
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="owner_name" class="block font-semibold">Nombre del Propietario*</label>
                <InputText
                    id="owner_name"
                    v-model="form.owner_name"
                    v-keyfilter="/[a-zA-Z ]/"
                    class="w-full"
                    maxlength="50"
                    placeholder="Ingresa el nombre del propietario"
                    required
                />
            </div>
            <div>
                <label for="owner_email" class="block font-semibold">Email del Propietario*</label>
                <InputText
                    id="owner_email"
                    v-model="form.owner_email"
                    v-keyfilter="/[a-zA-Z0-9@._+\-]/"
                    class="w-full"
                    maxlength="120"
                    placeholder="Ingresa un email válido"
                    required
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="cif_picture" class="block font-semibold mb-1">CIF de la Empresa</label>
                <FileUpload
                    inputId="cif_picture"
                    name="cif_picture"
                    accept="image/*"
                    customUpload
                    :auto="false"
                    :maxFileSize="1000000"
                    :maxFiles="1"
                    :showUploadButton="false"
                    @select="onFileSelectCif"
                    @clear="onFileClearCif"
                    class="w-full"
                />
            </div>

            <div>
                <label for="dni_picture" class="block font-semibold mb-1">DNI del Representante Legal</label>
                <FileUpload
                    id="dni_picture"
                    name="dni_picture"
                    accept="image/*"
                    customUpload
                    :auto="false"
                    :maxFileSize="1000000"
                    :maxFiles="1"
                    :showUploadButton="false"
                    @select="onFileSelectDni"
                    @clear="onFileClearDni"
                    class="w-full"
                >
                    <template #choose>
                        <Button icon="pi pi-image" label="Elegir imagen" />
                    </template>

                    <template #upload>
                        <Button icon="pi pi-cloud-upload" label="Subir archivo" />
                    </template>

                    <template #cancel>
                        <Button icon="pi pi-times" label="Cancelar" severity="danger" />
                    </template>
                </FileUpload>
            </div>
        </div>

<!--        <div>-->
<!--            <label class="block font-semibold mb-1" for="image1">CIF de la Empresa</label>-->
<!--            <input-->
<!--                id="image1"-->
<!--                type="file"-->
<!--                accept="image/*"-->
<!--                @change="handleImage1Upload"-->
<!--                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"-->
<!--            />-->
<!--        </div>-->

<!--        <div>-->
<!--            <label class="block font-semibold mb-1" for="image2">DNI del Representante Legal</label>-->
<!--            <input-->
<!--                id="image2"-->
<!--                type="file"-->
<!--                accept="image/*"-->
<!--                @change="handleImage2Upload"-->
<!--                class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"-->
<!--            />-->
<!--        </div>-->

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

        <div class="flex items-center gap-2">
            <CheckBox
                v-model="acceptedPrivacy"
                name="acceptedPrivacy"
                inputId="acceptedPrivacy"
                binary
            />
            <label for="acceptedPrivacy" class="text-l ml-1">He leído y acepto la Política de Privacidad</label>
        </div>

<!--        <div>-->
<!--            <input-->
<!--                v-model="acceptedPrivacy"-->
<!--                type="checkbox"-->
<!--                id="reservation"-->
<!--                class="w-5 h-5 accent-green-600 "-->
<!--            ><span class="text-l ml-3">He leído y acepto la Política de Privacidad</span>-->
<!--        </div>-->

        <Button
            type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition disabled:cursor-not-allowed disabled:bg-gray-400 disabled:text-gray-700 disabled:hover:bg-gray-400"
            :disabled="!acceptedPrivacy"
        >
            Enviar Propuesta
        </Button>
    </form>
    <transition name="fade">
        <PoliticaPrivacidadModal v-if="openModal" @close="openModal = false"/>
    </transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
:deep(.p-fileupload-file-status){
    display: none !important;
}
</style>
