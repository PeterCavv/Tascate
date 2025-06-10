<template>
    <div class="card flex justify-center">
        <DatePicker
            v-model="date"
            inline
            showWeek
            :disabled="false"
            class="w-full sm:w-[30rem] pointer-events-none"
        />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import DatePicker from 'primevue/datepicker'

const props = defineProps({
    selectedDate: {
        type: String,
        required: true
    }
})

const date = ref(null)

watch(
    () => props.selectedDate,
    (val) => {
        if (val) {
            const [year, month, day] = val.split('-').map(Number)
            date.value = new Date(year, month - 1, day)
        }
    },
    { immediate: true }
)
</script>
