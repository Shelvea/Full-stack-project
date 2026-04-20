<template>
    <div class="container">
        <h3 class="text-success">Vegetables</h3>

        <Alert :message="message" :type="type" @close="message = ''" />

        <p v-if="searchQuery" class="text-muted">
            Showing results for "<strong>{{ searchQuery }}</strong>"
        </p>

        <div v-if="vegetableList.length === 0" class="alert alert-warning">
            No products found.
        </div>

        <div class="row mt-4">
            <div v-for="vegetable in vegetableList" :key="vegetable.id" class="col-md-4 mb-4">
                <div class="card h-60 shadow d-flex flex-column">
                    <img v-if="vegetable.image" :src="`/storage/${vegetable.image}`" class="card-img-top product-image" :alt="vegetable.name" />
                    <div class="card-body d-flex flex-column">
                        <h5>{{ vegetable.name }}</h5>
                        <p>{{ vegetable.description }}</p>
                        <p>Stock: {{ vegetable.stock }}</p>
                        <strong>NT$ {{ formatPrice(vegetable.price) }}</strong>

                    <div v-if="vegetable.stock > 0 && auth.viewAsCustomer === false" class="mt-2 text-center">
                        <div class="input-group mb-2 d-inline-flex" style="width: 120px;">
                            <button type="button" class="btn btn-success" @click="decreaseQuantity(vegetable)">-</button>
                            <input type="number" v-model.number="vegetable.quantity" :min="1" :max="vegetable.stock" class="form-control text-center">
                            <button type="button" class="btn btn-success" @click="increaseQuantity(vegetable)">+</button>
                        </div>
                            <button class="btn btn-success w-100" @click="addToCart(vegetable)">Add to Cart</button>
                    </div>

                    <button
                        v-else-if = "auth.viewAsCustomer === false"
                        class="btn btn-secondary w-100 mt-auto"
                        disabled >
                        Out of Stock
                    </button>

                </div>
            </div>
        </div>
    </div>

        <div class="mt-4">
            <Pagination :links="paginationLinks" @page-changed="fetchPage" />
        </div>

    </div>
</template>


<script setup>

import axios from 'axios'
import { ref, onMounted, watch } from 'vue'
import { useTitle } from '@/utils/useTitle.js'
import { useAlert } from '@/useAlert'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/components/pinia/authStore'
import Pagination from '@/components/Pagination.vue'

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Vegetable')
    fetchProducts()
})

const route = useRoute()

const auth = useAuthStore()

const { message, type, showSuccess } = useAlert()

const searchQuery = ref(route.query.search || '')

const vegetableList = ref([]) //creates reactive array, store products from api, auto update ui when changed

const paginationLinks = ref([])

//fetch api
async function fetchProducts(url = '/api/products/vegetables'){
    
    try{

        const params = {}//creates an empty obj that store key-value pairs

        if(searchQuery.value)
        {
            params.search = searchQuery.value
        }

        const res = await axios.get(url, {params})

        vegetableList.value = res.data.data.map(p => ({
            ...p, quantity: 1            
        }))

        paginationLinks.value = res.data.links

    }catch(error)
    {
        console.error(error)        
    }
}

function formatPrice(price) {
    return Number(price).toFixed(2)
}

function increaseQuantity(vegetable){
    
    if(vegetable.quantity < vegetable.stock)
    {
        vegetable.quantity++        
    }
}

function decreaseQuantity(vegetable){
    
    if(vegetable.quantity > 1)
    {
        vegetable.quantity--
    }
}

async function addToCart(vegetable){

    if(!vegetable) return

    try{

        const res = await axios.post(`/api/user/cart/add/${vegetable.id}`, {
            quantity: vegetable.quantity
        })
        showSuccess(res.data.message)

    }
    catch(error)
    {
        console.error(error)
    }
}

function fetchPage(url){
    if(url) fetchProducts(url)
}

watch(
    () => route.query.search,
    (newValue) => {
        searchQuery.value = newValue || ''
        fetchProducts()
    }
)
</script>