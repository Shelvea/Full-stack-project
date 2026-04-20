<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-success border-bottom fixed-top">

        <button v-if="mode === 'dashboard' && role === 'viewAsCustomer'" class="btn btn-light me-2 ms-3 btn-ham" data-bs-toggle="offcanvas" data-bs-target="#ViewCustomerSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <button v-else-if="mode === 'dashboard' && role === 'user'" class="btn btn-light me-2 ms-3 btn-ham" data-bs-toggle="offcanvas" data-bs-target="#userSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <button  v-else-if="mode === 'dashboard' && role === 'admin'" class="btn btn-light me-2 ms-3 btn-ham" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="container">
            <!-- Toggler (hamburger) -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" >
            <span class="navbar-toggler-icon"></span>
        </button>

        <NavbarSupportedContent :mode="mode"/>

        </div>
    </nav>
    
    <!-- Sidebars -->
        <SidebarUser v-show = "mode === 'dashboard' && role === 'user'" />
        <SidebarAdmin v-show = "mode === 'dashboard' && role === 'admin'" />
        <SidebarViewAsCustomer v-show = "mode === 'dashboard' && role === 'viewAsCustomer'" />
        
</template>

<script setup>
import { useAuthStore } from '../pinia/authStore'
import { computed } from 'vue'

import SidebarUser from './SidebarUser.vue'
import SidebarAdmin from './SidebarAdmin.vue'
import SidebarViewAsCustomer from './SidebarViewAsCustomer.vue'
import NavbarSupportedContent from './NavbarSupportedContent.vue'

const auth = useAuthStore()

const role = computed(() => auth.role)

defineProps({
    mode:{
        type: String,
        default: 'public'
    }
})
</script>