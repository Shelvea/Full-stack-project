<template>
    <div class="offcanvas offcanvas-start bg-success text-white" id="adminSidebar" data-bs-backdrop="false">
    
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
            
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <!--<li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-chart-line me-2"></i>Sales & Analytics</a></li>-->
            <li class="nav-item mb-2">
                <button class="nav-link text-white" @click="goToPreview"><i class="fas fa-tachometer-alt me-2"></i>View Customer Dashboard</button>           
            </li>
            <li class="nav-item mb-2">
                <router-link class="nav-link text-white" :to="{ name: 'notification' }"><i class="fas fa-bell me-2 position-relative"><!-- Badge --><span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">1</span></i>Notifications</router-link>
            </li>
            <li class="nav-item mb-2">
                <router-link class="nav-link text-white" :to="{ name: 'product' }"><i class="fas fa-boxes me-2"></i>Product Management</router-link>
            </li>
            <li class="nav-item mb-2">
                <router-link class="nav-link text-white" :to="{ name: 'order' }"><i class="fas fa-clipboard-list me-2"></i>Order Management</router-link>
            </li>
            <li class="nav-item mb-2">
                <router-link class="nav-link text-white" :to="{ name: 'user' }"><i class="fas fa-users me-2"></i>Customer Management</router-link>
            </li>
        </ul>
    </div>
</div>
</template>

<script setup>
import { useAuthStore } from '../pinia/authStore'
import { useRouter } from 'vue-router'
import { Offcanvas } from 'bootstrap'

const auth = useAuthStore()

const router = useRouter()

const goToPreview = () => {
    const sidebar = document.getElementById('adminSidebar')
    const instance = Offcanvas.getInstance(sidebar)
    instance?.hide()

    auth.enableCustomerView();
    router.push({ name: 'preview-customer-dashboard' })
}
</script>