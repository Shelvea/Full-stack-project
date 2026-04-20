<template>

    <div class="container">
        <h3 class="text-success">Cart</h3>

        <Alert :message="message" :type="type" @close="message = ''" />

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>                
                <tr v-for="item in cart.cart_items" :key="item.id">
                    <td>{{ item.product.name }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-minus" @click="decreaseQuantity(item)" >-</button>
                            <input type="number" v-model.number="item.quantity" :min="1" :max="item.product.stock" class="form-control text-center quantity-input">
                            <button type="button" class="btn btn-success btn-plus" @click="increaseQuantity(item)" >+</button>
                        </div>
                    </td>
                    <td>NT$ {{ formatPrice(item.product.price) }}</td>
                    <td class="item-total">NT$ {{ formatPrice(itemTotal(item)) }}</td>
                    <td>
                        <form @submit.prevent="remove(item)">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                
                
                <tr v-if="cart.cart_items.length === 0">
                <td colspan="5" class="text-center">Your cart is empty.</td>
                </tr>
                
            </tbody>
        </table>

        <div v-if="cart.cart_items.length" class="d-flex justify-content-between mt-4">
            <h5 class="mb-0" >Subtotal: <strong>NT${{ formatPrice(subtotal) }}</strong></h5>
            <a class="btn btn-success" @click="proceedToCheckout">Proceed to Checkout</a>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useTitle } from '@/utils/useTitle.js'
import { useAlert } from '@/useAlert'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

onMounted(async () => {//same as mounted() in options api lifecycle hook
    useTitle('Cart')
    fetchCart()
})

const { message, type, showSuccess } = useAlert()

const itemTotal = (item) => {

    return item.product.price * item.quantity;
};

const subtotal = computed(() => {
    return cart.value?.cart_items?.reduce((total, item) => {
        return total + (item.product.price * item.quantity)
    }, 0)
})

const cart = ref({
    cart_items:[]

})

async function fetchCart(url = '/api/user/cart'){

    try{

        const response = await axios.get(url)

        cart.value.cart_items = response.data.cart.cart_items;

        //console.log(response.data)

    }
    catch(error){
        console.error(error)
    }
}

async function remove(item){

    if (!item) return

    try{
        
        const response = await axios.delete(`/api/user/cart/remove/${item.id}`)

        showSuccess(response.data.message)

        cart.value.cart_items = cart.value.cart_items.filter(currentItem => currentItem.id !== item.id);

    }catch(error){

        console.error(error)
    }
}

function increaseQuantity(item){
    if (item.quantity < item.product.stock) {
        item.quantity++
  }
}

function decreaseQuantity(item){
    if (item.quantity > 1) {
        item.quantity--
  }
}

function formatPrice(price){
    return Number(price).toFixed(2)
}

function proceedToCheckout(){
    router.push({ name:'checkout' })

}

</script>