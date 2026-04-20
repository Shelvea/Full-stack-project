<template>

<div class="container">

    <h3 class="text-success">Checkout</h3>

    <Alert :message="message" :type="type" @close="message = ''" />

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in cart.cart_items" :key="item.id">
                <td>{{ item.product.name }}</td>
                <td>{{ item.quantity }}</td>
                <td>NT$ {{ formatPrice(item.product.price) }}</td>
                <td>NT$ {{ formatPrice(itemTotal(item)) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">
        <a class="btn btn-success" @click="goBack">Back</a>
        <h5 class="mb-0">Subtotal: <strong> NT$ {{ formatPrice(subtotal) }}</strong></h5>
    </div>

    <form @submit.prevent="placeOrder" class="mt-5 mx-auto">
        <div class="card p-3">
            <h5 class="text-success text-center mb-3">Delivery Information</h5>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" :value="auth.user.name" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" :value="auth.user.email" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input v-model="phone" type="tel" class="form-control" pattern="^\+8869\d{8}$" required>
                <div class="form-text text-success">Format: +8869******** (E.164 format)</div>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label">Delivery Address</label>
                <input v-model="address" @blur="blurMethod" type="text" class="form-control" placeholder="Enter address" required>

                <ul v-show="suggestionList" class="list-group position-absolute w-100">
                    <li v-for="item in Listitems" :key="item.id" class="list-group-item" @click="selectItem(item)">
                        {{ item }}
                    </li>
                </ul>
            </div>

            <p v-if="loading">Calculating delivery...</p>
            
            <div class="mb-3 mt-3">
                <p>Estimated Delivery Fee: <strong>{{ deliveryFee }}</strong></p>
                <p>Estimated Delivery Distance: <strong>{{ deliveryDistance }}</strong></p>
                <p>Total Fee: <strong>{{ totalAmount }}</strong></p>
            </div>

            <!-- Hidden fields for form submission -->
            <input v-model="deliveryFee2">
            <input v-model="deliveryDistance2">
            <input v-model="subtotal">
            <input v-model="total">

            <input v-model="quotationId">
            <input v-model="senderStopId">
            <input v-model="recipientStopId">

            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select v-model="paymentMethod" class="form-select" required>
                    <option value="cod">Cash on Delivery</option>
                    <option value="transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success">Place Order</button>
            </div>

        </div>
    </form>

</div>

</template>

<script setup>

import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useTitle } from '@/utils/useTitle.js'
import { useAlert } from '@/useAlert'
import axios from 'axios'
import { useAuthStore } from '@/components/pinia/fetchUser'


const router = useRouter()

const auth = useAuthStore()

onMounted(async () => {
    useTitle('Checkout')
    fetchCart()

})

const { message, type, showSuccess, showError} = useAlert()

const phone = ref('')
const paymentMethod = ref('cod')

function formatPrice(price){
    return Number(price).toFixed(2)
}

function blurMethod(){
    getLalamoveQuote()
    return
}

async function placeOrder(){
    
    try{

        const res = await axios.post('/api/user/placeOrder', {

            recipient_name: auth.user.name,
            recipient_email: auth.user.email,
            recipient_phone: phone.value,
            recipient_address: address.value,
            delivery_fee: deliveryFee2.value,
            delivery_distance: deliveryDistance2.value,
            payment_method: paymentMethod.value,
            quotation_id: quotationId.value,
            sender_stop_id: senderStopId.value,
            recipients_stop_id: recipientStopId.value,
            subtotal: subtotal.value,
            total: total.value,
        });

        router.push({ 
            name: 'orderSuccess',
            query: {
                message: res.data.message,
                type: 'success'
            }
        })

    }
    catch(error)
    {
        showError(error.response?.data?.message || 'Error')
    }

}

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

const goBack = () => router.back()

const loading = ref(false)

const deliveryFee = ref('NT$ 0')
const deliveryFee2 = ref('')
const deliveryDistance = ref('--')
const deliveryDistance2 = ref('')

const quotationId = ref('')
const senderStopId = ref('')
const recipientStopId = ref('')

const totalAmount = ref('--')
const total = ref('')
const deliveryFeeValue = ref('')


const address = ref('')
const suggestionList = ref(false);

const Listitems = ref([])

const apiKey = "9ef97a98cb2a40848d555df5e8631a38";
const vendorAddress = "National University Of Kaohsiung, 700 Kaohsiung University Road, Nanzi District, Kaohsiung 81148, Taiwan";
const lalamoveEndpoint = "/api/lalamove/estimate";

const totalFee = computed(() => {
    return subtotal.value + deliveryFeeValue.value
})

let debounceTimer;

watch(address, (newValue) => {

    clearTimeout(debounceTimer);

    const query = address.value.trim();
    
    if(query.length < 3){
        suggestionList.value = false;
        return;
    }
    
    debounceTimer = setTimeout(async () => {
        try{

            const res = await axios.get(`https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(query)}&apiKey=${apiKey}`, { signal: AbortSignal.timeout(5000) });
            suggestionList.value = false;

            if(!res.data.features || res.data.features.length === 0){
                suggestionList.value = false;
                return;
            }

            
            Listitems.value = res.data.features.map(f => f.properties.formatted);
            

            suggestionList.value = Listitems.value.length > 0;


        }catch(error){
            console.error('Geoapify API error:', error);
        }

    }, 300);    
})

function selectItem(item){

    address.value = item;
    suggestionList.value = false;
    getLalamoveQuote(); // fetch quote immediately

}

async function getLalamoveQuote(retries = 3, delay = 1000){

    address.value = address.value.trim();

    if(!address.value) return;

    loading.value = true;

    try{

        // Geocode destination
        const geoRes = await axios.get(`https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(address.value)}&apiKey=${apiKey}`, { signal: AbortSignal.timeout(5000) });
        if(!geoRes.data.features.length) throw new Error('Invalid destination address');

        // Geocode pickup
        const geoRes1 = await axios.get(`https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(vendorAddress)}&apiKey=${apiKey}`, { signal: AbortSignal.timeout(5000) });
        if(!geoRes1.data.features.length) throw new Error('Invalid pickup address');

        const dest = geoRes.data.features[0].geometry.coordinates;
        const pickup = geoRes1.data.features[0].geometry.coordinates;

        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 5000); // 5s timeout

        const res = await axios.post(
            lalamoveEndpoint, 
            { 
                destination: dest, 
                pickup: pickup, 
                pickupAddress: vendorAddress, 
                destAddress: address.value
            },
            {
                headers: { 'Content-Type': 'application/json'},
                signal: controller.signal
        });

        clearTimeout(timeout);

        if (!res) throw new Error(`HTTP error! status: ${res.status}`);

        const data = res.data;

        deliveryFee.value = data.fee ? `NT$ ${data.fee}` : `NT$ 0`;
        deliveryFee2.value = data.fee || 0;
        deliveryDistance.value = data.distance_m ? `${(data.distance_m / 1000).toFixed(1)} km` : '--';
        deliveryDistance2.value = data.distance_m ? Number((data.distance_m / 1000).toFixed(1)) : 0;

        quotationId.value = data.quotation_id || '--';
        senderStopId.value = data.sender_stop_id || '--';
        recipientStopId.value = data.recipients_stop_id || '--';

        deliveryFeeValue.value = parseFloat(data.fee) || 0;
        total.value = totalFee.value.toFixed(2);
        totalAmount.value = `NT$ ${totalFee.value.toFixed(2)}`;


    }
    catch(error){
        console.error('Lalamove API error:', error);
        
        if (retries > 0) {
                console.log(`Retrying in ${delay}ms... (${retries} left)`);
                setTimeout(() => getLalamoveQuote(retries - 1, delay * 2), delay); // exponential backoff
        } else {
                alert('Failed to get Lalamove quote. Please try again later.');
        }
    } 
    finally {
        loading.value = false
    }
}

</script>

<style>
</style>