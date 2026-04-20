//axios interceptor global error handler
import axios from 'axios'
import { useAlert } from '@/useAlert'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json'
    }
});


api.interceptors.response.use(
    response => response,
    error => {

        const status = error.response?.status

        //ignore auth errors
        if(status === 401){
            return Promise.reject(error)
        }

        const { showError } = useAlert()

        const message = error.response?.data?.message || 'An error occurred'

        showError(message)

        // The error is STILL passed to your component
        // try/catch will still run
        return Promise.reject(error)
        
    }
)

export default api