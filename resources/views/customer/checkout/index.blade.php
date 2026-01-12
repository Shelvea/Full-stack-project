<x-app-layout>

<x-slot name="title">Checkout</x-slot>

<div class="container">
        <h3 class="text-success">Checkout</h3>

        {{-- Cart Items --}}
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
            @foreach($cart?->cartItems ?? [] as $item)
            @php
                $itemTotal = $item->product->price * $item->quantity;
            @endphp
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>NT$ {{ number_format($item->product->price, 2) }}</td>
                    <td>NT$ {{ number_format($itemTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">        
    <a href="{{ url()->previous() }}" class="btn btn-success" style="font-weight: bold;">
        Back
    </a>    
    <h5 class="mb-0" >Subtotal: <strong id="subtotal">NT$ {{ number_format($subtotal, 2) }}</strong></h5>
    </div>

    <form action="{{ route('checkout.placeOrder') }}" method="POST" class="mt-5 mx-auto" style="max-width: 600px; width: 100%;">
        @csrf
        <div class="card p-3">
            
            <h5 class="text-success text-center mb-3">Delivery Information</h5>
            
            {{-- User Name & Email --}}
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="recipient_name" value="{{ auth()->user()->name }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" class="form-control" name="recipient_email" value="{{ auth()->user()->email }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="tel" class="form-control" name="recipient_phone" pattern="^\+8869\d{8}$" required>
            <div class="form-text text-success">Format: +8869******** (E.164 format)</div>
        </div>

        {{-- default Delivery method using lalamove --}}
        
            {{-- Delivery Address --}}
            <div class="mb-3 position-relative">
                <label for="address" class="form-label">Delivery Address</label>
                <input type="text" name="recipient_address" id="address" class="form-control" placeholder="Enter address" required>
            {{-- Add suggestions list --}}
                <ul id="address-suggestions" class="list-group position-absolute w-100" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></ul>
            </div>
        
            {{-- Lalamove info --}}
            <div class="mb-3 mt-3" id="lalamove-info">
                <p>Estimated Delivery Fee: <strong id="delivery-fee">NT$ 0</strong></p>
                <p>Estimated Delivery Distance: <strong id="delivery-distance">--</strong></p>
                <p>Total Fee: <strong id="total-amount">--</strong></p>
            </div>

            <!-- Hidden fields for form submission -->
            <input type="hidden" name="delivery_fee" id="delivery_fee" value="0">
            <input type="hidden" name="delivery_distance" id="delivery_distance" value="">
            <input type="hidden" name="subtotal" id="subtotal_input" value="{{ $subtotal }}">
            <input type="hidden" name="total" id="total" value="0">

            <input type="hidden" id="quotation_id" name="quotation_id">
            <input type="hidden" id="sender_stop_id" name="sender_stop_id">
            <input type="hidden" id="recipients_stop_id" name="recipients_stop_id">


            {{-- Payment Method --}}
        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="cod">Cash on Delivery</option>
                <option value="transfer">Bank Transfer</option>
            </select>
        </div>

        <div class="text-center mt-3">
        <button type="submit" class="btn btn-success" style="width: 150px; font-weight: bold;">Place Order</button>
        </div>

        </div>
    </form>

</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addressInput = document.getElementById('address');
    const suggestionsList = document.getElementById('address-suggestions');
    const apiKey = "9ef97a98cb2a40848d555df5e8631a38";
    const vendorAddress = "National University Of Kaohsiung, 700 Kaohsiung University Road, Nanzi District, Kaohsiung 81148, Taiwan";
    const lalamoveEndpoint = "/api/lalamove/estimate";

    let debounceTimer;

    // --- Debounced Address Autocomplete ---
    addressInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = addressInput.value.trim();

        if (query.length < 3) {
            suggestionsList.innerHTML = '';
            suggestionsList.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(query)}&apiKey=${apiKey}`, { signal: AbortSignal.timeout(5000) });
                const data = await res.json();
                suggestionsList.innerHTML = '';

                if (!data.features.length) {
                    suggestionsList.style.display = 'none';
                    return;
                }

                data.features.forEach(feature => {
                    const item = document.createElement('li');
                    item.classList.add('list-group-item');
                    item.textContent = feature.properties.formatted;
                    item.style.cursor = 'pointer';
                    item.onclick = () => {
                        addressInput.value = feature.properties.formatted;
                        suggestionsList.innerHTML = '';
                        suggestionsList.style.display = 'none';
                        getLalamoveQuote(); // fetch quote immediately
                    };
                    suggestionsList.appendChild(item);
                });

                suggestionsList.style.display = 'block';
            } catch (err) {
                console.error('Geoapify API error:', err);
            }
        }, 300); // debounce 300ms
    });

    document.addEventListener('click', (e) => {
        if (!addressInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.style.display = 'none';
        }
    });

    // --- Function to fetch Lalamove quote with retries ---
    async function getLalamoveQuote(retries = 3, delay = 1000) {
        const address = addressInput.value.trim();
        if (!address) return;

        try {
            // Geocode destination
            const geoRes = await fetch(`https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(address)}&apiKey=${apiKey}`, { signal: AbortSignal.timeout(5000) });
            const geoData = await geoRes.json();
            if (!geoData.features.length) throw new Error('Invalid destination address');

            // Geocode pickup
            const geoRes1 = await fetch(`https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(vendorAddress)}&apiKey=${apiKey}`, { signal: AbortSignal.timeout(5000) });
            const geoData1 = await geoRes1.json();
            if (!geoData1.features.length) throw new Error('Invalid pickup address');

            const dest = geoData.features[0].geometry.coordinates;
            const pickup = geoData1.features[0].geometry.coordinates;

            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 5000); // 5s timeout
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            const res = await fetch(lalamoveEndpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ destination: dest, pickup: pickup, pickupAddress: vendorAddress, destAddress: address }),
                signal: controller.signal
            });

            clearTimeout(timeout);

            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

            const data = await res.json();

            document.getElementById('delivery-fee').textContent = data.fee ? `NT$ ${data.fee}` : 'NT$ 0';
            document.getElementById('delivery-distance').textContent = data.distance_m ? `${(data.distance_m / 1000).toFixed(1)} km` : '--';
            document.getElementById('delivery_fee').value = data.fee || 0;
            document.getElementById('delivery_distance').value = data.distance_m ? Number((data.distance_m / 1000).toFixed(1)) : 0;
            document.getElementById('quotation_id').value = data.quotation_id || '--';
            document.getElementById('sender_stop_id').value = data.sender_stop_id || '--';
            document.getElementById('recipients_stop_id').value = data.recipients_stop_id || '--';

            // --- Add delivery fee and subtotal ---
            const subtotal = parseFloat(document.getElementById('subtotal_input').value) || 0;
            const deliveryFeeValue = parseFloat(data.fee) || 0;
            const totalFee = subtotal + deliveryFeeValue;

            // Display total in NT$
            document.getElementById('total-amount').textContent = `NT$ ${totalFee.toFixed(2)}`;

            // Update hidden input for form submission
            document.getElementById('total').value = totalFee.toFixed(2);

        } catch (err) {
            console.error('Lalamove API error:', err);
            if (retries > 0) {
                console.log(`Retrying in ${delay}ms... (${retries} left)`);
                setTimeout(() => getLalamoveQuote(retries - 1, delay * 2), delay); // exponential backoff
            } else {
                alert('Failed to get Lalamove quote. Please try again later.');
            }
        }
    }

    // --- Trigger quote on blur ---
    addressInput.addEventListener('blur', () => getLalamoveQuote());
});

</script>

@endpush

</x-app-layout>