import api from '@/axios'

export const addToCart = (id, quantity) => {
    return api.post(`/user/cart/add/${id}`, { quantity })
}