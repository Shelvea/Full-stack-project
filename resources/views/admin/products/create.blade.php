<x-app-layout>

<x-slot name="title">Add Product</x-slot>

<x-slot name="header">
    <h4 class="text-center text-success">Product Management</h4>
</x-slot>    

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-3">
            
            <h4 class="m-0">Add Product</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-warning mt-4 custom-hover">Back</a>

            <div class="card bg-success text-white mt-3">
                <div class="card-body border border-light rounded">
                    
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" accept="image/*" class="form-control bg-light @error('image') is-invalid @enderror" value="{{ old('image') }}">
                            @error('image') 
                                <div class="invalid-feedback custom-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name') 
                                <div class="invalid-feedback custom-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control bg-light @error('description') is-invalid @enderror" value="{{ old('description') }}">
                            @error('description') 
                                <div class="invalid-feedback custom-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" class="form-control bg-light @error('price') is-invalid @enderror" value="{{ old('price') }}">
                            @error('price') 
                                <div class="invalid-feedback custom-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control bg-light @error('stock') is-invalid @enderror" value="{{ old('stock') }}">
                            @error('stock') 
                                <div class="invalid-feedback custom-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback custom-invalid">{{ $message }}</div>
                        @enderror
                    </div>
                        <button type="submit" class="btn btn-light custom-save">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>