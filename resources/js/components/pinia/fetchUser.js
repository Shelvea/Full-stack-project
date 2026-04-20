export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null
    }),
    actions:{
        async fetchUser(){
            const res = await axios.get('/api/user')
            this.user = res.data
        }
    }
})