@extends('layouts.app')
@section('title', 'Items - BarliTor Shop')

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
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.items.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded-md font-bold text-white transition-colors shadow-lg">
                            <i class="fa-solid fa-plus mr-2"></i> Add Item
                        </a>
                        <a href="{{ route('admin.items.trashed') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-md font-semibold text-gray-200 border border-gray-700 transition-colors">
                            <i class="fa-solid fa-trash-can mr-2 text-orange-400"></i>
                            Trashed Items ({{ $trashedCount ?? 0 }})
                        </a>
                    </div>
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
        <form method="GET" action="{{ route('items.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Search Items</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Keywords..." value="{{ $keyword }}"
                               class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block w-full p-2 pr-10">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fa-solid fa-search text-gray-500"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Category</label>
                    <select name="category"
                            class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                        <option value="all" {{ $category === 'all' || $category === '' ? 'selected' : '' }}>All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $cat === $category ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Type</label>
                    <select name="type"
                            class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                        <option value="" {{ $type === '' ? 'selected' : '' }}>All Types</option>
                        <option value="product" {{ $type === 'product' ? 'selected' : '' }}>Products</option>
                        <option value="tool" {{ $type === 'tool' ? 'selected' : '' }}>Tools (Rental)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Min Price</label>
                    <input type="number" name="min_price" min="0" step="0.01" value="{{ $minPrice }}"
                           class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block w-full p-2"
                           placeholder="0.00">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Max Price</label>
                    <input type="number" name="max_price" min="0" step="0.01" value="{{ $maxPrice }}"
                           class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block w-full p-2"
                           placeholder="0.00">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Sort</label>
                    <select name="sort"
                            class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block w-full p-2">
                        <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>Default (A–Z)</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="type" {{ $sort === 'type' ? 'selected' : '' }}>By Type</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('items.index') }}"
                   class="bg-gray-700 hover:bg-gray-600 text-white border border-gray-600 rounded-md px-4 py-2 text-sm font-bold inline-flex items-center justify-center">
                    <i class="fa-solid fa-xmark mr-2"></i> Clear Filters
                </a>
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white rounded-md px-4 py-2 text-sm font-bold inline-flex items-center justify-center">
                    <i class="fa-solid fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>

        @if($keyword || ($category && $category !== 'all') || $type || $minPrice !== '' || $maxPrice !== '' || $sort !== 'default')
            @php
                $sortLabels = [
                    'price_asc'  => 'Price: Low → High',
                    'price_desc' => 'Price: High → Low',
                    'newest'     => 'Newest First',
                    'oldest'     => 'Oldest First',
                    'type'       => 'By Type',
                ];
            @endphp
            <div class="mt-3 pt-3 border-t border-gray-800 flex flex-wrap items-center gap-2">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold mr-1">Active filters:</span>

                @if($keyword)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Search: &quot;{{ $keyword }}&quot;
                        <a href="{{ route('items.index', array_filter([
                            'category'  => ($category && $category !== 'all') ? $category : null,
                            'type'      => $type ?: null,
                            'sort'      => ($sort && $sort !== 'default') ? $sort : null,
                            'min_price' => $minPrice !== '' ? $minPrice : null,
                            'max_price' => $maxPrice !== '' ? $maxPrice : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @endif

                @if($category && $category !== 'all')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Category: {{ $category }}
                        <a href="{{ route('items.index', array_filter([
                            'search'    => $keyword ?: null,
                            'type'      => $type ?: null,
                            'sort'      => ($sort && $sort !== 'default') ? $sort : null,
                            'min_price' => $minPrice !== '' ? $minPrice : null,
                            'max_price' => $maxPrice !== '' ? $maxPrice : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @endif

                @if($type)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Type: {{ ucfirst($type) }}
                        <a href="{{ route('items.index', array_filter([
                            'search'    => $keyword ?: null,
                            'category'  => ($category && $category !== 'all') ? $category : null,
                            'sort'      => ($sort && $sort !== 'default') ? $sort : null,
                            'min_price' => $minPrice !== '' ? $minPrice : null,
                            'max_price' => $maxPrice !== '' ? $maxPrice : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @endif

                @if($minPrice !== '' && $maxPrice !== '')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Price: ₱{{ number_format((float) $minPrice, 2) }} – ₱{{ number_format((float) $maxPrice, 2) }}
                        <a href="{{ route('items.index', array_filter([
                            'search'   => $keyword ?: null,
                            'category' => ($category && $category !== 'all') ? $category : null,
                            'type'     => $type ?: null,
                            'sort'     => ($sort && $sort !== 'default') ? $sort : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @elseif($minPrice !== '')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Price: ≥ ₱{{ number_format((float) $minPrice, 2) }}
                        <a href="{{ route('items.index', array_filter([
                            'search'    => $keyword ?: null,
                            'category'  => ($category && $category !== 'all') ? $category : null,
                            'type'      => $type ?: null,
                            'sort'      => ($sort && $sort !== 'default') ? $sort : null,
                            'max_price' => $maxPrice !== '' ? $maxPrice : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @elseif($maxPrice !== '')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Price: ≤ ₱{{ number_format((float) $maxPrice, 2) }}
                        <a href="{{ route('items.index', array_filter([
                            'search'    => $keyword ?: null,
                            'category'  => ($category && $category !== 'all') ? $category : null,
                            'type'      => $type ?: null,
                            'sort'      => ($sort && $sort !== 'default') ? $sort : null,
                            'min_price' => $minPrice !== '' ? $minPrice : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @endif

                @if($sort && $sort !== 'default')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 border border-orange-500/40">
                        Sort: {{ $sortLabels[$sort] ?? ucfirst($sort) }}
                        <a href="{{ route('items.index', array_filter([
                            'search'    => $keyword ?: null,
                            'category'  => ($category && $category !== 'all') ? $category : null,
                            'type'      => $type ?: null,
                            'min_price' => $minPrice !== '' ? $minPrice : null,
                            'max_price' => $maxPrice !== '' ? $maxPrice : null,
                        ])) }}" class="text-orange-300 hover:text-white ml-0.5 font-bold leading-none">×</a>
                    </span>
                @endif
            </div>
        @endif
    </div>

    @auth
        @if(auth()->user()->isAdmin())
            <!-- Admin Import Panel -->
            <div x-data="{ open: false }" class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-gray-300 uppercase tracking-wider">Bulk Import</h2>
                    <button type="button" @click="open = !open"
                        class="text-xs px-3 py-1 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 transition-colors inline-flex items-center gap-2">
                        <i class="fa-solid fa-file-import text-orange-400"></i>
                        <span x-text="open ? 'Hide Import' : 'Show Import'"></span>
                    </button>
                </div>
                <div x-show="open" x-cloak class="bg-[#1a1a1a] border border-gray-800 rounded-lg p-4 text-sm text-gray-300">
                    <p class="mb-3 text-gray-400">
                        Upload a <span class="font-semibold text-orange-400">.xlsx</span>, <span class="font-semibold text-orange-400">.xls</span>, or <span class="font-semibold text-orange-400">.csv</span> file. The first row must contain headings.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <a href="{{ route('admin.items.downloadTemplate') }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-800 hover:bg-gray-700 text-xs font-semibold text-gray-100 border border-gray-700">
                            <i class="fa-solid fa-download mr-2 text-orange-400"></i>
                            Download Template
                        </a>
                    </div>
                    <form method="POST" action="{{ route('admin.items.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                        @csrf
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Import File</label>
                            <input type="file" name="import_file" accept=".xlsx,.xls,.csv"
                                class="block w-full text-xs text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-200 hover:file:bg-gray-700 bg-[#111111] border border-gray-700 rounded-md cursor-pointer">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-md border border-orange-600 shadow-sm">
                            <i class="fa-solid fa-file-import mr-2"></i> Import Items
                        </button>
                    </form>
                </div>
            </div>

            <!-- Admin DataTable -->
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-200 uppercase tracking-wider">
                        All Items
                    </h2>
                    <span class="text-xs text-gray-500">Total: {{ $items->count() }}</span>
                </div>
                <div class="p-4 overflow-x-auto">
                    <table id="itemsTable" class="min-w-full text-xs text-left text-gray-300">
                        <thead class="bg-[#151515] text-[11px] uppercase tracking-wider text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">Image</th>
                                <th class="px-3 py-2">Title</th>
                                <th class="px-3 py-2">Category</th>
                                <th class="px-3 py-2">Type</th>
                                <th class="px-3 py-2">Stock</th>
                                <th class="px-3 py-2">Price</th>
                                <th class="px-3 py-2">Supplier</th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($items as $index => $item)
                                <tr class="hover:bg-[#181818]">
                                    <td class="px-3 py-2 align-middle text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 align-middle">
                                        <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" class="w-10 h-10 rounded object-cover border border-gray-700">
                                    </td>
                                    <td class="px-3 py-2 align-middle">
                                        <a href="{{ route('items.show', $item->item_id) }}" class="text-sm font-semibold text-white hover:text-orange-400">
                                            {{ $item->title }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-xs text-gray-300">
                                        {{ $item->category ?? 'Misc' }}
                                    </td>
                                    <td class="px-3 py-2 align-middle">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold 
                                            {{ $item->type === 'tool' ? 'bg-orange-900/50 text-orange-300 border border-orange-700' : 'bg-gray-800 text-gray-200 border border-gray-700' }}">
                                            @if($item->type === 'tool')
                                                <i class="fa-solid fa-wrench mr-1"></i> Tool
                                            @else
                                                <i class="fa-solid fa-box mr-1"></i> Product
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 align-middle">
                                        <span class="inline-flex items-center text-xs px-2 py-0.5 rounded 
                                            {{ $item->stock_quantity <= 0 ? 'bg-red-900/40 text-red-300 border border-red-700' : 'bg-green-900/40 text-green-300 border border-green-700' }}">
                                            {{ (int) $item->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 align-middle text-sm font-semibold text-orange-400">
                                        ₱{{ number_format($item->sell_price, 2) }}
                                    </td>
                                    <td class="px-3 py-2 align-middle text-xs text-gray-300">
                                        {{ optional($item->supplier)->name ?? '—' }}
                                    </td>
                                    <td class="px-3 py-2 align-middle text-right">
                                        <div class="inline-flex gap-2">
                                            <a href="{{ route('admin.items.edit', $item->item_id) }}" class="px-2 py-1 rounded bg-gray-800 hover:bg-gray-700 text-[11px] text-gray-100 border border-gray-700">
                                                <i class="fa-regular fa-pen-to-square mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.items.destroy', $item->item_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 rounded bg-red-900/50 hover:bg-red-800 text-[11px] text-red-200 border border-red-700">
                                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Customer / Guest Card Grid -->
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
                                <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" 
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
        @endif
    @else
        <!-- Guest / Non-admin Card Grid -->
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
                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" 
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
    @endauth
</div>
@endsection

@push('styles')
    @auth
        @if(auth()->user()->isAdmin())
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
            <style>
                #itemsTable_wrapper {
                    color: #e5e7eb;
                }
                #itemsTable_wrapper .dataTables_length select,
                #itemsTable_wrapper .dataTables_filter input {
                    background-color: #111111;
                    border-color: #374151;
                    color: #e5e7eb;
                    border-radius: 0.375rem;
                    padding: 0.25rem 0.5rem;
                }
                #itemsTable_wrapper .dataTables_paginate .paginate_button {
                    color: #9ca3af !important;
                }
                #itemsTable_wrapper .dataTables_paginate .paginate_button.current {
                    background-color: #f97316 !important;
                    color: #ffffff !important;
                    border-color: #ea580c !important;
                }
                #itemsTable_wrapper .dataTables_paginate .paginate_button:hover {
                    background-color: #374151 !important;
                    color: #ffffff !important;
                }
                #itemsTable {
                    border-color: #1f2937;
                }
            </style>
        @endif
    @endauth
@endpush

@push('scripts')
    @auth
        @if(auth()->user()->isAdmin())
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (window.jQuery && $('#itemsTable').length) {
                        $('#itemsTable').DataTable({
                            pageLength: 15,
                            order: [[2, 'asc']],
                            columnDefs: [
                                { orderable: false, targets: [1, 8] }
                            ]
                        });
                    }
                });
            </script>
        @endif
    @endauth
@endpush
