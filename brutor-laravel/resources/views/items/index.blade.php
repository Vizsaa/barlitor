@extends('layouts.app')
@section('title', 'Items - BruTor Shop')

@section('content')
<div class="container py-5">

    <div class="alert alert-info text-center fw-semibold shadow-sm mb-4">
        @guest
            You are browsing as a <strong>guest</strong>.
        @else
            @if(auth()->user()->isAdmin())
                You are logged in as an <strong>admin</strong>.
            @else
                You are logged in as a <strong>customer</strong>.
            @endif
        @endguest
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Items ({{ $items->count() }})</h2>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fa-solid fa-plus"></i> Add Item
                </a>
            @endif
        @endauth
    </div>

    <form method="GET" action="{{ route('items.index') }}" class="row g-3 align-items-center mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ $keyword }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="all">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $cat === $category ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>Sort by: Default (A–Z)</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="type" {{ $sort === 'type' ? 'selected' : '' }}>Sort by: Type</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="fa-solid fa-filter"></i> Apply
            </button>
        </div>
    </form>

    @if($items->count() > 0)
        <div class="row g-4">
            @foreach($items as $item)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0">
                        <a href="{{ route('items.show', $item->item_id) }}">
                            @php
                                $img = $item->image_path ? asset($item->image_path) : asset('images/default.png');
                            @endphp
                            <img src="{{ $img }}" class="card-img-top img-fluid" alt="{{ $item->title }}"
                                 style="height: 200px; object-fit: cover; border-bottom: 1px solid #eee;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title mb-2">
                                <a href="{{ route('items.show', $item->item_id) }}" class="text-decoration-none text-dark">
                                    {{ $item->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">{{ $item->description }}</p>
                            <p class="card-text small text-muted mb-1">
                                Category: <strong>{{ $item->category ?? 'N/A' }}</strong>
                            </p>
                            <p class="card-text small text-muted mb-1">
                                Type: <strong>{{ ucfirst($item->type ?? 'N/A') }}</strong>
                            </p>
                            <p class="card-text small {{ $item->stock_quantity <= 0 ? 'text-danger' : 'text-success' }}">
                                Stock: {{ (int) $item->stock_quantity }}
                            </p>
                            <p class="card-text fw-semibold text-success mb-0">
                                ₱{{ number_format($item->sell_price, 2) }}
                            </p>
                        </div>

                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="card-footer bg-transparent border-0 text-center pb-3">
                                    <a href="{{ route('admin.items.edit', $item->item_id) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item->item_id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning text-center mt-4">No items found.</div>
    @endif
</div>
@endsection
