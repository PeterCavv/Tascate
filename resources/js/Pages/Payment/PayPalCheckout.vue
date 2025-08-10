<template>

</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { onMounted } from 'vue'
import axios from 'axios'

defineOptions({
    layout: MainLayout
});

onMounted(() => {
    const script = document.createElement('script')
    script.src = `https://www.paypal.com/sdk/js?client-id=${import.meta.env.VITE_PAYPAL_CLIENT_ID}&currency=USD`
    script.onload = () => {
        window.paypal.Buttons({
            createOrder: async () => {
                const { data } = await axios.post('/paypal/create-order', { total: 20.00 })
                return data.id
            },
            onApprove: async (data) => {
                const { data: captureData } = await axios.post('/paypal/capture-order', {
                    orderID: data.orderID
                })
                console.log('Pago completado:', captureData)
                alert('Pago exitoso 🎉')
            },
            onError: (err) => {
                console.error(err)
                alert('Ocurrió un error con el pago')
            }
        }).render('#paypal-button-container')
    }
    document.body.appendChild(script)
})
</script>
