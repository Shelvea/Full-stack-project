<template>
    <div class="collapse navbar-collapse d-flex justify-content-between align-items-center" id="navbarSupportedContent">
            <!-- Left Side -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <!-- Icon -->
                    <i class="fas fa-leaf me-2 text-white"></i> 
                    <span class="navbar-brand mb-0 h5 text-white">
                        FreshMart
                    </span>
                </li>
            </ul>

    <div v-if="role === 'user'" class="flex-grow-1 d-flex justify-content-center">
        <form id="global-search-form" class="d-flex mx-auto position-relative" style="max-width: 500px; width:100%" @submit.prevent="submitSearch">
            <input v-model="searchQuery" @input="handleInput" class="form-control me-2" type="text" placeholder="Search" autocomplete="off">

            <button class="btn btn-light btn-search" type="submit">Search</button>
            
            <!-- Dropdown -->
            <div v-if="showDropdown"
                class="list-group position-absolute w-100 shadow"
                style="top:100%; z-index:1000;">

        <div v-if="!searchResults.length"
        class="list-group-item text-muted">
        No results found
        </div>

        <button v-for="item in searchResults"
        :key="item.id" class="list-group-item list-group-item-action text-start"
        @click="selectResult(item)" type="button">
        {{ item.name }}
        <small class="text-muted">({{ item.category }})</small>
        </button>

    </div>

    </form>
    </div>

            <!-- Right Side -->
            <div v-if="mode === 'dashboard' && user && role !== 'viewAsCustomer'">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                <!-- Dropdown User -->
                    
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ user?.name }}                        
                    </a>
                        
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-end profile-dropdown">
                        <li><router-link class="dropdown-item" :to="{ name: 'profile' }">Profile</router-link></li>
                        <!-- Logout -->
                        <li>
                            <button @click="logout" class="dropdown-item">Log Out</button>                        
                        </li>
                    </ul>
                
                </li>
            </ul>
        </div>

            <div v-if="role === 'viewAsCustomer'">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                    <button @click="exitView" class="btn btn-warning btn-sm fw-bold">
                        Exit Customer View
                    </button>
            </li>
            </ul>            
            </div>
        
        </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/components/pinia/authStore'
import axios from 'axios'

import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

const router = useRouter()

const auth = useAuthStore()

const user = computed(() => auth.user);
const role = computed(() => auth.role);

const searchQuery = ref('')
const searchResults = ref([])
const showDropdown = ref(false)
let debounceTimer = null
const cache = {}
let cancelToken

// SEARCH SUBMIT
const submitSearch = async () => {
  const q = searchQuery.value.trim()
  if (!q) return

try {
    const res = await axios.get('/api/search', {
        params: { q }
    })

    if (!res.data.length) {    
        router.push({
        name: 'fruit',
        query: { search: q }
    })    
        return //return nothing
    }

    const item = res.data[0]

    router.push({
      name: item.category === 'fruit' ? 'fruit' : 'vegetable',
      query: { search: q }
    })

  } catch (err) {
    console.error(err)
  }
}

const handleInput = () => {

    clearTimeout(debounceTimer);

    const query = searchQuery.value.trim()

    if (query.length < 2) {
        showDropdown.value = false
        return
    }

     // Check cache first
    if (cache[query]) {
        searchResults.value = cache[query]
        showDropdown.value = true
        return
    }

    debounceTimer = setTimeout(async () => {
        
        try {            
            // Cancel previous request
            if (cancelToken) {
                cancelToken.cancel("New request triggered")
            }

            cancelToken = axios.CancelToken.source()

            const res = await axios.get('/api/search', {
            params: { q: query },
            cancelToken: cancelToken.token

            })

            // Save to cache
            cache[query] = res.data

            searchResults.value = res.data
            showDropdown.value = true

        } catch (err) {

            if (!axios.isCancel(err)) {
                console.error(err)
            }
        }
    }, 350)

}

const selectResult = (item) => {

  router.push({
    name: item.category === 'fruit' ? 'fruit' : 'vegetable',
    query: { search: item.name }//query parameters
  })

  showDropdown.value = false
}

const handleClickOutside = (event) => {
  if (!event.target.closest('#global-search-form')) {
    showDropdown.value = false
  }
}

const exitView = () => {
  auth.disableCustomerView();
  router.push({ name: 'admin-dashboard' })
}

const logout = async () => {

  //await axios.get('/sanctum/csrf-cookie')
  await axios.post('/logout', {}, { withCredentials: true });
  auth.logout()
  window.location.href = '/'
}

defineProps({
    mode:{
        type: String,
        default: 'public'
    }
})

</script>