@extends('layouts.app')
@section('title', 'Edit Expense - BruTor Shop Admin')
@section('title_header', 'Edit Expense')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-800 bg-[#111111]">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-pen-to-square text-orange-500 mr-2"></i> Edit Expense
            </h3>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.expenses.update', $expense->expense_id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Expense Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 transition-colors" value="{{ old('title', $expense->title) }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Amount (₱) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 font-bold">
                                ₱
                            </div>
                            <input type="number" step="0.01" min="0.01" name="amount" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-8 p-2.5 transition-colors" value="{{ old('amount', $expense->amount) }}" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Date <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fa-regular fa-calendar text-gray-500"></i>
                            </div>
                            <input type="date" name="expense_date" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-10 p-2.5 transition-colors" value="{{ old('expense_date', $expense->expense_date) }}" required>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Notes (optional)</label>
                        <textarea name="notes" rows="3" class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 transition-colors">{{ old('notes', $expense->notes) }}</textarea>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-800">
                    <a href="{{ route('admin.expenses.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 rounded-md transition-colors border border-gray-700 shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-md transition-colors shadow-sm flex items-center">
                        <i class="fa-solid fa-check mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
