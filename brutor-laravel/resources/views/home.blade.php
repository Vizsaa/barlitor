@extends('layouts.app')
@section('title', 'BruTor Shop - Home')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold">Welcome to <span class="text-success">BruTor Shop</span>!</h1>
            <p class="lead mt-3">
                Discover great deals and quality products — all in one place.
                Whether you're looking to shop or manage your store, you're in the right place.
            </p>

            @guest
                <a href="{{ route('login') }}" class="btn btn-success btn-lg mt-3">
                    <i class="fa-solid fa-right-to-bracket"></i> Get Started
                </a>
            @else
                <a href="{{ route('items.index') }}" class="btn btn-success btn-lg mt-3">
                    <i class="fa-solid fa-bag-shopping"></i> Start Shopping
                </a>
            @endguest
        </div>
        <div class="col-md-6 text-center">
            <img src="{{ asset('images/banner.png') }}" alt="Shop Banner" class="img-fluid rounded shadow-sm" style="max-height: 300px;" onerror="this.style.display='none'">
        </div>
    </div>

    <hr class="my-5">

    <div class="text-center mb-4">
        <h2 class="fw-bold">Featured Items</h2>
        <p class="text-muted">A quick look at some of our latest products</p>
    </div>

    <div class="row">
        @forelse($items as $item)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    @php
                        $image = $item->image_path ? asset($item->image_path) : asset('images/default.png');
                    @endphp
                    <img src="{{ $image }}" class="card-img-top" alt="Item Image" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $item->title ?: $item->description }}</h5>
                        <p class="card-text text-success fw-bold">₱{{ number_format($item->sell_price, 2) }}</p>
                        <a href="{{ route('items.show', $item->item_id) }}" class="btn btn-outline-success btn-sm">View More</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No featured items available right now.</p>
        @endforelse
    </div>
</div>
@endsection
