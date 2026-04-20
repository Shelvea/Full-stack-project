<template>
    <footer class="bg-success text-white text-center py-4 mt-5">
    <p class="mb-1">📍 123 Fresh Street, Green City</p>
    <p class="mb-1">📞 +123 456 7890 | ✉️ info@freshmart.com</p>
    <p>&copy; {{ year }} FreshMart. All rights reserved.</p>
    </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>
