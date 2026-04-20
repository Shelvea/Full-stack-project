<template>
  <div class="container my-4">
    <h4>Edit Product</h4>
    
    <Alert
    :message="message" :type="type" @close="message = ''"
    />

    <button class="btn btn-warning mb-3" @click="goBack">Back</button>

    <div class="card bg-success text-white mt-4">
      <div class="card-body border border-light rounded">
        <form @submit.prevent="updateProduct" enctype="multipart/form-data">
          <!-- Image -->
          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" class="form-control bg-light" @change="onImageChange">
            <div v-if="product.image || previewImage" class="mt-2">
            <img 
                :src="previewImage || `/storage/${product.image}`" alt="Product Image" 
                width="120" class="rounded"
              >
            </div>
            <div v-if="errors.image" class="text-danger">{{ errors.image[0] }}</div>
          </div>

          <!-- Name -->
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control bg-light" v-model="product.name">
            <div v-if="errors.name" class="text-danger">{{ errors.name[0] }}</div>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" class="form-control bg-light" v-model="product.description">
            <div v-if="errors.description" class="text-danger">{{ errors.description[0] }}</div>
          </div>

          <!-- Price -->
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" class="form-control bg-light" v-model.number="product.price">
            <div v-if="errors.price" class="text-danger">{{ errors.price[0] }}</div>
          </div>

          <!-- Stock -->
          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control bg-light" v-model.number="product.stock">
            <div v-if="errors.stock" class="text-danger">{{ errors.stock[0] }}</div>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select class="form-control" v-model="product.category_id">
              <option value="">-- Select Category --</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
            <div v-if="errors.category_id" class="text-danger">{{ errors.category_id[0] }}</div>
          </div>

          <button type="submit" class="btn btn-light">Update</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { useAlert } from '@/useAlert';

const route = useRoute()
const router = useRouter()

const { message, type, showSuccess } = useAlert();

const product = ref({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  category_id: null,
  image: ''
})

const previewImage = ref(null)
const categories = ref([])
const errors = ref({})

// Load product & categories on mount
onMounted(async () => {
  try {
    // Fetch product
    const res = await axios.get(`/admin/products/${route.params.id}`)
    product.value = res.data.product

    // Fetch categories
    const catRes = await axios.get('/api/admin/categories')
    categories.value = catRes.data

  } catch (err) {
    console.error(err)
  }
})

// Handle image input preview
const onImageChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    previewImage.value = URL.createObjectURL(file)
    product.value.imageFile = file
  }
}

// Submit form
const updateProduct = async () => {
  const formData = new FormData()
  formData.append('_method', 'PUT')
  formData.append('name', product.value.name)
  formData.append('description', product.value.description)
  formData.append('price', product.value.price)
  formData.append('stock', product.value.stock)
  formData.append('category_id', product.value.category_id)

  if (product.value.imageFile) {
    formData.append('image', product.value.imageFile)
  }

  try {

    const res = await axios.post(`/admin/products/${route.params.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    showSuccess(res.data.message)
    router.push({ name: 'product' , query: route.query}); // back to list
    
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors
    }
  }
}

const goBack = () => router.push({ name: 'product' , query: route.query })
</script>