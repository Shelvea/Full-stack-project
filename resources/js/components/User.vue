<template>
  <div class="container pt-5 mt-5">
  
    <div v-if="alert.message" class="alert" :class="alert.type"
    role="alert">    
  {{ alert.message }}
  </div>

  <table class="table table-striped">
    <thead>
      <tr class="table-success">
        <th>Username</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td><button class="btn btn-sm btn-danger fw-bold" @click="openDeleteModal(user)">Delete</button></td>
      </tr>
    </tbody>
  </table>
  
  <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Are you sure you want to delete
        <strong>{{ selectedUser?.name }}</strong>?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>

        <button type="button" class="btn btn-danger" @click="confirmDelete">
          Delete
        </button>
      </div>

    </div>
  </div>
</div>

  </div>
</template>

<script setup>//composition api
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useTitle } from '../utils/useTitle.js' 

const users = ref([])//ref() creates a reactive variable
const alert = ref({
  message: '',
  type: ''
})
const selectedUser = ref(null)
let deleteModal = null

onMounted(async () => {//same as mounted() in options api lifecycle hook
  useTitle('Users')
  const res = await axios.get('/api/users')
  users.value = res.data.users

  // Bootstrap modal instance
  deleteModal = new bootstrap.Modal(
    document.getElementById('deleteModal')
  )
})

const openDeleteModal = (user) => {
  selectedUser.value = user
  deleteModal.show()
}

const confirmDelete = async () => {
  try {
    const res = await axios.delete(`/api/users/${selectedUser.value.id}`)

    users.value = users.value.filter(
      user => user.id !== selectedUser.value.id
    )

      alert.value = {
      message: res.data.message,
      type: 'alert-success'
    }

    deleteModal.hide()

    setTimeout(() => {
      alert.value.message = ''
    }, 3000)

  } catch (error) {
    alert.value = {
      message: error.response?.data?.message || 'Delete failed',
      type: 'alert-danger'
    }

    setTimeout(() => {
      alert.value.message = ''
    }, 3000)
  }
}

</script>
