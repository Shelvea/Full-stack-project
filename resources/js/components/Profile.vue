<template>
    
    <div class="container my-5">

    <h2 class="h5 mb-4">Profile</h2>
    
    <Alert :message="message" :type="type" @close="message = ''"/>
    <!-- Update Profile Info -->
    
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5 class="mb-3">Update Profile Information</h5>

        <form @submit.prevent="updateProfile">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input v-model="form.name" type="text" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input v-model="form.email" type="email" class="form-control">
          </div>

          <button class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>

    <!-- Update Password -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5 class="mb-3">Update Password</h5>

        <form @submit.prevent="updatePassword">
          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input v-model="passwordForm.current_password" type="password" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input v-model="passwordForm.password" type="password" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input v-model="passwordForm.password_confirmation" type="password" class="form-control">
          </div>

          <button class="btn btn-warning">Update Password</button>
        </form>
      </div>
    </div>

    <!-- Delete Account -->
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-3 text-danger">Delete Account</h5>

        <button @click="deleteAccount" class="btn btn-danger">
          Delete Account
        </button>
      </div>
    </div>

    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useTitle } from '@/utils/useTitle.js'

import { reactive } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/components/pinia/authStore'
import { useAlert } from '@/useAlert' 

const { message, type, showSuccess } = useAlert()

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Profile')
})

const auth = useAuthStore()

const form = reactive({
  name: auth.user?.name || '',
  email: auth.user?.email || ''
})

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const updateProfile = async () => {
  const response = await axios.patch('/profile', form)

  auth.setUser(response.data.user)

  showSuccess(response.data.message)
  
}

const updatePassword = async () => {
  const response = await axios.put('/password', passwordForm)
  showSuccess(response.data.message)
  
}

const deleteAccount = async () => {
  if (!confirm('Are you sure?')) return

  if (!passwordForm.current_password) {
    alert('Please enter your current password')
    return
  }

  await axios.delete('/profile',{
    data: { password: passwordForm.current_password }
  })

  auth.logout()
  window.location.href = '/'

}

</script>