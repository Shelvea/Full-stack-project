<template>
        
        <!-- Only render the page content here -->        
        <router-view></router-view>
        
</template>

<script setup>

import { onMounted } from 'vue'
import axios from 'axios'
//import api from '@/axios'
import { useAuthStore } from '@/components/pinia/authStore'
import { startNotifications } from '@/notifications';

const auth = useAuthStore()

onMounted(async () => {
        console.log('App mounted')

        startNotifications();

try {
        const response = await axios.get('/api/user', { withCredentials: true })
        console.log('USER:', response.data)
        //const response = await api.get('/user')
        auth.setUser(response.data)
        console.log('AUTH USER:',auth.user)

} catch (error) {
        //console.log('NOT AUTHENTICATED')
        auth.logout()
}

})

</script>
