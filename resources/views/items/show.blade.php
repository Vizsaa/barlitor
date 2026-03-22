@extends('layouts.app')
@section('title', $item->title . ' - BarliTor Shop')

@section('content')
<!-- Breadcrumbs / Back Navigation -->
<div class="bg-[#1a1a1a] border-b border-gray-800 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between text-sm">
        <div class="flex items-center text-gray-400 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('items.index') }}" class="hover:text-white transition-colors">Items</a>
            <span>/</span>
            <span class="text-white font-medium truncate max-w-[200px] sm:max-w-xs">{{ $item->title }}</span>
        </div>
        <a href="{{ route('items.index') }}" class="text-gray-400 hover:text-white transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Catalog
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
        
        <!-- Image Section -->
        <div>
            @php
                $images = $item->images ?? collect();
                // Include legacy image_path in gallery if it's not already there
                if ($item->image_path && $images->isEmpty()) {
                    $images = collect([
                        (object) ['image_path' => $item->image_path],
                    ]);
                }
                $mainSrc = $item->thumbnail;
            @endphp
            <style>
                /* Hide scrollbar for Chrome, Safari and Opera */
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                /* Hide scrollbar for IE, Edge and Firefox */
                .no-scrollbar {
                    -ms-overflow-style: none;  /* IE and Edge */
                    scrollbar-width: none;  /* Firefox */
                }
            </style>
            <div class="bg-[#111111] rounded-xl border border-gray-800 overflow-hidden shadow-2xl relative">
                <div class="flex flex-col-reverse md:flex-row">
                    {{-- Thumbnails Strip (left on desktop, bottom on mobile) --}}
                    @if($images->count() > 1)
                        <div class="md:w-20 flex md:flex-col gap-2 p-2 overflow-x-auto md:overflow-y-auto md:max-h-[400px] border-t md:border-t-0 md:border-r border-gray-800 bg-[#151515] no-scrollbar">
                            @foreach($images as $img)
                                <button type="button"
                                        class="thumb-btn w-16 h-16 rounded-md overflow-hidden border-2 border-gray-700 hover:border-orange-500 flex-shrink-0 focus:outline-none transition-all"
                                        data-src="{{ asset($img->image_path) }}">
                                    <img src="{{ asset($img->image_path) }}" alt="Thumb" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    {{-- Main Image --}}
                    <div class="flex-1 flex items-center justify-center h-[350px] md:h-[400px]">
                        <img id="mainPhoto" src="{{ $mainSrc }}" alt="{{ $item->title }}" class="w-full h-full object-contain p-4 mix-blend-lighten">
                    </div>
                </div>

                @if($item->type === 'tool')
                    <div class="absolute top-4 left-4 bg-orange-600 text-white text-xs font-bold px-3 py-1.5 rounded uppercase tracking-wider shadow-lg z-10">
                        <i class="fa-solid fa-wrench mr-1"></i> Tool / Rental
                    </div>
                @endif
            </div>
            
            <!-- Details / Specs Card -->
            <div class="mt-6 bg-[#1a1a1a] rounded-xl border border-gray-800 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2 uppercase tracking-wide">Specifications</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500 font-medium">Category</dt>
                        <dd class="text-white mt-1">{{ $item->category ?? 'Categorized' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">Supplier</dt>
                        <dd class="text-white mt-1">
                            @if($item->supplier)
                                @if($item->supplier->website)
                                    <a href="{{ $item->supplier->website }}" target="_blank" class="text-orange-500 hover:underline">
                                        {{ $item->supplier->name }} <i class="fa-solid fa-external-link-alt text-[10px] ml-1"></i>
                                    </a>
                                @else
                                    {{ $item->supplier->name }}
                                @endif
                            @else
                                <span class="text-gray-400 italic">Not Specified</span>
                            @endif
                        </dd>
                    </div>
                    <!-- Add more custom specs here if the DB has them -->
                </dl>
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="flex flex-col">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2 leading-tight">{{ $item->title }}</h1>
            
            <div class="flex items-center gap-4 mb-6">
                @if($item->stock_quantity > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/40 text-green-400 border border-green-800">
                        <i class="fa-solid fa-check-circle mr-1.5"></i> In Stock ({{ (int) $item->stock_quantity }})
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/40 text-red-400 border border-red-800">
                        <i class="fa-solid fa-circle-xmark mr-1.5"></i> Out of Stock
                    </span>
                @endif
                
                <!-- Average Rating snippet (if you calculate it in the controller) -->
                @if($reviews->count() > 0)
                    @php $avgRating = $reviews->avg('rating'); @endphp
                    <div class="flex items-center">
                        <div class="flex text-yellow-500 text-sm">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-400">({{ $reviews->count() }} reviews)</span>
                    </div>
                @endif
            </div>

            <div class="text-4xl font-black text-orange-500 mb-8 border-b border-gray-800 pb-8">
                ₱{{ number_format($item->sell_price, 2) }}
            </div>

            <div class="prose prose-invert prose-p:text-gray-300 max-w-none mb-10">
                <p class="text-base leading-relaxed">{{ $item->description }}</p>
            </div>

            <!-- Action Area / Checkout Form -->
            <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 p-6 shadow-md mt-auto">
                @auth
                    @if($item->stock_quantity > 0)
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->item_id }}">
                            <input type="hidden" name="type" value="{{ $item->type === 'tool' ? 'tool' : 'product' }}">
                            
                            <div class="flex flex-col sm:flex-row gap-4 items-end">
                                <div class="w-full sm:w-32">
                                    <label for="quantity" class="block text-sm font-medium text-gray-400 mb-2 uppercase tracking-wide">Qty</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $item->stock_quantity }}" required
                                        class="bg-[#111111] border border-gray-700 text-white text-lg rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2.5 text-center">
                                </div>

                                @if($item->type === 'tool')
                                    <div class="w-full">
                                        <label for="start_date" class="block text-sm font-medium text-gray-400 mb-2 uppercase tracking-wide">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" required
                                            class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-3">
                                    </div>
                                    <div class="w-full">
                                        <label for="due_date" class="block text-sm font-medium text-gray-400 mb-2 uppercase tracking-wide">Return Date</label>
                                        <input type="date" name="due_date" id="due_date" min="{{ date('Y-m-d') }}" required
                                            class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-3">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="w-full mt-6 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 px-4 rounded-md shadow-[0_0_15px_rgba(249,115,22,0.3)] transition-all transform hover:-translate-y-0.5 flex items-center justify-center text-lg uppercase tracking-wide">
                                <i class="fa-solid fa-cart-plus mr-3"></i> 
                                {{ $item->type === 'tool' ? 'Rent Tool Now' : 'Add to Cart' }}
                            </button>
                        </form>
                    @else
                        <div class="bg-red-900/20 border border-red-800 rounded-md p-4 text-center">
                            <i class="fa-solid fa-box-open text-red-500 text-3xl mb-2"></i>
                            <h4 class="text-red-400 font-bold mb-1">Currently Unavailable</h4>
                            <p class="text-red-300/70 text-sm">We're working on restocking this item. Please check back later.</p>
                        </div>
                    @endif
                @else
                    <div class="text-center p-4">
                        <i class="fa-solid fa-lock text-gray-600 text-3xl mb-3"></i>
                        <h4 class="text-white font-bold mb-2">Authentication Required</h4>
                        <p class="text-gray-400 text-sm mb-4">Please log in to purchase or rent items from our store.</p>
                        <a href="{{ route('login') }}" class="inline-flex w-full justify-center bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white font-bold py-3 px-4 rounded-md transition-colors">
                            <i class="fa-solid fa-right-to-bracket mt-1 mr-2"></i> Log In
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div id="reviews" class="mt-20 border-t border-gray-800 pt-12">
        <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
            <i class="fa-regular fa-comments text-orange-500 mr-3"></i> Customer Reviews
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Review Form Column -->
            <div class="lg:col-span-1">
                @if($canReview)
                    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 p-6 sticky top-24">
                        <h4 class="text-lg font-bold text-white mb-4">Write a Review</h4>
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                            
                            <div class="mb-4">
                                <label for="rating" class="block text-sm font-medium text-gray-400 mb-2">Rating</label>
                                <select name="rating" id="rating" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>Select star rating</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} Star{{ $i !== 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-400 mb-2">Your Review</label>
                                <textarea name="comment" id="comment" rows="4" placeholder="Share your experience..."
                                    class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-3 resize-none" required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-4 rounded-md transition-colors">
                                Submit Review
                            </button>
                        </form>
                    </div>
                @elseif(auth()->check() && auth()->user()->role === 'customer')
                    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 p-6 text-center">
                        <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-receipt text-gray-400"></i>
                        </div>
                        <h4 class="text-white font-bold mb-2">Verified Purchase Required</h4>
                        <p class="text-gray-400 text-sm">You can only leave a review if you have purchased this item.</p>
                    </div>
                @else
                    @guest
                        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 p-6 text-center">
                            <h4 class="text-white font-bold mb-2">Have thoughts?</h4>
                            <p class="text-gray-400 text-sm mb-4">Log in as a customer to leave a review.</p>
                            <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-400 font-medium text-sm border border-orange-500/30 px-4 py-2 rounded transition-colors inline-block">Log In</a>
                        </div>
                    @endguest
                @endif
            </div>

            <!-- Reviews List Column -->
            <div class="lg:col-span-2 space-y-4">
                @if($reviews->count() > 0)
                    @foreach($reviews as $rev)
                        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 p-6 transition-colors hover:border-gray-700">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h5 class="text-white font-bold">{{ $rev->user->name ?? 'Unknown Customer' }}</h5>
                                        <span class="text-gray-600 text-xs text-xs px-1.5 py-0.5 bg-gray-800 rounded">Verified</span>
                                    </div>
                                    <div class="flex text-yellow-500 text-xs">
                                        @for($i=1; $i<=5; $i++)
                                            @if($i <= $rev->rating)
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 font-medium">{{ \Carbon\Carbon::parse($rev->created_at)->format('M d, Y') }}</span>
                            </div>
                            
                            <p class="text-gray-300 text-sm leading-relaxed mb-4">
                                {{ $rev->comment }}
                            </p>

                            @auth
                                @if(auth()->id() == $rev->user_id || auth()->user()->isAdmin())
                                    <div class="flex justify-end gap-2 border-t border-gray-800/60 pt-3">
                                        @if(auth()->id() == $rev->user_id)
                                            <a href="{{ route('reviews.edit', $rev->review_id) }}" class="text-xs text-gray-400 hover:text-white transition-colors bg-gray-800 px-2 py-1 rounded">
                                                <i class="fa-solid fa-pen mr-1"></i> Edit
                                            </a>
                                        @endif
                                        <form action="{{ route('reviews.destroy', $rev->review_id) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition-colors bg-red-900/20 px-2 py-1 rounded">
                                                <i class="fa-solid fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                @else
                    <div class="flex flex-col items-center justify-center p-12 border-2 border-dashed border-gray-800 rounded-xl">
                        <i class="fa-regular fa-star text-4xl text-gray-600 mb-3"></i>
                        <h4 class="text-white font-bold mb-1">No Reviews Yet</h4>
                        <p class="text-gray-500 text-sm text-center">Be the first to share your experience with this item.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainPhoto = document.getElementById('mainPhoto');
        if (!mainPhoto) return;

        document.querySelectorAll('.thumb-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const src = this.getAttribute('data-src');
                if (src) {
                    mainPhoto.src = src;
                }
            });
        });
    });
</script>
@endpush
