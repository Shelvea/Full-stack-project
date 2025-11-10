<x-app-layout>

<x-slot name="title">Product List</x-slot>

<x-slot name="header">
    <h4 class="text-center text-success">Product Management</h4>
</x-slot>

<div class="container mt-4">
    <h4 class="mb-4 text-dark">Products List</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-success mb-3">Add Product</a>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <table class="table table-bordered table-success table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($products as $product)
                
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td> 
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" width="50">
                    @endif
                </td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.products.show', [$product->id, 'page' => request()->get('page', 1)]) }}" class="btn btn-warning index-btn">View</a>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info index-btn">Edit</a>
                    

                    <button type="button" class="btn btn-danger delete-btn index-btn" data-bs-toggle="modal" 
                    data-bs-target="#deleteProductModal" data-route={{ route('admin.products.destroy', $product->id) }}>
                        Delete
                    </button> 
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No product found!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- pagination --}}
    <div class="d-flex justify-content-end mt-4">
    {{ $products->links('vendor.pagination.bootstrap-5-light') }}
    </div>
</div>

{{-- Delete modal --}}
<div class="modal fade" id="deleteProductModal" tabindex="-1">
    <div class="modal-dialog">
    <div class="modal-content bg-success text-white">

      <!-- Modal Header -->
      <div class="modal-header border-0">
        <h5 class="modal-title">Delete Product?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <p>You are about to delete this product.</p>
        <p>This action cannot be reversed.</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Product</button>
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
</script>

@endpush

</x-app-layout>