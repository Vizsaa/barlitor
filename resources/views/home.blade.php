@extends('layouts.app')
@section('title', 'BruTor Shop - Home')

@section('content')
<!-- Hero Section -->
<div class="relative bg-[#1a1a1a] overflow-hidden">
    <!-- Background Pattern/Overlay -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-[#111111] via-[#111111]/80 to-transparent z-10"></div>
        <img src="{{ asset('images/banner.png') }}" alt="Motorcycle Banner" class="w-full h-full object-cover opacity-30 mix-blend-luminosity" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1558981806-ec527fa84c39?q=80&w=2070&auto=format&fit=crop';">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
        <div class="py-20 md:py-32 lg:py-40">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight uppercase">
                Welcome to <span class="text-orange-500">BruTor Shop</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg md:text-xl text-gray-400">
                Premium motorcycle parts, essential tools, and professional gear. Build, repair, and ride with confidence.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                @guest
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-3 bg-orange-500 hover:bg-orange-600 border border-transparent rounded-md font-bold text-white uppercase tracking-wider transition-colors shadow-[0_0_15px_rgba(249,115,22,0.3)]">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Get Started
                    </a>
                @else
                    <a href="{{ route('items.index') }}" class="inline-flex justify-center items-center px-8 py-3 bg-orange-500 hover:bg-orange-600 border border-transparent rounded-md font-bold text-white uppercase tracking-wider transition-colors shadow-[0_0_15px_rgba(249,115,22,0.3)]">
                        <i class="fa-solid fa-bag-shopping mr-2"></i> Start Shopping
                    </a>
                @endguest
                <a href="{{ route('items.index') }}" class="inline-flex justify-center items-center px-8 py-3 bg-transparent hover:bg-white/5 border-2 border-gray-600 rounded-md font-bold text-gray-300 uppercase tracking-wider transition-colors">
                    View Catalog
                </a>
            </div>
        </div>
    </div>
    
    <!-- Decorative bottom border -->
    <div class="absolute bottom-0 w-full h-1 bg-gradient-to-r from-orange-600 via-orange-500 to-[#1a1a1a]"></div>
</div>

<!-- Search Bar -->
<div class="bg-[#111111] border-b border-gray-800 py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('home') }}" class="flex gap-3 items-center">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                </div>
                <input
                    type="text"
                    name="search"
                    value="{{ $searchQuery }}"
                    placeholder="Search products, tools, parts..."
                    @if($searchQuery) autofocus @endif
                    class="bg-[#1a1a1a] border border-gray-700 text-gray-200 text-sm rounded-lg
                           focus:ring-1 focus:ring-orange-500 focus:border-orange-500
                           block w-full pl-11 pr-4 py-3 placeholder-gray-500 transition-colors">
            </div>
            <button type="submit"
                class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg
                       transition-colors text-sm whitespace-nowrap shadow-sm">
                Search
            </button>
            @if($searchQuery)
                <a href="{{ route('home') }}"
                   class="px-4 py-3 bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold rounded-lg
                          transition-colors text-sm whitespace-nowrap border border-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    {{-- ── SEARCH RESULTS MODE ──────────────────────────────────── --}}
    @if($searchQuery)
        <div class="flex items-end justify-between mb-10 border-b border-gray-800 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-white uppercase tracking-tight">
                    Search Results <span class="text-orange-500">for &quot;{{ $searchQuery }}&quot;</span>
                </h2>
                <p class="text-gray-400 mt-1 text-sm">
                    {{ $searchResults->total() }} {{ \Illuminate\Support\Str::plural('item', $searchResults->total()) }} found
                </p>
            </div>
            <a href="{{ route('items.index', ['search' => $searchQuery]) }}"
               class="hidden sm:flex items-center text-orange-500 hover:text-orange-400 font-medium text-sm transition-colors">
                Advanced Search <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>

        @if($searchResults->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @foreach($searchResults as $item)
                    <div class="bg-[#1a1a1a] rounded-lg border border-gray-800 overflow-hidden group hover:-translate-y-1 hover:border-orange-500/50 hover:shadow-[0_8px_30px_rgb(0,0,0,0.5)] transition-all duration-300 flex flex-col h-full">
                        <div class="relative h-48 overflow-hidden bg-[#111111]">
                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 mix-blend-lighten">

                            @if($item->type === 'tool')
                                <div class="absolute top-3 right-3 bg-orange-600 text-white text-xs font-bold px-2 py-1 rounded uppercase tracking-wider shadow-md">
                                    For Rent
                                </div>
                            @else
                                <div class="absolute top-3 right-3 bg-gray-700 text-gray-200 text-xs font-bold px-2 py-1 rounded uppercase tracking-wider shadow-md">
                                    Product
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            @if($item->category)
                                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">
                                    {{ $item->category }}
                                </span>
                            @endif
                            <h3 class="text-base font-bold text-white mb-1 line-clamp-2 group-hover:text-orange-400 transition-colors">
                                {{ $item->title }}
                            </h3>
                            <p class="text-gray-500 text-xs line-clamp-2 mb-3 flex-grow">
                                {{ $item->description }}
                            </p>
                            <div class="mt-auto pt-3 flex items-center justify-between border-t border-gray-800/80">
                                <span class="text-lg font-black text-orange-500">
                                    ₱{{ number_format($item->sell_price, 2) }}
                                </span>
                                <a href="{{ route('items.show', $item->item_id) }}" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-full transition-colors" aria-label="View Details">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($searchResults->hasPages())
                <div class="flex justify-center mt-4">
                    {{ $searchResults->links('pagination::tailwind') }}
                </div>
            @endif
        @else
            <div class="text-center py-16 bg-[#1a1a1a] rounded-lg border border-gray-800">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
                    <i class="fa-solid fa-magnifying-glass text-2xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">No Results Found</h3>
                <p class="text-gray-400 max-w-md mx-auto text-sm">
                    No items matched &quot;<span class="text-orange-400">{{ $searchQuery }}</span>&quot;.
                    Try different keywords or browse the full catalog.
                </p>
                <a href="{{ route('items.index') }}"
                   class="inline-block mt-5 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-md text-sm transition-colors">
                    Browse All Items
                </a>
            </div>
        @endif

    {{-- ── FEATURED ITEMS MODE (no search query) ──────────────────── --}}
    @else
        <div class="flex items-end justify-between mb-10 border-b border-gray-800 pb-4">
            <div>
                <h2 class="text-3xl font-bold text-white uppercase tracking-tight">Featured <span class="text-orange-500">Items</span></h2>
                <p class="text-gray-400 mt-1">Top picks from our inventory</p>
            </div>
            <a href="{{ route('items.index') }}" class="hidden sm:flex items-center text-orange-500 hover:text-orange-400 font-medium transition-colors">
                View All <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>

        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <div class="bg-[#1a1a1a] rounded-lg border border-gray-800 overflow-hidden group hover:-translate-y-1 hover:border-orange-500/50 hover:shadow-[0_8px_30px_rgb(0,0,0,0.5)] transition-all duration-300 flex flex-col h-full">
                        <div class="relative h-48 overflow-hidden bg-[#111111]">
                            @php
                                $image = $item->image_path ? asset($item->image_path) : asset('images/default.png');
                            @endphp
                            <img src="{{ $image }}" alt="{{ $item->title ?: $item->description }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 mix-blend-lighten">

                            @if($item->type === 'tool')
                                <div class="absolute top-3 right-3 bg-orange-600 text-white text-xs font-bold px-2 py-1 rounded uppercase tracking-wider shadow-md">
                                    For Rent
                                </div>
                            @else
                                <div class="absolute top-3 right-3 bg-gray-700 text-gray-200 text-xs font-bold px-2 py-1 rounded uppercase tracking-wider shadow-md">
                                    Product
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-white mb-2 line-clamp-1 group-hover:text-orange-400 transition-colors">
                                {{ $item->title ?: $item->description }}
                            </h3>

                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-800/80">
                                <span class="text-xl font-black text-orange-500">₱{{ number_format($item->sell_price, 2) }}</span>
                                <a href="{{ route('items.show', $item->item_id) }}" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-full transition-colors" aria-label="View Details">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-[#1a1a1a] rounded-lg border border-gray-800">
                <i class="fa-solid fa-box-open text-4xl text-gray-600 mb-3"></i>
                <p class="text-gray-400 font-medium">No featured items available right now.</p>
            </div>
        @endif

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('items.index') }}" class="inline-flex items-center text-orange-500 hover:text-orange-400 font-medium transition-colors">
                View All Catalog <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    @endif
</div>
@endsection
