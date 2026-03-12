@extends('layouts.app')
@section('title', 'Add Supplier - BruTor Shop Admin')
@section('title_header', 'Add Supplier')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-800 bg-[#111111]">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-plus text-orange-500 mr-2"></i> Add New Supplier
            </h3>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.suppliers.store') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 transition-colors" value="{{ old('name') }}" required placeholder="e.g. Acme Parts Co.">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-500"></i>
                            </div>
                            <input type="email" name="contact_email" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 transition-colors" value="{{ old('contact_email') }}" placeholder="contact@example.com">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-phone text-gray-500"></i>
                            </div>
                            <input type="text" name="contact_phone" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 transition-colors" value="{{ old('contact_phone') }}" placeholder="0917-123-4567">
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Expected Lead Time</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-clock text-gray-500"></i>
                            </div>
                            <input type="text" name="lead_time" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 transition-colors" value="{{ old('lead_time') }}" placeholder="e.g. 3-5 days">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Average time from order to delivery.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Website</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-solid fa-globe text-gray-500"></i>
                            </div>
                            <input type="url" name="website" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 transition-colors" value="{{ old('website') }}" placeholder="https://...">
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-800">
                    <a href="{{ route('admin.suppliers.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 rounded-md transition-colors border border-gray-700 shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-md transition-colors shadow-sm flex items-center">
                        <i class="fa-solid fa-check mr-2"></i> Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
