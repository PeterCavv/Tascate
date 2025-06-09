<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import PoliticaPrivacidadModal from "@/Components/PoliticaPrivacidadModal.vue";
import FileUpload from 'primevue/fileupload';
import CheckBox from 'primevue/checkbox';
import FormLayout from "@/Layouts/FormLayout.vue";
import {Head} from "@inertiajs/vue3";
import Message from 'primevue/message';
import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";
import Dialog from 'primevue/dialog';

const toast = useToast();
const { t } = useI18n();

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

/** * Submit the form to create a new Tasca proposal*/
const submitForm = () => {
    form.submit("post", route('tascas-proposals.store'), {
        preserveScroll: true,
        forceFormData: true,
        onError: (errors) => {
            Object.keys(errors).forEach(key => {
                toast.add({
                    severity: 'error',
                    summary: t('messages.toast.error'),
                    detail: errors[key],
                    life: 3000,
                });
            });
        },
        preserveState: true,
    });
};

/** * Handle file selection for CIF picture
 * @param {Event} event
 */
function onFileSelectCif(event) {
    form.cif_picture_path = event.files.length ? event.files[0] : null
}

/** Handle file selection for DNI picture
 * @param {Event} event
 */
function onFileSelectDni(event) {
    form.dni_picture_path = event.files.length ? event.files[0] : null
}

/** Clear the CIF picture path */
function onFileClearCif() {
    form.cif_picture_path = null
}

/** Clear the DNI picture path */
function onFileClearDni() {
    form.dni_picture_path = null
}
</script>

