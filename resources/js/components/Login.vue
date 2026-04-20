<template>

<div class="bg-success text-white d-flex justify-content-center align-items-center vh-100">
    <div class="container" style="max-width:470px;">
        <div class="card shadow p-4" style="width: 500px;">
        <h1 class="card-title text-center text-success mb-4">Login</h1>

        <Alert :message="message" :type="type" @close="message = ''"/>
    <form @submit.prevent="login">            
            <div class="mb-3">
                <label>Email</label>
                <input v-model="email" class="form-control" type="email" placeholder="Email">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input v-model="password" class="form-control" type="password" placeholder="Password">
            </div>

            <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success btn-custom">Login</button>
            <a href="/" class="btn btn-warning ms-2 btn-back">Back</a>
            </div>
</form>
            </div>
            </div>
        </div>

</template>

<script setup>
import { useAuthStore } from '@/components/pinia/authStore'
import { ref, onMounted } from 'vue'
import axios from 'axios'
//import api from '@/axios'
import { useTitle } from '@/utils/useTitle.js'
import { useAlert } from '@/useAlert' 
import { useRouter } from 'vue-router'

const router = useRouter()

const email = ref('')
const password = ref('')

const { message, type, showError } = useAlert()

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Login')
})

const auth = useAuthStore()

const login = async () => {
    try {
        
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value
        },{
            withCredentials: true
        });

        //const response = await api.post('/login', {
        //    email: email.value,
        //    password: password.value
        //})
        
        auth.setUser(response.data.user)

        if (response.data.role === 'admin') {
            router.push({ name: 'admin-dashboard' })

        } else if(response.data.role === 'user'){
            router.push({ name: 'dashboard' })
        }
        

    } catch (error) {
        console.log(error.response)
        showError(error.response?.data?.message || 'Login failed')

    }
}

</script>