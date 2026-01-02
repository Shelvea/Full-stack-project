<x-app-layout>
    <x-slot name="title">Vegetables</x-slot>
    
    @if(request('search'))
    <p class="text-muted">
        Showing results for "<strong>{{ request('search') }}</strong>"
    </p>
    @endif

    <div class="container mt-5 pt-5">
        <h3 class="text-success">Vegetables</h3>

    @if($products->count() === 0)
    <div class="alert alert-warning">
        No products found.
    </div>
    @endif

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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

        <div class="row mt-4">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-60 shadow">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p>{{ $product->description }}</p>
                            <p>Stock: {{ $product->stock }}</p>
                            <strong>NT$ {{ number_format($product->price, 2) }}</strong>
                            
                            @if($product->stock > 0)
                                <!-- Quantity and Add to Cart -->
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2 text-center">
                                    @csrf
                                    <div class="input-group mb-2 d-inline-flex" style="width: 120px;">
                                        <button type="button" class="btn btn-success btn-minus">-</button>
                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control text-center quantity-input">
                                        <button type="button" class="btn btn-success btn-plus">+</button>
                                    </div>
                                    <div class="mt-2 w-100">
                                    <button type="submit" class="btn btn-success w-100">Add to Cart</button>
                                    </div>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100 mt-auto" disabled>Out of Stock</button>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

@push('scripts')
<!-- Plus/Minus Buttons Script -->
    <script>

    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.quantity-input').forEach(function (input) {
        const minusBtn = input.closest('div').querySelector('.btn-minus');
        const plusBtn = input.closest('div').querySelector('.btn-plus');

        // Handle + button
        plusBtn.addEventListener('click', function () {
            let value = parseInt(input.value);
            const max = parseInt(input.max);
            if (value < max) {
                input.value = value + 1;
            } else {
                input.value = max;
            }
        });

        // Handle - button
        minusBtn.addEventListener('click', function () {
            let value = parseInt(input.value);
            const min = parseInt(input.min);
            if (value > min) {
                input.value = value - 1;
            } else {
                input.value = min;
            }
        });

        // Prevent manual input beyond min/max
        input.addEventListener('input', function () {
            const value = parseInt(input.value);
            const min = parseInt(input.min);
            const max = parseInt(input.max);

            if (value > max) input.value = max;
            if (value < min || isNaN(value)) input.value = min;
        });
    });
});
    </script>

@endpush

</x-app-layout>