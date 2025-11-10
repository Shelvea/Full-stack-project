<x-app-layout>

<x-slot name="title">Order List</x-slot>

<x-slot name="header">
    <h4 class="text-center text-success">Order Management</h4>
</x-slot>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <table class="table table-hover">
        <thead class="table-light text-center">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Order Items</th>
                    <th>Payment</th>
                    <th>Delivery</th>
                    <th>Recipient</th>
                    <th>Actions</th>
                </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <ul class="mb-0">
                            @foreach($order->orderItems as $item)
                                <li class="text-nowrap">{{ $item->product_name }} ({{ $item->quantity }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-nowrap">{{ $order->payment_method }} ({{ $order->payment_status }})</li>  
                            <li class="list-group-item text-nowrap">subtotal : {{ $order->subtotal }}</li>
                            <li class="list-group-item text-nowrap">total : {{ $order->total }}</li>
                        </ul>
                    </td>
                    <td>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-nowrap">fee : {{ $order->delivery_fee }}</li>
                            <li class="list-group-item text-nowrap">status ({{ $order->delivery_status }})</li>
                        </ul>
                    </td>
                    <td>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ $order->recipient_name }}</li>
                            <li class="list-group-item">{{ $order->recipient_email }}</li>
                            <li class="list-group-item">{{ $order->recipient_phone }}</li>
                            <li class="list-group-item">{{ $order->recipient_address }}</li>
                        </ul>
                    </td>
                    <td>
                        <div class="text-nowrap">
                        <button type="button" onclick="window.location.href='{{ route('admin.orders.delivery', $order->id) }}'" class="btn btn-warning btn-sm" style="font-weight: bold;">Delivery</button>
                        <button type="button" data-route={{ route('admin.orders.destroy', $order->id) }} class="btn btn-danger delete-btn index-btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteOrderModal">Delete</button>                        
                        
                        <div class="dropdown mt-2">
                        
                        @php
                            $statusColor = match($order->status) {
                                'Completed' => 'btn-success',
                                'Cancelled', 'Returned' => 'btn-danger',
                                'Processing', 'Ready for Delivery' => 'btn-warning',
                                default => 'btn-primary',
                            };
                        @endphp
                        
                            <button 
                            id="statusBtn-{{ $order->id }}" 
                            type="button" 
                            class="btn {{ $statusColor }} dropdown-toggle" 
                            data-bs-toggle="dropdown">
                            {{ $order->status }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'pending payment')">Pending Payment</a></li> 
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'Confirmed')">Confirmed</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'Processing')">Processing</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'Ready for Delivery')">Ready for Delivery</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'On the Way')">On the Way</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'Completed')">Completed</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'Cancelled')">Cancelled</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $order->id }}, 'Returned')">Returned</a></li>
                        </ul>
                        </div>                        
                    </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- pagination --}}
    <div class="d-flex justify-content-end mt-4">
    {{ $orders->links('vendor.pagination.bootstrap-5-light') }}
    </div>
</div>

{{-- Delete modal --}}
<div class="modal fade" id="deleteOrderModal" tabindex="-1">
    <div class="modal-dialog">
    <div class="modal-content bg-success text-white">

      <!-- Modal Header -->
      <div class="modal-header border-0">
        <h5 class="modal-title">Delete Order?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p>You are about to delete this order.</p>
        <p>This action cannot be reversed.</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Order</button>
        </form>
      </div>
    </div>
    </div>
</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded',function(){
        const deleteButtons = document.querySelectorAll('.delete-btn');//select all elements with the class delete-btn
        const deleteForm = document.getElementById('deleteForm');
        
        deleteButtons.forEach(button =>{
            button.addEventListener('click', function(){
                const deleteUrl = this.dataset.route;
                deleteForm.action = deleteUrl;
            });
        });
    });

    function updateStatus(orderId, newStatus) {
    // Update the button text right away for instant feedback
    const button = document.getElementById(`statusBtn-${orderId}`);
    button.textContent = newStatus;

    // Send AJAX request to backend to save status
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel protection
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.remove('btn-primary', 'btn-warning', 'btn-success', 'btn-danger');
            if (newStatus === 'Completed') {
                button.classList.add('btn-success');
            } else if (newStatus === 'Cancelled' || newStatus === 'Returned') {
                button.classList.add('btn-danger');
            } else if (newStatus === 'Processing' || newStatus === 'Ready for Delivery') {
                button.classList.add('btn-warning');
            } else {
                button.classList.add('btn-primary');
            }
        } else {
            alert('Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}

</script>

@endpush

</x-app-layout>