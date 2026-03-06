@extends('layouts.app')
@section('title', 'Items - BruTor Shop')

@section('content')
<!-- Page Header -->
<div class="bg-[#1a1a1a] border-b border-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Shop <span class="text-orange-500">Inventory</span></h1>
                <p class="text-gray-400 mt-1">Browse our complete selection of parts and tools ({{ $items->count() }} items)</p>
            </div>
            
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.items.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded-md font-bold text-white transition-colors shadow-lg">
                        <i class="fa-solid fa-plus mr-2"></i> Add Item
                    </a>
                @endif
            @endauth
        </div>

        <div class="mt-4">
            @guest
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-gray-300 border border-gray-700">
                    Browsing as <strong class="ml-1 text-white">Guest</strong>
                </div>
            @else
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-800 text-gray-300 border border-gray-700">
                    Logged in as <strong class="ml-1 text-white">{{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }}</strong>
                </div>
            @endguest
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filters Area -->
    <div class="bg-[#1a1a1a] p-4 rounded-lg border border-gray-800 mb-8 shadow-sm">
        <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Search Items</label>
                <div class="relative">
                    <input type="text" name="search" placeholder="Keywords..." value="{{ $keyword }}"
                        class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-3 py-2 pr-10">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-500"></i>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Category</label>
                <select name="category" class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $cat === $category ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Sort Order</label>
                <select name="sort" class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                    <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>Default (A–Z)</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="type" {{ $sort === 'type' ? 'selected' : '' }}>Type (Products / Tools)</option>
                </select>
            </div>
            
            <div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md text-sm font-bold transition-colors border border-gray-600 hover:border-gray-500">
                    <i class="fa-solid fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Results Grid -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($items as $item)
                <div class="bg-[#1a1a1a] rounded-lg border border-gray-800 overflow-hidden group hover:border-orange-500/50 hover:shadow-[0_8px_30px_rgb(0,0,0,0.5)] transition-all duration-300 flex flex-col h-full relative">
                    
                    <!-- Badges -->
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                        @if($item->type === 'tool')
                            <span class="bg-orange-600 text-white text-xs font-bold px-2 py-1 rounded shadow-md uppercase tracking-wide">
                                <i class="fa-solid fa-wrench mr-1"></i> For Rent
                            </span>
                        @endif
                        @if($item->stock_quantity <= 0)
                            <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-md uppercase tracking-wide">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Image -->
                    <a href="{{ route('items.show', $item->item_id) }}" class="relative h-56 overflow-hidden bg-[#111111] block">
                        @php
                            $img = $item->image_path ? asset($item->image_path) : asset('images/default.png');
                        @endphp
                        <img src="{{ $img }}" alt="{{ $item->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 mix-blend-lighten">
                        
                        <!-- Overlay gradient for text readability if needed -->
                        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-[#1a1a1a] to-transparent opacity-50"></div>
                    </a>

                    <!-- Details -->
                    <div class="p-5 flex flex-col flex-grow relative">
                        <!-- Category float -->
                        <div class="absolute -top-3 right-4 bg-gray-800 border border-gray-700 px-2 py-0.5 rounded text-[10px] font-bold text-gray-400 uppercase tracking-wider backdrop-blur-sm">
                            {{ $item->category ?? 'Misc' }}
                        </div>

                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 pb-1 group-hover:text-orange-400 transition-colors">
                            <a href="{{ route('items.show', $item->item_id) }}">
                                {{ $item->title }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-400 text-sm line-clamp-2 mb-4 flex-grow">{{ $item->description }}</p>

                        <div class="mt-auto border-t border-gray-800 pt-3 flex flex-col gap-2">
                            <div class="flex justify-between items-end">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Price</span>
                                    <span class="text-xl font-black text-orange-500">₱{{ number_format($item->sell_price, 2) }}</span>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded {{ $item->stock_quantity <= 0 ? 'bg-red-900/40 text-red-400' : 'bg-green-900/40 text-green-400' }}">
                                    Stock: {{ (int) $item->stock_quantity }}
                                </span>
                            </div>

                            @auth
                                @if(auth()->user()->isAdmin())
                                    <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-gray-800/50">
                                        <a href="{{ route('admin.items.edit', $item->item_id) }}" class="text-center py-1.5 px-3 text-xs bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors font-semibold">
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.items.destroy', $item->item_id) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-center py-1.5 px-3 text-xs bg-red-900/50 hover:bg-red-800 border border-red-800 text-red-200 rounded transition-colors font-semibold">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-[#1a1a1a] rounded-lg border border-gray-800 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
                <i class="fa-solid fa-boxes-stacked text-2xl text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No Items Found</h3>
            <p class="text-gray-400 max-w-md mx-auto">We couldn't find any items matching your current filters. Try adjusting your search or clearing filters to see more results.</p>
            <a href="{{ route('items.index') }}" class="inline-block mt-4 text-orange-500 hover:text-orange-400 font-semibold transition-colors">
                Clear all filters
            </a>
        </div>
    @endif
</div>
@endsection
