<template>

    <div class="bg-success text-white d-flex justify-content-center align-items-center vh-100">
    <div class="container" style="max-width:470px;">
        <div class="card shadow p-4" style="width: 500px;">
        <h1 class="card-title text-center text-success mb-4">Register</h1>

        <Alert :message="message" :type="type" @close="message = ''"/>
    <form @submit.prevent="register">
        <div class="mb-3">
        <label>Name</label>
        <input v-model="name" class="form-control" type="text" placeholder="Name" required autofocus>  
        </div>

        <div class="mb-3">
        <label>Email</label>
        <input v-model="email" class="form-control" type="email" placeholder="Email" required>    
        </div>

        <div class="mb-3">
        <label>Password</label>
        <input v-model="password" class="form-control" type="password" placeholder="Password" required>
        </div>

        <div class="mb-3">
        <label>Confirm Password</label>
        <input v-model="password_confirmation" class="form-control" type="password" placeholder="Confirm password" required>
        </div>

        <div class="d-flex justify-content-between">
        <router-link to="/app/login">Already registered?</router-link>
    
        <div class="d-flex">
            <button type="submit" class="btn btn-success btn-custom"  >Register</button>
            <a href="/" class="btn btn-warning ms-2 btn-back">Back</a>
        </div>
        </div>

    </form>
    </div>
    </div>
</div>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useTitle } from '../utils/useTitle.js'
import { useAlert } from '@/useAlert'
import { useRouter } from 'vue-router'

const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')

const { message, type, showError } = useAlert()

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Register')
})

const register = async () => {
    try {
        await axios.get('/sanctum/csrf-cookie')

        await axios.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value        
        })
            
            router.push({ name: 'dashboard' })
        
        
    } catch (error) {

        showError(error.response?.data?.message || 'Register failed')

    }
}

</script>