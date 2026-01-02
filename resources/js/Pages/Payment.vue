<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  orders: Object
})

const formatMoney = (value) => {
  const number = Number(value)
  return Number.isFinite(number) ? number.toFixed(2) : '0.00'
}
</script>

<template>
  <Head title="Payment" />

<div class="container mt-5 pt-5">

  <h4 class="text-success">Pending Payments</h4>

  <div v-for="order in orders.data" :key="order.id" class="card mb-3 mt-3">
    <div class="card-body">
      <h5>Order #{{ order.id }}</h5>
      <span class="badge bg-warning mb-2">Pending Payment</span>
      <p>Total: ${{ formatMoney(order.total) }}</p>
    </div>
  </div>

</div>
</template>
