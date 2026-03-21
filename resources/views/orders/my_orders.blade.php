@extends('layouts.app')
@section('title', 'My Orders - BruTor Shop')

@section('content')
<div class="bg-[#1a1a1a] border-b border-gray-800 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white uppercase tracking-tight">
                    <i class="fa-solid fa-box text-orange-500 mr-2"></i> My Orders
                </h1>
                <p class="text-gray-400 text-sm mt-1">Review your purchases and tool rentals.</p>
            </div>
            <a href="{{ route('items.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 transition-colors text-sm font-semibold">
                <i class="fa-solid fa-store mr-2 text-orange-400"></i> Continue Shopping
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Filters -->
    <div class="bg-[#1a1a1a] p-5 rounded-xl border border-gray-800 shadow-sm">
        <form method="GET" action="{{ route('orders.mine') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">From (Order Date)</label>
                <input type="date" name="from" value="{{ $dateFrom }}"
                    class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">To (Order Date)</label>
                <input type="date" name="to" value="{{ $dateTo }}"
                    class="bg-[#111111] border border-gray-700 text-gray-300 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
            </div>
            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="flex-1 inline-flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-4 rounded-md transition-colors text-sm shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('orders.mine') }}" class="flex-1 inline-flex items-center justify-center bg-gray-700 hover:bg-gray-600 text-gray-200 font-bold py-2.5 px-4 rounded-md transition-colors text-sm border border-gray-600">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-16 bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800 mb-4">
                <i class="fa-solid fa-inbox text-2xl text-gray-500"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No Orders Found</h3>
            <p class="text-gray-400 max-w-md mx-auto text-sm">Try adjusting the date range or place an order from the catalog.</p>
            <a href="{{ route('items.index') }}"
                class="inline-flex mt-5 items-center justify-center px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-md text-sm transition-colors">
                Browse Items <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $orderTotal = 0;
                    foreach ($order->productsSold as $ps) {
                        $orderTotal += $ps->quantity * $ps->rate_charged;
                    }
                    foreach ($order->rentals as $r) {
                        $orderTotal += $r->rate_charged;
                    }

                    $status = (string) ($order->status ?? '—');
                    $statusClass = match (strtolower($status)) {
                        'completed' => 'bg-green-900/40 text-green-300 border-green-700',
                        'processing' => 'bg-blue-900/40 text-blue-300 border-blue-700',
                        'cancelled', 'canceled' => 'bg-red-900/40 text-red-300 border-red-700',
                        default => 'bg-gray-800 text-gray-200 border-gray-700',
                    };
                @endphp

                <details class="group bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden">
                    <summary class="cursor-pointer select-none px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-3 hover:bg-[#111111] transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-800 border border-gray-700 text-orange-400">
                                <i class="fa-solid fa-receipt"></i>
                            </span>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-white font-bold">Order #{{ $order->orderinfo_id }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Order date: <span class="text-gray-300">{{ $order->date_placed }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="sm:ml-auto flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total</div>
                                <div class="text-lg font-black text-orange-500">₱{{ number_format($orderTotal, 2) }}</div>
                            </div>
                            <span class="text-gray-500 group-open:rotate-180 transition-transform">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>
                    </summary>

                    <div class="px-5 pb-5 pt-2 border-t border-gray-800 space-y-5">
                        @if($order->productsSold->count() > 0)
                            <div>
                                <h3 class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-box text-orange-400"></i> Products
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left whitespace-nowrap">
                                        <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border border-gray-800">
                                            <tr>
                                                <th class="px-4 py-2.5 font-semibold">Item</th>
                                                <th class="px-4 py-2.5 font-semibold text-center">Qty</th>
                                                <th class="px-4 py-2.5 font-semibold text-right">Rate</th>
                                                <th class="px-4 py-2.5 font-semibold text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-800/60 border border-gray-800 border-t-0">
                                            @foreach($order->productsSold as $ps)
                                                <tr class="hover:bg-[#111111] transition-colors">
                                                    <td class="px-4 py-3 text-white">{{ $ps->item->title ?? 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-center text-gray-300">{{ (int) $ps->quantity }}</td>
                                                    <td class="px-4 py-3 text-right text-gray-300">₱{{ number_format($ps->rate_charged, 2) }}</td>
                                                    <td class="px-4 py-3 text-right font-bold text-gray-100">₱{{ number_format($ps->quantity * $ps->rate_charged, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if($order->rentals->count() > 0)
                            <div>
                                <h3 class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-wrench text-orange-400"></i> Tool Rentals
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left whitespace-nowrap">
                                        <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border border-gray-800">
                                            <tr>
                                                <th class="px-4 py-2.5 font-semibold">Tool</th>
                                                <th class="px-4 py-2.5 font-semibold text-center">Start</th>
                                                <th class="px-4 py-2.5 font-semibold text-center">Due</th>
                                                <th class="px-4 py-2.5 font-semibold text-center">Qty</th>
                                                <th class="px-4 py-2.5 font-semibold text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-800/60 border border-gray-800 border-t-0">
                                            @foreach($order->rentals as $r)
                                                <tr class="hover:bg-[#111111] transition-colors">
                                                    <td class="px-4 py-3 text-white">{{ $r->item->title ?? 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-center text-gray-300">{{ $r->start_date }}</td>
                                                    <td class="px-4 py-3 text-center text-gray-300">{{ $r->due_date }}</td>
                                                    <td class="px-4 py-3 text-center text-gray-300">{{ (int) $r->quantity }}</td>
                                                    <td class="px-4 py-3 text-right font-bold text-gray-100">₱{{ number_format($r->rate_charged, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if($order->payment)
                            <div class="bg-[#111111] border border-gray-800 rounded-lg p-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div class="text-sm font-semibold text-gray-200 flex items-center gap-2">
                                        <i class="fa-solid fa-credit-card text-orange-400"></i> Payment
                                    </div>
                                    <div class="text-sm text-gray-300">
                                        <span class="text-gray-500">Amount Paid:</span>
                                        <span class="font-bold text-white">₱{{ number_format($order->payment->amount, 2) }}</span>
                                        <span class="text-gray-600 mx-2">•</span>
                                        <span class="text-gray-500">Paid on:</span>
                                        <span class="font-semibold text-gray-200">{{ $order->payment->payment_date }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3 pt-2">
                            <a href="{{ route('receipts.download', $order->orderinfo_id) }}"
                               class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 text-sm font-bold transition-colors">
                                <i class="fa-solid fa-file-arrow-down mr-2 text-orange-400"></i> Download PDF Receipt
                            </a>
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
    @endif
</div>
@endsection
