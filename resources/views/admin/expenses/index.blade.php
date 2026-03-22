@extends('layouts.app')
@section('title', 'Expenses - BarliTor Shop Admin')
@section('title_header', 'Expense Tracking')

@section('content')
<div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-[#111111]">
        <div>
            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Business Expenses</h3>
            <p class="text-xs text-gray-400 mt-1">Track and manage store expenditures.</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 rounded-md font-bold text-white transition-colors shadow-sm text-sm">
            <i class="fa-solid fa-plus mr-2"></i> Add Expense
        </a>
    </div>

    @if($expenses->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold w-24">ID</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Title & Date</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Amount</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Notes</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @foreach($expenses as $expense)
                        <tr class="hover:bg-[#111111] transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400">#{{ $expense->expense_id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $expense->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fa-regular fa-calendar mr-1"></i> 
                                    {{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-sm font-bold bg-red-900/30 text-red-400 border border-red-800/50 tracking-wide">
                                    ₱{{ number_format($expense->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm max-w-xs truncate">
                                {{ $expense->notes ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('admin.expenses.edit', $expense->expense_id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs font-semibold transition-colors border border-gray-600 shadow-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
                                </a>
                                <form action="{{ route('admin.expenses.destroy', $expense->expense_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this expense?');">
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
            <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-600"></i>
            <h4 class="text-white font-bold mb-1">No Expenses Recorded</h4>
            <p>You haven't added any business expenses yet.</p>
        </div>
    @endif
</div>
@endsection
