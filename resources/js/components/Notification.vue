<template>
    <div class="container pt-5 mt-5">
        <h3>Notifications</h3>

        <!-- ✅ SUCCESS ALERT -->
    <div
        v-if="successMessage"
        class="alert alert-success alert-dismissible fade show"
        role="alert"
    >
    <strong>Success!</strong> {{ successMessage }}
    <button
        type="button"
        class="btn-close"
        @click="successMessage = ''"
    ></button>
    </div>

        <div v-if="loading">
            Loading notifications...
        </div>

        <div v-else-if="notifications.length === 0">
            No notifications
        </div>

    <div v-else>
    <div v-for="n in notifications" :key="n.id" class="card col-12 col-md-8 col-lg-6 mb-3">
    
    <div class="card-body">   
    
    <div class="content">
        <h5 class="card-title"> 
            {{ n.data.message }}
        </h5>
        <small class="time">
            ( {{ formatTime(n.created_at) }} )
        </small>
    </div>

    <div class="actions d-flex gap-2 mt-3">
    <button @click="viewOrder(n)" class="btn btn-primary">
        View Order
    </button>

    <button @click="deleteNotification(n.id)" class="btn btn-danger">
        Delete
    </button>
    </div>

    </div>

    </div>
    </div>
    </div>    
</template>

<script setup>//composition api
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useTitle } from '../utils/useTitle.js' 

const notifications = ref([])
const loading = ref(true)
const successMessage = ref('')

const formatTime = (date) => {
    return new Date(date).toLocaleString()
}

const showSuccess = (msg) => {
    successMessage.value = msg
    setTimeout(() => {
    successMessage.value = ''
    }, 3000)
}

const viewOrder = async (notification) => {
    //mark notification as read
    await axios.post(`/api/notifications/${notification.id}/read`)

   // navigate to Blade Order Management page with highlighted order ID
    window.location.href = `/admin/orders?highlight=${notification.data.order_id}`

}

const deleteNotification = async (id) => {
    
    try {
    const res = await axios.delete(`/api/notifications/${id}`)

    notifications.value = notifications.value.filter(
        n => n.id !== id
    )

    showSuccess(res.data.message)
    
    } catch (error) {

        console.error('Delete failed:', error)
    }
}

onMounted(async () => {
    useTitle('Notifications')
    
    try{
    
        const res = await axios.get('/api/notifications')
        notifications.value = res.data.notifications
    
    }finally{
        
        loading.value = false
    }
})

</script>

<style scoped>

</style>