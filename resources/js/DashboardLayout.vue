<template>
    <Navbar mode="dashboard" />
    
    <div class="flex-fill container pt-5 mt-5">
    <router-view />    
    </div>
    
    <Footer />
</template>

<script setup>
import Navbar from '@/components/layout/Navbar.vue'
import Footer from '@/components/layout/Footer.vue'

</script>