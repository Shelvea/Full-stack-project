<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-3">
        <h4>Product Details</h4>

        <div class="card bg-success text-white mt-4">
          <div class="card-body border border-success rounded">

            <!-- Show image -->
            <div v-if="product.image" class="mt-2">
              <img 
                :src="`/storage/${product.image}`" 
                alt="Product Image" 
                width="120" 
                class="rounded"
              >
            </div>

            <h5 class="card-title">
              <strong>Name:</strong> {{ product.name }}
            </h5>

            <p class="card-text">
              <strong>Description:</strong> {{ product.description }}
            </p>

            <p class="card-text">
              <strong>Price:</strong> {{ product.price }}
            </p>

            <p class="card-text">
              <strong>Stock:</strong> {{ product.stock }}
            </p>

            <p class="card-text">
              <strong>Category:</strong> {{ product.category?.name }}
            </p>

            <button 
              @click="goBack" 
              class="btn btn-warning custom-hover"
            >
              Back to List
            </button>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const product = ref({})

onMounted(async () => {
  try {
    const response = await axios.get(`/admin/products/${route.params.id}`)
    product.value = response.data.product
  } catch (error) {
    console.error(error)
  }
})

const goBack = () => {
  router.push({
    name: 'product',
    query: { page: route.query.page || 1 }
  })
}
</script>
