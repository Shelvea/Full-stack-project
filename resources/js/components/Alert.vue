<template>
    <div
    v-if="message"
    class="alert alert-dismissible fade show"
    :class="alertClass"
    role="alert"
    >
    <strong>{{ title }}</strong> {{ message }}
    <button type="button" class="btn-close" @click="close"></button>
    </div>
</template>

<script setup>

import { computed } from 'vue'

const props = defineProps({//parent to child component
    message: {
        type: String,
        default: ''
    },
    type: {
        type: String,
        default: 'success', // success | error
    }
})

const emit = defineEmits(['close'])

const close = () => {
    emit('close')
}

const alertClass = computed(() => {
    return {
        'alert-success': props.type === 'success',
        'alert-danger': props.type === 'error',
    }
})

const title = computed(() => {
    return {
        success: 'Success!',
        error: 'Failed!'
    }[props.type]
})
</script>
