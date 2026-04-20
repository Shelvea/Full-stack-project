<template>
    
        <h3>Notifications</h3>

        <!-- ALERT -->
    <Alert
    :message="message"
    :type="type"
    @close="message = ''"
    />

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
    
</template>

<script setup>//composition api
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useTitle } from '@/utils/useTitle.js' 
import { useAlert } from '@/useAlert'


const notifications = ref([])
const loading = ref(true)

const { message, type, showSuccess, showError } = useAlert()

const formatTime = (date) => {
    return new Date(date).toLocaleString()
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
        showError('Delete failed')

        console.error(error)
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