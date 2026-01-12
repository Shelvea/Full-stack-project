<x-app-layout>
    <x-slot name="title">Returned Goods or Refunds</x-slot>

    @include('partials.navbar')

    <div class="container page-content">

               @forelse($orders as $order)
            <div class="card mb-4 shadow-sm">
                <div class="card-header"> 
                    <span class="text-muted ms-2">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($order->orderItems as $item)
                            <div class="d-flex align-items-start mb-3 border-bottom pb-2">
                                {{-- Product Image --}}
                                <img 
                                    src="{{ asset('storage/' . $item->product->image) }}" 
                                    alt="{{ $item->product->name }}" 
                                    class="rounded shadow-sm me-3 mb-4" 
                                    style="max-height: 120px; object-fit: cover;"
                                >
                                <div class="d-flex flex-column w-100">
                                    {{-- Product Name & Quantity --}}
                                    <p class="mb-1 fw-bold">{{ $item->product->name }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="text-muted small mb-0">{{ $item->product->description }}</p>
                                        <p class="text-muted align-self-end mb-0">x{{ $item->quantity }}</p>
                                    </div>
                                </div>

                                <p class="mt-auto align-self-end mb-0">${{ $item->product->price }}</p>
                        
                            </div>

                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end">
                    <p class="mb-0"><strong>Total : </strong> ${{ $order->subtotal }}</p>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='#'" disabled><i class="bi bi-patch-check me-1"></i>Order Completed</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location='#'"><i class="fas fa-truck me-1"></i>Track Order</button>
                    </div>

                </div>
            </div>
        @empty
            <p class="text-center text-muted mt-4">You have no orders yet.</p>
        @endforelse
    </div>

    {{-- pagination --}}
    <div class="d-flex justify-content-end mt-4">
    {{ $orders->links('vendor.pagination.bootstrap-5-light') }}
    </div>
    
    </div>

</x-app-layout>