import { ref } from 'vue'

const message = ref('')
const type = ref('success')

export function useAlert() {
        const showSuccess = (msg) => {
        message.value = msg
        type.value = 'success'
        autoClear()
    }

    const showError = (msg) => {
        message.value = msg
        type.value = 'error'
        autoClear()
    }

    const autoClear = () => {
        setTimeout(() => {
        message.value = ''
        }, 3000)
    }

    return {
        message,
        type,
        showSuccess,
        showError
    }
}
