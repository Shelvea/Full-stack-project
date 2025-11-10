<x-app-layout>
    
    <x-slot name="title">Product Details</x-slot>

    <x-slot name="header">
        <h4 class="text-center text-success">Product Management</h4>
    </x-slot>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-3">
            <h4>Product Details</h4>
            <div class="card bg-success text-white mt-4">
                <div class="card-body border border-success rounded">
                <!-- Show current image -->
                @if ($product->image)
                <div class="mt-2">
                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image" width="120" class="rounded">
                </div>
                @endif
                <h5 class="card-title"><strong>Name:</strong> {{ $product->name }} </h5>
                <p class="card-text"><strong>Description:</strong> {{ $product->description }} </p>
                <p class="card-text"><strong>Price:</strong> {{ $product->price }} </p>
                <p class="card-text"><strong>Stock:</strong> {{ $product->stock }} </p>
                <p class="card-text"><strong>Category:</strong> {{ $product->category->name }} </p>
                <a href="{{ route('admin.products.index', ['page' => $page]) }}" class="btn btn-warning custom-hover">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>