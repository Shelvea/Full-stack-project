<template>

    <div class="row justify-content-center">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    Admin You're logged in!
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { onMounted } from 'vue'
import { useTitle } from '@/utils/useTitle.js'

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Home')

})

</script>