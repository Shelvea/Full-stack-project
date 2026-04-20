<template>
    
    <h5>Order Success</h5>

    <Alert :message="message" :type="type" @close="message = ''" />

    <div class="container mt-5 pt-5 text-center">
        <p>We have received your order and it will be processed shortly.</p>
        <a @click="goToHome" class="btn btn-success mt-3">Go to Home</a>
    </div>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router'
import { useAlert } from '@/useAlert'
import { onMounted } from 'vue'

const router = useRouter()
const route = useRoute()

const { message, type, showSuccess, showError } = useAlert()

onMounted(() => {

    if(route.query.message){
        
        if (route.query.type === 'success') {
            showSuccess(route.query.message)

        } else {
            
            showError(route.query.message)
        }
    }
})

function goToHome(){
    
    router.push({ name:'orderSuccess' })
}
</script>