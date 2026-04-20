<template>
    Order
</template>

<script setup>
import { onMounted } from 'vue'
import { useTitle } from '@/utils/useTitle.js'

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Order')
})

</script>