<template>
    <Toast/>
    <FormLayout>
        <Head title="Propuesta de Tasca" />

        <template #title>{{ t('messages.tasca_proposal.title') }}</template>
        <template #subtitle>{{ t('messages.tasca_proposal.subtitle') }}</template>

        <form @submit.prevent="submitForm" class="space-y-6">
            <div>
                <FloatLabel variant="on">
                    <label for="tasca_name" class="block font-semibold">
                        {{ t('messages.tasca_proposal.name') }}*
                    </label>
                    <InputText
                        id="tasca_name"
                        v-model="form.tasca_name"
                        v-keyfilter="/[a-zA-Z0-9 ]/"
                        class="w-full"
                        maxlength="100"
                        aria-required="true"
                        :invalid="form.errors.tasca_name"
                    />
                </FloatLabel>
                <Message
                    v-if="form.errors.tasca_name"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.tasca_name }}
                </Message>
            </div>

            <div>
                <FloatLabel variant="on">
                    <label for="address" class="block font-semibold">
                        {{ t('messages.tasca_proposal.address') }}*
                    </label>
                    <InputText
                        id="address"
                        v-model="form.address"
                        v-keyfilter="/[a-zA-Z0-9 ,.]/"
                        class="w-full"
                        maxlength="200"
                        aria-required="true"
                        :invalid="form.errors.address"
                    />
                </FloatLabel>
                <Message
                    v-if="form.errors.address"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.address }}
                </Message>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <FloatLabel variant="on">
                        <label for="telephone" class="block font-semibold">
                            {{ t('messages.tasca_proposal.phone') }}*
                        </label>
                        <InputText
                            id="telephone"
                            v-model="form.telephone"
                            v-keyfilter.int
                            class="w-full"
                            pattern="[0-9]{9}"
                            maxlength="9"
                            aria-required="true"
                            :invalid="form.errors.telephone"
                        />
                    </FloatLabel>
                    <Message
                        v-if="form.errors.telephone"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.telephone }}
                    </Message>
                </div>
                <div>
                    <FloatLabel variant="on">
                        <label for="cif" class="block font-semibold">
                            {{ t('messages.tasca_proposal.cif') }}*
                        </label>
                        <InputText
                            id="cif"
                            v-model="form.cif"
                            v-keyfilter="/[A-Z0-9]/"
                            class="w-full"
                            maxlength="9"
                            aria-required="true"
                            :invalid="form.errors.cif"
                        />
                    </FloatLabel>
                    <Message
                        v-if="form.errors.cif"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.cif }}
                    </Message>
                </div>
                <div>
                    <FloatLabel variant="on">
                        <label for="nif" class="block font-semibold">
                            {{ t('messages.tasca_proposal.nif') }}*
                        </label>
                        <InputText
                            id="nif"
                            v-model="form.dni"
                            v-keyfilter="/[A-Z0-9]/"
                            class="w-full"
                            maxlength="9"
                            aria-required="true"
                            :invalid="form.errors.dni"
                        />
                    </FloatLabel>
                    <Message
                        v-if="form.errors.dni"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.dni }}
                    </Message>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <FloatLabel variant="on">
                        <label for="owner_name" class="block font-semibold">
                            {{ t('messages.tasca_proposal.owner_name') }}*
                        </label>
                        <InputText
                            id="owner_name"
                            v-model="form.owner_name"
                            v-keyfilter="/[a-zA-Z ]/"
                            class="w-full"
                            maxlength="50"
                            aria-required="true"
                            :invalid="form.errors.owner_name"
                        />
                    </FloatLabel>
                    <Message
                        v-if="form.errors.owner_name"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.owner_name }}
                    </Message>
                </div>
                <div>
                    <FloatLabel variant="on">
                        <label for="owner_email" class="block font-semibold">
                            {{ t('messages.tasca_proposal.email') }}*
                        </label>
                        <InputText
                            id="owner_email"
                            v-model="form.owner_email"
                            v-keyfilter="/[a-zA-Z0-9@._+\-]/"
                            class="w-full"
                            maxlength="120"
                            aria-required="true"
                            :invalid="form.errors.owner_email"
                        />
                    </FloatLabel>
                    <Message
                        v-if="form.errors.owner_email"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.owner_email }}
                    </Message>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cif_picture" class="block font-semibold mb-1">
                        {{ t('messages.tasca_proposal.cif_file') }}
                    </label>
                    <FileUpload
                        inputId="cif_picture"
                        name="cif_picture"
                        accept="image/*"
                        mode="basic"
                        :auto="false"
                        :maxFileSize="1000000"
                        :maxFiles="1"
                        :showUploadButton="false"
                        @select="onFileSelectCif"
                        @clear="onFileClearCif"
                        class="w-full"
                        aria-labelledby="cif_picture_label"
                    />
                    <Message
                        v-if="form.errors.cif_picture_path"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.cif_picture_path }}
                    </Message>
                </div>

                <div>
                    <label for="dni_picture" class="block font-semibold mb-1">
                        {{ t('messages.tasca_proposal.nif_file') }}
                    </label>
                    <FileUpload
                        id="dni_picture"
                        name="dni_picture"
                        accept="image/*"
                        mode="basic"
                        :auto="false"
                        :maxFileSize="1000000"
                        :maxFiles="1"
                        :showUploadButton="false"
                        @select="onFileSelectDni"
                        @clear="onFileClearDni"
                        class="w-full"
                        aria-labelledby="dni_picture_label"
                    />
                    <Message
                        v-if="form.errors.dni_picture_path"
                        severity="error"
                        size="small"
                        variant="simple"
                    >
                        {{ form.errors.dni_picture_path }}
                    </Message>
                </div>
            </div>

            <div class="text-xs text-gray-400">
                <p>
                    {{ t('messages.tasca_proposal.privacy_policy') }}
                    <span
                        @click="openModal = true"
                        class="underline text-blue-400 hover:text-blue-600 cursor-pointer"
                    >
                        {{ t('messages.privacy_policy.title') }}
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
                <label for="acceptedPrivacy" class="ml-1">
                    {{ t('messages.tasca_proposal.check_terms') }}
                </label>
            </div>

            <Button
                type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition disabled:cursor-not-allowed disabled:bg-gray-400"
                :disabled="!acceptedPrivacy"
            >
                {{ t('messages.tasca_proposal.submit') }}
            </Button>
        </form>
    </FormLayout>

    <Dialog v-model:visible="openModal"
            modal
            :header="t('messages.privacy_policy.title')"
            :style="{ width: '50rem', padding: '2rem' }"
            :closable="false"
            :dismissableMask="true"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <PoliticaPrivacidadModal @close="openModal = false" />
    </Dialog>
</template>
