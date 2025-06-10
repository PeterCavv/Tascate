<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {computed} from 'vue'
import {Head, router, useForm} from '@inertiajs/vue3'
import 'primeicons/primeicons.css'
import {Link} from '@inertiajs/vue3'
import FloatLabel from "primevue/floatlabel";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import {useI18n} from "vue-i18n";
import Message from "primevue/message";
import DatePicker from "primevue/datepicker";
import Checkbox from "primevue/checkbox";
import {useToast} from "primevue/usetoast";
import FileUpload from "primevue/fileupload";
import FormLayout from "@/Layouts/FormLayout.vue";
import InputNumber from 'primevue/inputnumber';

const {t} = useI18n();
const toast = useToast();

const {tasca} = defineProps({
    tasca: Object
})

defineOptions({
    layout: MainLayoutTemp,
})

const openingTimeFormatted = computed(() => tasca.opening_time?.slice(0, 5))
const closingTimeFormatted = computed(() => tasca.closing_time?.slice(0, 5))

const form = useForm({
    name: tasca ? tasca.name : '',
    address: tasca ? tasca.address : '',
    telephone: tasca ? tasca.telephone : '',
    reservation: tasca ? tasca.reservation : false,
    reservation_price: tasca ? tasca.reservation_price : 0,
    opening_time: tasca ? openingTimeFormatted.value : '',
    closing_time: tasca ? closingTimeFormatted.value : '',
    capacity: tasca ? tasca.capacity : 0,
    picture: null,
})

function handleFileUpload(e) {
    form.picture = e.files[0] ?? null;
}

function handleFileClear(e) {
    form.picture = null;
}

const submitForm = () => {
    form._method = 'PATCH';

    form.submit("post", route("tascas.update", tasca.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset();
        },
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
</script>

<template>
    <Head :title="t('messages.tasca.edit_form.title')"></Head>
    <FormLayout>
        <!-- Header slot -->
        <template #header>
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                        Editar Tasca
                    </h1>
                    <p class="text-gray-500 mt-2">
                        Complete todos los campos requeridos (*)
                    </p>
                </div>
            </div>
        </template>

        <!-- Main form content slot -->
        <form
            @submit.prevent="submitForm"
            class="space-y-6"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-wrap md:flex-nowrap items-end gap-2 col-span-2">
                    <div class="w-full">
                        <FloatLabel variant="on">
                            <label for="name">Nombre</label>
                            <InputText id="name" class="w-full" v-model="form.name" :invalid="form.errors.name" />
                        </FloatLabel>
                    </div>

                    <div class="w-full">
                        <FloatLabel variant="on">
                            <InputText v-model="form.address" class="w-full" :invalid="form.errors.address" />
                            <label class="block font-semibold mb-1">Dirección</label>
                        </FloatLabel>
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-nowrap items-end gap-4 col-span-2">
                    <div class="flex flex-col flex-1 min-w-[120px]">
                        <FloatLabel variant="on">
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
                            <label for="telephone" class="block font-semibold text-sm mb-1">Teléfono</label>
                        </FloatLabel>
                    </div>

                    <div class="flex flex-col flex-1 min-w-[120px]">
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.capacity"
                                class="w-full px-3 py-2"
                                maxlength="9"
                                :invalid="form.errors.capacity"
                            />
                            <label class="block font-semibold text-sm mb-1">Capacidad</label>
                        </FloatLabel>
                    </div>

                    <div class="flex flex-col flex-1 min-w-[100px] min-h-[1.5rem]">
                        <FloatLabel variant="on">
                            <DatePicker
                                inputId="opening_time"
                                v-model="form.opening_time"
                                showIcon
                                iconDisplay="input"
                                timeOnly
                                :inputClass="form.errors.opening_time ? 'p-invalid w-full' : 'w-full'"
                            >
                                <template #inputicon="slotProps">
                                    <i class="pi pi-clock" @click="slotProps.clickCallback" />
                                </template>
                            </DatePicker>
                            <label for="opening_time">Hora apertura</label>
                        </FloatLabel>
                    </div>

                    <div class="flex flex-col min-w-[100px] flex-grow">
                        <FloatLabel variant="on">
                            <DatePicker
                                inputId="closing_time"
                                v-model="form.closing_time"
                                showIcon
                                iconDisplay="input"
                                timeOnly
                                fluid
                                :inputClass="form.errors.closing_time ? 'p-invalid w-full' : 'w-full'"
                            >
                                <template #inputicon="slotProps">
                                    <i class="pi pi-clock" @click="slotProps.clickCallback" />
                                </template>
                            </DatePicker>
                            <label class="block font-semibold text-sm mb-1">Hora cierre</label>
                        </FloatLabel>
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-nowrap items-end gap-2 col-span-2">
                    <div>
                        <FloatLabel variant="on">
                            <InputNumber
                                v-model="form.reservation_price"
                                inputId="price"
                                mode="currency"
                                currency="EUR"
                                locale="de-DE"
                                :invalid="form.errors.reservation_price"
                                class="w-full"
                                inputClass="w-full"
                                :disabled="!form.reservation"
                            />
                            <label for="price">Precio</label>
                        </FloatLabel>
                    </div>

                    <div class="flex items-center space-x-2 whitespace-nowrap">
                        <Checkbox v-model="form.reservation" inputId="reservation" binary />
                        <label for="reservation" class="font-medium">¿Permite Reservas?</label>
                    </div>
                </div>

                <div class="col-span-2 w-full">
                    <label for="tasca_picture" class="block font-semibold mb-1">Imagen</label>
                    <FileUpload
                        id="tasca_picture"
                        name="tasca_picture"
                        accept="image/*"
                        mode="basic"
                        :auto="false"
                        :maxFileSize="1000000"
                        :maxFiles="1"
                        :showUploadButton="false"
                        @select="handleFileUpload"
                        @clear="handleFileClear"
                        class="w-full"
                        aria-labelledby="tasca_picture"
                    />
                    <p class="text-sm text-gray-500 text-center mt-1">Formato: JPG, PNG. Tamaño máximo: 2MB.</p>
                </div>

                <div class="flex flex-wrap md:flex-nowrap items-end gap-2 col-span-2">
                    <Button
                        label="Guardar Cambios"
                        icon="pi pi-save"
                        type="submit"
                        class="w-full"
                    />
                    <Button
                        @click="router.visit(`/tascas/${tasca.id}`)"
                        label="Descartar"
                        outlined
                        icon="pi pi-undo"
                        severity="secondary"
                        class="w-full"
                    />
                </div>
            </div>
        </form>

        <template #motivational-phrase>
            <Fieldset legend="Tascate">
                <p class="m-0">Cuanto más llamativa, más público atraerás.</p>
            </Fieldset>
        </template>
    </FormLayout>
</template>



