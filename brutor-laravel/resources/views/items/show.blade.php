@extends('layouts.app')
@section('title', $item->title . ' - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            @php
                $img = $item->image_path ? asset($item->image_path) : asset('images/default.png');
            @endphp
            <img src="{{ $img }}" class="img-fluid border rounded" alt="{{ $item->title }}">
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold">{{ $item->title }}</h2>
            <p class="text-muted">{{ $item->description }}</p>
            <p>Category: <strong>{{ $item->category ?? 'N/A' }}</strong></p>
            <p>Supplier:
                @if($item->supplier)
                    @if($item->supplier->website)
                        <a href="{{ $item->supplier->website }}" target="_blank" class="text-decoration-none">{{ $item->supplier->name }}</a>
                    @else
                        {{ $item->supplier->name }}
                    @endif
                @else
                    N/A
                @endif
            </p>
            <p class="{{ $item->stock_quantity <= 0 ? 'text-danger' : 'text-success' }}">
                Stock: {{ (int) $item->stock_quantity }}
            </p>
            <p class="fw-semibold text-success fs-4">₱{{ number_format($item->sell_price, 2) }}</p>

            @auth
                @if($item->stock_quantity > 0)
                    @if($item->type === 'tool')
                        <form method="POST" action="{{ route('cart.add') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->item_id }}">
                            <input type="hidden" name="type" value="tool">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $item->stock_quantity }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-cart-plus"></i> Rent Tool
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('cart.add') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->item_id }}">
                            <input type="hidden" name="type" value="product">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $item->stock_quantity }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-danger mt-3">Out of stock</div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary mt-3">Login to purchase</a>
            @endauth

            <div class="mt-4 p-3 border rounded bg-white">
                <h5>Product Notes</h5>
                <p>{{ $item->description }}</p>
            </div>

            <div class="mt-4">
                <h4>Reviews</h4>

                @if($canReview)
                    <form method="POST" action="{{ route('reviews.store') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                        <div class="mb-2">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-select" required>
                                <option value="">Select rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                @elseif(auth()->check() && auth()->user()->role === 'customer')
                    <div class="alert alert-warning">You can only leave a review if you purchased this item.</div>
                @else
                    @guest
                        <div class="alert alert-warning">Please log in as a verified customer to leave a review.</div>
                    @endguest
                @endif

                @if($reviews->count() > 0)
                    @foreach($reviews as $rev)
                        <div class="border p-3 rounded mb-2 bg-white d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $rev->user->name ?? 'Unknown' }}</strong>
                                <span class="text-warning">{!! str_repeat('&#9733;', $rev->rating) . str_repeat('&#9734;', 5 - $rev->rating) !!}</span>
                                <p class="mb-0">{{ $rev->comment }}</p>
                                <small class="text-muted">{{ $rev->created_at }}</small>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                @auth
                                    @if(auth()->id() == $rev->user_id)
                                        <a href="{{ route('reviews.edit', $rev->review_id) }}" class="btn btn-sm btn-outline-primary mb-1">Edit</a>
                                    @endif
                                    @if(auth()->id() == $rev->user_id || auth()->user()->isAdmin())
                                        <form action="{{ route('reviews.destroy', $rev->review_id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No reviews yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
