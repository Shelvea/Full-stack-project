<x-app-layout>

<x-slot name="title">Cart</x-slot>

<div class="container pt-5 mt-5">
{{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
        @php $subtotal = 0; @endphp

        @forelse($cart?->cartItems ?? [] as $item)
        @php
            $itemTotal = $item->product->price * $item->quantity;
            $subtotal += $itemTotal;
        @endphp
        <tr data-item-id="{{ $item->id }}">
            <td>{{ $item->product->name }}</td>
            <td>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-success btn-minus">-</button>
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control text-center quantity-input" style="width:60px;">
                    <button type="button" class="btn btn-success btn-plus">+</button>
                </div>                    
            </td>
            <td>NT$ {{ number_format($item->product->price, 2) }}</td>
            <td class="item-total">NT$ {{ number_format($itemTotal, 2) }}</td>
            <td>
                <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Your cart is empty.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- ✅ Proceed to checkout button --}}
@if(!empty($cart?->cartItems) && $cart->cartItems->count() > 0)
<div class="d-flex justify-content-between mt-4">
    <h5 class="mb-0" >Subtotal: <strong id="subtotal">NT$ {{ number_format($subtotal, 2) }}</strong></h5>
    <a href="{{ route('checkout.index') }}" class="btn btn-success">
        Proceed to Checkout
    </a>
</div>
@endif

<div id="alert-card" class="alert alert-warning fade show d-none" role="alert" 
        style="position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px 40px;
        min-width: 300px; font-weight: bold;">
    
    <span id="alert-message"></span>
</div>

</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    const baseUrl = "{{ url('user/cart/update') }}";

    function showAlert(message) {
        const alertCard = document.getElementById('alert-card');
        const alertMessage = document.getElementById('alert-message');
        alertMessage.textContent = message;
        alertCard.classList.remove('d-none');

        // Auto-hide after 3 seconds
        setTimeout(() => {
            alertCard.classList.add('d-none');
        }, 3000);
    }

    // Handle + / - buttons
    document.querySelectorAll('.btn-plus, .btn-minus').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const itemId = row.dataset.itemId;
            const quantityInput = row.querySelector('.quantity-input');
            const maxStock = parseInt(quantityInput.getAttribute('max'));
            let quantity = parseInt(quantityInput.value);

            if (this.classList.contains('btn-plus')) {
                if (quantity < maxStock) {
                    quantity++;
                } else {
                    showAlert(`Only ${maxStock} in stock!`);
                    return;
                }
            } else if (this.classList.contains('btn-minus') && quantity > 1) {
                quantity--;
            }

            quantityInput.value = quantity;
            updateCart(itemId, quantity, row);
        });
    });

    // 👇 Handle manual typing in quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function () {
            const row = this.closest('tr');
            const itemId = row.dataset.itemId;
            const maxStock = parseInt(this.getAttribute('max'));
            let quantity = parseInt(this.value);

            // If not a number, reset to 1
            if (isNaN(quantity) || quantity < 1) quantity = 1;

            // If over max, clamp to max
            if (quantity > maxStock) {
                alert(`Only ${maxStock} in stock!`);
                quantity = maxStock;
            }

            this.value = quantity;

            // Auto update subtotal & total
            updateCart(itemId, quantity, row);
        });
    });

    // using Fetch API Reusable function to send AJAX update
    function updateCart(itemId, quantity, row) {
        fetch(`${baseUrl}/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                row.querySelector('.item-total').textContent = `NT$ ${data.item_total.toFixed(2)}`;
                document.getElementById('subtotal').textContent = `NT$ ${data.subtotal.toFixed(2)}`;
            }
        })
        .catch(err => console.error(err));
    }
});

</script>

@endpush

</x-app-layout>