@extends('layouts.app')
@section('title', 'Suppliers - BruTor Shop Admin')
@section('title_header', 'Supplier Management')

@section('content')
<div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-[#111111]">
        <div>
            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Active Suppliers</h3>
            <p class="text-xs text-gray-400 mt-1">Manage your item suppliers and contact information.</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 rounded-md font-bold text-white transition-colors shadow-sm text-sm">
            <i class="fa-solid fa-plus mr-2"></i> Add Supplier
        </a>
    </div>

    @if($activeSuppliers->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">ID</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Name</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Contact</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Lead Time</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @foreach($activeSuppliers as $row)
                        <tr class="hover:bg-[#111111] transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400 w-16">#{{ $row->supplier_id }}</td>
                            <td class="px-6 py-4 font-bold text-white">
                                {{ $row->name }}
                                @if($row->website)
                                    <a href="{{ $row->website }}" target="_blank" class="block text-xs font-normal text-orange-400 hover:text-orange-300 mt-1">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Website
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                <div class="flex flex-col gap-1 text-xs">
                                    <span><i class="fa-solid fa-envelope w-4 text-gray-500"></i> {{ $row->contact_email }}</span>
                                    <span><i class="fa-solid fa-phone w-4 text-gray-500"></i> {{ $row->contact_phone }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-300 whitespace-nowrap">
                                <span class="bg-gray-800 text-gray-300 px-2.5 py-1 rounded text-xs font-semibold border border-gray-700">
                                    <i class="fa-solid fa-clock mr-1 text-gray-500"></i> {{ $row->lead_time }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('admin.suppliers.edit', $row->supplier_id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs font-semibold transition-colors border border-gray-600 shadow-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
                                </a>
                                <form action="{{ route('admin.suppliers.destroy', $row->supplier_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this supplier?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center justify-center px-3 py-1.5 bg-red-900/30 hover:bg-red-900/50 border border-red-800/50 text-red-400 hover:text-red-300 rounded text-xs font-semibold transition-colors shadow-sm" type="submit">
                                        <i class="fa-solid fa-trash mr-1.5"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-8 text-center text-gray-400">
            <i class="fa-solid fa-truck-field-slash text-4xl mb-3 text-gray-600"></i>
            <h4 class="text-white font-bold mb-1">No Active Suppliers</h4>
            <p>You haven't added any suppliers yet.</p>
        </div>
    @endif
</div>

<!-- Deleted Suppliers -->
@if($deletedSuppliers->count() > 0)
<div class="bg-gray-900 rounded-xl border border-gray-800 border-dashed shadow-sm overflow-hidden opacity-75 hover:opacity-100 transition-opacity">
    <div class="px-6 py-4 border-b border-gray-800 bg-[#111111]">
        <h3 class="text-md font-bold text-gray-300 uppercase tracking-wide flex items-center">
            <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-500"></i> Deleted Suppliers
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase tracking-wider bg-gray-800/30 border-b border-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 font-semibold">Name</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Contact</th>
                    <th scope="col" class="px-6 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/40">
                @foreach($deletedSuppliers as $row)
                    <tr class="hover:bg-[#151515] transition-colors">
                        <td class="px-6 py-3 text-gray-400 font-medium line-through decoration-gray-600">{{ $row->name }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $row->contact_email }}</td>
                        <td class="px-6 py-3 text-right">
                            <a href="{{ route('admin.suppliers.restore', $row->supplier_id) }}" class="inline-flex items-center justify-center px-3 py-1 bg-green-900/30 hover:bg-green-900/50 border border-green-800/50 text-green-400 hover:text-green-300 rounded text-xs font-semibold transition-colors disabled:opacity-50" onclick="return confirm('Restore this supplier?');">
                                <i class="fa-solid fa-rotate-left mr-1.5"></i> Restore
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
