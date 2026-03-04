@extends('layouts.app')
@section('title', 'Add New Item - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-plus"></i> Add New Item</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Item Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter item title" required value="{{ old('title') }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a short item description" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="cost_price" class="form-label fw-semibold">Cost Price</label>
                            <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" placeholder="Enter item cost price" required value="{{ old('cost_price') }}">
                        </div>

                        <div class="mb-3">
                            <label for="sell_price" class="form-label fw-semibold">Sell Price</label>
                            <input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" placeholder="Enter selling price" required value="{{ old('sell_price') }}">
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select a category</option>
                                @foreach(['Engine', 'Electrical', 'Bodywork', 'Consumables', 'Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="product" {{ old('type') === 'product' ? 'selected' : '' }}>Product</option>
                                <option value="tool" {{ old('type') === 'tool' ? 'selected' : '' }}>Tool Rental</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label fw-semibold">Stock Quantity</label>
                            <input type="number" min="0" class="form-control" id="stock_quantity" name="stock_quantity" placeholder="Enter initial stock" required value="{{ old('stock_quantity') }}">
                        </div>

                        <div class="mb-3">
                            <label for="supplier_id" class="form-label fw-semibold">Supplier (optional)</label>
                            <select class="form-select" id="supplier_id" name="supplier_id">
                                <option value="">Select a supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="image_path" class="form-label fw-semibold">Upload Image</label>
                            <input class="form-control" type="file" id="image_path" name="image_path" accept="image/*">
                            <div class="form-text">Accepted formats: JPG, PNG (max 5MB)</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('items.index') }}" class="btn btn-secondary me-2">
                                <i class="fa-solid fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i> Save Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
