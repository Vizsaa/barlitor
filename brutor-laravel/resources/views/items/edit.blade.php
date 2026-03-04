@extends('layouts.app')
@section('title', 'Edit Item - BruTor Shop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Edit Item</h2>

    <form method="POST" action="{{ route('admin.items.update', $item->item_id) }}" enctype="multipart/form-data" class="p-4 border rounded bg-white shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $item->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Description</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $item->description) }}" required>
        </div>

        <div class="mb-3">
            <label for="cost_price" class="form-label fw-semibold">Cost Price</label>
            <input type="number" name="cost_price" id="cost_price" class="form-control" value="{{ old('cost_price', $item->cost_price) }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="sell_price" class="form-label fw-semibold">Sell Price</label>
            <input type="number" name="sell_price" id="sell_price" class="form-control" value="{{ old('sell_price', $item->sell_price) }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label fw-semibold">Category</label>
            <select name="category" id="category" class="form-select" required>
                <option value="">Select a category</option>
                @foreach(['Engine', 'Electrical', 'Bodywork', 'Consumables', 'Other'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $item->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label fw-semibold">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="product" {{ old('type', $item->type) === 'product' ? 'selected' : '' }}>Product</option>
                <option value="tool" {{ old('type', $item->type) === 'tool' ? 'selected' : '' }}>Tool Rental</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="stock_quantity" class="form-label fw-semibold">Stock Quantity</label>
            <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', $item->stock_quantity) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="supplier_id" class="form-label fw-semibold">Supplier (optional)</label>
            <select name="supplier_id" id="supplier_id" class="form-select">
                <option value="">-- Select a Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id', $item->supplier_id) == $supplier->supplier_id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Current Image</label><br>
            @php
                $img = $item->image_path ? asset($item->image_path) : asset('images/default.png');
            @endphp
            <img src="{{ $img }}" alt="Item Image" width="120" class="rounded border">
        </div>

        <div class="mb-4">
            <label for="image_path" class="form-label fw-semibold">Upload New Image (optional)</label>
            <input type="file" name="image_path" id="image_path" class="form-control" accept="image/*">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('items.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
