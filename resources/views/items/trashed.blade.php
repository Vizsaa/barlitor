@extends('layouts.app')
@section('title', 'Trashed Items - BarliTor Shop')

@section('content')
<div class="bg-[#1a1a1a] border-b border-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Trashed <span class="text-orange-500">Items</span></h1>
            <p class="text-gray-400 mt-1">Soft-deleted items that can be restored ({{ $items->count() }} total)</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('items.index') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 transition-colors text-sm font-semibold">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Items
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($items->isEmpty())
        <div class="text-center py-16 bg-[#1a1a1a] rounded-lg border border-gray-800 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
                <i class="fa-solid fa-trash-can text-2xl text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No Trashed Items</h3>
            <p class="text-gray-400 max-w-md mx-auto">There are currently no soft-deleted items. Deleted items will appear here and can be restored.</p>
        </div>
    @else
        <div class="bg-[#1a1a1a] border border-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-200 uppercase tracking-wider">
                    Trashed Items
                </h2>
                <span class="text-xs text-gray-500">Showing {{ $items->count() }} record(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs text-left text-gray-300">
                    <thead class="bg-[#151515] text-[11px] uppercase tracking-wider text-gray-400 border-b border-gray-800">
                        <tr>
                            <th class="px-4 py-2">Image</th>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Deleted At</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($items as $item)
                            <tr class="hover:bg-[#181818]">
                                <td class="px-4 py-3 align-middle">
                                    @php
                                        $thumb = $item->thumbnail ?? asset($item->image_path ?: 'images/items/default.png');
                                    @endphp
                                    <img src="{{ $thumb }}" alt="{{ $item->title }}" class="w-12 h-12 rounded object-cover border border-gray-700">
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="text-sm font-semibold text-white">{{ $item->title }}</div>
                                    <div class="text-[11px] text-gray-500">
                                        ID: {{ $item->item_id }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle text-xs text-gray-300">
                                    {{ $item->category ?? 'Misc' }}
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold 
                                        {{ $item->type === 'tool' ? 'bg-orange-900/50 text-orange-300 border border-orange-700' : 'bg-gray-800 text-gray-200 border border-gray-700' }}">
                                        @if($item->type === 'tool')
                                            <i class="fa-solid fa-wrench mr-1"></i> Tool
                                        @else
                                            <i class="fa-solid fa-box mr-1"></i> Product
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle text-xs text-gray-300">
                                    {{ optional($item->deleted_at)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3 align-middle text-right">
                                    <form action="{{ route('admin.items.restore', $item->item_id) }}" method="POST" onsubmit="return confirm('Restore this item?');" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-md bg-green-900/50 hover:bg-green-800 text-[11px] text-green-200 border border-green-700 font-semibold inline-flex items-center gap-1">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
