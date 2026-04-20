import api from '@/axios'

export const getProducts = (url, params) => {
    
    return api.get(url, { params })
}