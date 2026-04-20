<template>
  <div class="container my-4">
    <h4 class="mb-4">Products List</h4>
    
    <Alert
    :message="message" :type="type" @close="message = ''"
    />

    <router-link
      :to="{ name: 'create-product' }"
      class="btn btn-outline-success mb-3"
    >
      Add Product
    </router-link>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Image</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="product in products.data" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.description }}</td>
          <td>{{ product.price }}</td>
          <td>{{ product.stock }}</td>

          <td>
            <img
              v-if="product.image"
              :src="`/storage/${product.image}`"
              width="50"
            />
          </td>

          <td>{{ product.category?.name || '-' }}</td>

          <td>
            <router-link
              :to="{ name: 'show-product', params: { id: product.id },
              query: route.query }"
              class="btn btn-warning btn-sm me-2"
            >
              View
            </router-link>

            <router-link
              :to="{ name: 'edit-product', params: { id: product.id },
              query: route.query }"
              class="btn btn-info btn-sm me-2"
            >
              Edit
            </router-link>

            <button
              class="btn btn-danger btn-sm"
              @click="confirmDelete(product.id)"
            >
              Delete
            </button>
          </td>
        </tr>

        <tr v-if="!products.data?.length">
          <td colspan="8" class="text-center">No product found!</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-4">
      <button
        class="btn btn-sm btn-outline-secondary me-2"
        :disabled="!products.prev_page_url"
        @click="fetchProducts(products.current_page - 1)"
      >
        Previous
      </button>

      <button
        class="btn btn-sm btn-outline-secondary"
        :disabled="!products.next_page_url"
        @click="fetchProducts(products.current_page + 1)"
      >
        Next
      </button>
    </div>
  </div>

  <!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Delete Product?</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        This action cannot be undone.
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
        <button class="btn btn-danger" @click="deleteProduct">
          Delete
        </button>
      </div>

    </div>
  </div>
</div>

</template>

<script setup>
import { Modal } from 'bootstrap'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useTitle } from '@/utils/useTitle.js'
import { useAlert } from '@/useAlert'
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router'
const router = useRouter()

const route = useRoute();

const { message, type, showSuccess } = useAlert()

let deleteModalInstance = null

const products = ref({ data: [] })
const productToDelete = ref(null)

const fetchProducts = async (page = 1) => {
  const response = await axios.get(`/admin/products?page=${page}`)
  products.value = response.data

   // update URL query so it's remembered
  router.replace({ query: { page } })
}

const confirmDelete = async (id) => {

    productToDelete.value = id
    deleteModalInstance.show()
  }  

const deleteProduct = async () => {

    if (!productToDelete.value) return

    const response = await axios.delete(`/admin/products/${productToDelete.value}`)

    deleteModalInstance.hide()

    showSuccess(response.data.message);
    productToDelete.value = null
  // reload current page
    fetchProducts(products.value.current_page || 1)
}

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Product Management')

    deleteModalInstance = new Modal(
    document.getElementById('deleteModal')
    )

    const initialPage = parseInt(route.query.page) || 1

    fetchProducts(initialPage)
})

</script>