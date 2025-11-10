<x-app-layout>

<x-slot name="title">Delivery</x-slot>

<x-slot name="header">
    <h4 class="text-center text-success">Delivery Details</h4>
</x-slot>

<div class="container mt-3">

        <p>
            @if($order->share_link)
                <p><a href="{{ $order->share_link }}" class="btn btn-outline-primary rounded-pill" target="_blank">
                    <i class="bi bi-truck me-2"></i>Track Delivery</a>
                </p>
            @else
                <p>No share link available</p>
            @endif
        </p>
    
</div>
    
    <div class="d-flex justify-content mt-4">        
    <a href="{{ url()->previous() }}" class="btn btn-success" style="font-weight: bold;">
        Back
    </a>
</div>

</x-app-layout>