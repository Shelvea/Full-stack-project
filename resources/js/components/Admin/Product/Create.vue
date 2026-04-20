<template>
  <div class="container my-4">
    <h4>Create Product</h4>
 
    <Alert
    :message="message" :type="type" @close="message = ''"
    />
    <router-link :to="{ name: 'product' }" class="btn btn-warning mt-4 mb-2 custom-hover">Back</router-link>
    
    <form @submit.prevent="submitForm">

      <input type="file" @change="handleImage" class="form-control mb-2">

      <p>Name: <input v-model="form.name" placeholder="Name" class="form-control mb-2"></p>

      <p>Description: <input v-model="form.description" placeholder="Description" class="form-control mb-2"></p>

      <p>Price: <input v-model="form.price" type="number" placeholder="Price" class="form-control mb-2"></p>

      <p>Stock: <input v-model="form.stock" type="number" placeholder="Stock" class="form-control mb-2"></p>

      <p>Category: 
        <select v-model="form.category_id" class="form-control mb-2" v-if="categories.length">
        <option value="">Select Category</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
        {{ cat.name }}
        </option>
        </select>
        </p>

      <button class="btn btn-light custom-save">Save</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useTitle } from '@/utils/useTitle'
import { useAlert } from '@/useAlert';

const { message, type, showSuccess } = useAlert();

const router = useRouter()

const form = ref({
  name: '',
  description: '',
  price: '',
  stock: '',
  category_id: '',
  image: null
})

const categories = ref([])

const handleImage = (e) => {
  form.value.image = e.target.files[0]
}

const fetchCategories = async () => {
  const res = await axios.get('/api/admin/categories')
  categories.value = res.data
}

const submitForm = async () => {
  const formData = new FormData()

  Object.keys(form.value).forEach(key => {
    formData.append(key, form.value[key])
  })

  try {
  
const response = await axios.post('/admin/products', formData)
  showSuccess(response.data.message)
  router.push({ name: 'product' });

} catch (error){
    console.error(error);
}
}

onMounted(() => {
  useTitle('Create Product')
  fetchCategories()
})
</script>