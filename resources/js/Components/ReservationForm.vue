<template>
    <Toast :position="form.errors ? 'top-center' : 'bottom-center' "/>

    <div class="max-w-2xl mx-auto p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800">
            <span v-if="!isEdit">{{ t('messages.reservation.title_create') + tasca.name }}</span>
            <span v-else>{{ t('messages.reservation.title_edit') + tasca.name }}</span>
        </h2>

        <form @submit.prevent="submitReservation">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ t('messages.reservation.labels.name') }}
                    </label>
                    <input
                        :value="auth.user.name"
                        :name="t('messages.reservation.labels.name')"
                        type="text"
                        id="name"
                        :placeholder="t('messages.reservation.labels.name')"
                        class="border border-gray-300 rounded-md p-2 w-full"
                        disabled
                    />
                </div>

                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                           {{ t('messages.reservation.labels.reservation_date') }}
                        </label>
                        <input
                            v-model="form.reservation_date"
                            :name="t('messages.reservation.labels.reservation_date')"
                            required
                            type="date"
                            id="date"
                            class="border border-gray-300 rounded-md p-2 w-full"
                            :min="today"
                        />
                    </div>

                    <div class="flex-1">
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ t('messages.reservation.labels.reservation_time') }}
                        </label>
                        <input
                            v-model="form.reservation_time"
                            required
                            :name="t('messages.reservation.labels.reservation_time')"
                            type="time"
                            id="time"
                            class="border border-gray-300 rounded-md p-2 w-full"
                            :min="openingTimeFormatted"
                            :max="closingTimeFormatted"
                        />
                    </div>

                    <div class="flex-1">
                        <label for="people" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ t('messages.reservation.labels.people') }}
                        </label>
                        <input
                            v-model="form.people"
                            :name="t('messages.reservation.labels.people')"
                            required
                            type="number"
                            id="people"
                            class="border border-gray-300 rounded-md p-2 w-full"
                            :max="tasca.capacity"
                            :min="1"
                            @input="(e) => e.target.value = Math.max(1, Math.min(tasca.capacity, e.target.value))"
                        />
                    </div>
                </div>

                <div>
                    <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ t('messages.reservation.labels.observations') }}
                    </label>
                    <textarea
                        v-model="form.observations"
                        :name="t('messages.reservation.labels.observations')"
                        id="observations"
                        class="border border-gray-300 rounded-md p-2 w-full resize-none"
                        rows="4" />
                </div>

                <div class="flex items-center space-x-4">
                    <Button
                        type="submit"
                        :name="isEdit ? t('messages.reservation.buttons.update') : t('messages.reservation.buttons.create')"
                        class="px-5 py-1.5 rounded-full bg-green-600 text-white font-semibold shadow-md hover:bg-green-700
                        transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400
                        focus:ring-offset-1"
                        :label="isEdit ? t('messages.reservation.buttons.update') : t('messages.reservation.buttons.create')"
                    />

                    <p v-if="!isEdit" class="font-bold text-green-950">
                        {{ t('messages.reservation.price_text') + tasca.reservation_price }}€
                    </p>
                </div>

                <p v-if="!isEdit" class="text-xs text-gray-500 leading-relaxed">
                    {{ t('messages.reservation.payment_info') }}
                </p>
            </div>
        </form>
    </div>
</template>

<script setup>
import MainLayoutTemp from "@/Layouts/MainLayoutTemp.vue";
import {useForm, usePage} from '@inertiajs/vue3';
import {computed} from "vue";
import {useI18n} from "vue-i18n";
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";

const {t} = useI18n();
const toast = useToast()

const emit = defineEmits(['close']);

const {tasca, isEdit, reservation} = defineProps({
    tasca: Object,
    isEdit: Boolean,
    reservation: Object
});

const { auth } = usePage().props

const form = useForm({
    tasca_id: tasca.id,
    reservation_price: tasca.reservation_price,
    reservation_date: reservation ? reservation.reservation_date : '',
    reservation_time: reservation ? reservation.reservation_time : '',
    people: reservation ? reservation.people : 1,
    observations: reservation ? reservation.observations : '',
});

const openingTimeFormatted = computed(() => tasca.opening_time?.slice(0, 5))
const closingTimeFormatted = computed(() => tasca.closing_time?.slice(0, 5))

const today = new Date().toISOString().split('T')[0]

defineOptions({
    layout: MainLayoutTemp,
});

/**
* Submits the reservation form.
* @returns {number} media (decimal)
*/
const submitReservation = () => {
    form.reservation_time = form.reservation_time?.slice(0, 5);

    !isEdit ? form.post(route('reservations.store'), {
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
          })
        },
    }) : form.put(route('reservations.update', reservation.id), {
        method: 'patch',
        onSuccess: () => {
            emit('close');
            form.reset();
            toast.add({
                severity: 'success',
                summary: t('messages.toast.updated'),
                detail: t('messages.toast.reservation_updated'),
                life: 3000,
            });
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};
</script>
