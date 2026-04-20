<template>
  <div class="container">
    <h3 class="text-success">Fruits</h3>

    <Alert
    :message="message" :type="type" @close="message = ''" />

    <p v-if="searchQuery" class="text-muted">
      Showing results for "<strong>{{ searchQuery }}</strong>"
    </p>

    <div v-if="products.length === 0" class="alert alert-warning">
      No products found.
    </div>

    <div class="row mt-4">
      <div v-for="product in products" :key="product.id" class="col-md-4 mb-4">
        <div class="card h-60 shadow d-flex flex-column">
          <img
            v-if="product.image"
            :src="`/storage/${product.image}`"
            class="card-img-top product-image"
            :alt="product.name"
          />
          <div class="card-body d-flex flex-column">
            <h5>{{ product.name }}</h5>
            <p>{{ product.description }}</p>
            <p>Stock: {{ product.stock }}</p>
            <strong>NT$ {{  formatPrice(product.price) }}</strong>

            <div v-if="product.stock > 0 && auth.viewAsCustomer === false" class="mt-2 text-center">
              <div class="input-group mb-2 d-inline-flex" style="width: 120px;">
                <button type="button" class="btn btn-success" @click="decreaseQuantity(product)">-</button>
                <input
                  type="number"
                  v-model.number="product.quantity"
                  :min="1"
                  :max="product.stock"
                  class="form-control text-center"
                >
                <button type="button" class="btn btn-success" @click="increaseQuantity(product)">+</button>
              </div>
              <button class="btn btn-success w-100" @click="handleAddToCart(product)">Add to Cart</button>
            </div>

            <button
              v-else-if = "auth.viewAsCustomer === false"
              class="btn btn-secondary w-100 mt-auto"
              disabled
            >
              Out of Stock
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <!-- Pagination component can be a custom Vue component or links rendered from server -->
      <Pagination :links="paginationLinks" @page-changed="fetchPage" />
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useTitle } from '@/utils/useTitle'
import { useAlert } from '@/useAlert'
import { useAuthStore } from '@/components/pinia/authStore'
//import { addToCart as addToCartApi } from '@/services/cartService'
//import { getProducts as getProductsApi } from '@/services/productService'
import Pagination from '@/components/Pagination.vue'

onMounted(async () => {
  useTitle('Fruits')
  fetchProducts()
})

const route = useRoute()//current url

const auth = useAuthStore()

const { message, type, showSuccess } = useAlert()

const products = ref([])
const paginationLinks = ref([])

const searchQuery = ref(route.query.search || '')

async function fetchProducts(url = '/api/products/fruits') {
  try {

  const params = {}

  if (searchQuery.value) {
    params.search = searchQuery.value
  }

    const response = await axios.get(url, { params })

    //const response = await getProductsApi(url, params)

    products.value = response.data.data.map(p => ({//loop through each product
      ...p, quantity: 1 //spread operator in javascript
    }))

    paginationLinks.value = response.data.links

  } catch (error) {
    console.error(error)
  }
}

function increaseQuantity(product) {
  if (product.quantity < product.stock) {
    product.quantity++
  }
}

function decreaseQuantity(product) {
  if (product.quantity > 1) {
    product.quantity--
  }
}

async function handleAddToCart(product) {

  if(!product) return

  try {
    
    const res = await axios.post(`/api/user/cart/add/${product.id}`, {
      quantity: product.quantity
    })
    //const res = await addToCartApi(product.id, product.quantity)
    showSuccess(res.data.message)

  } catch (error) {
    console.error(error)
  }
}

function formatPrice(price) {
  return Number(price).toFixed(2)
}

watch(
  () => route.query.search,
  (newValue) => {
    searchQuery.value = newValue || ''
    fetchProducts()
  }
)

function fetchPage(url) {
  if (url) fetchProducts(url)
}

</script>
