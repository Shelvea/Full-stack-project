// authStore.js (Pinia) //pinia state is reactive
import { defineStore } from 'pinia' // defineStore is a function used to create a store (global state), create a global data container that any component can use

//useAuthStore : a function you call inside components
export const useAuthStore = defineStore('auth', { //auth is store name (unique id)
    state: () => ({ //stores variables like 'data' in vue
    user: null, //both variables are already reactive
    viewAsCustomer: false
}),
  //state and getters are reactive
  getters: { //like computed , derived values
    isAdmin: (state) => state.user?.is_admin === true,
    role: (state) => { //state is from above state
        if (!state.user) return null
        if (state.viewAsCustomer) return 'viewAsCustomer'
        if (state.user?.is_admin) return 'admin'
        return 'user'
    }
  },

  actions: { // like methods, used to change state
    setUser(user) {
      this.user = user
    },
    enableCustomerView() {
      this.viewAsCustomer = true
    },
    disableCustomerView() {
      this.viewAsCustomer = false
    },
    logout() {
      this.user = null
      this.viewAsCustomer = false
    }
  }
})

