@extends('layouts.admin')
@section('title', 'Orders - BruTor Admin')
@section('title_header', 'Orders')

@section('content')
<div class="space-y-6">
    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 bg-[#111111] flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white uppercase tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-orange-500"></i> Transactions
                </h2>
                <p class="text-xs text-gray-500 mt-1">Update order status and notify customers automatically.</p>
            </div>
            <span class="text-xs text-gray-500">Total: {{ $orders->count() }}</span>
        </div>

        @if($orders->isEmpty())
            <div class="p-10 text-center text-gray-400">
                <i class="fa-solid fa-inbox text-3xl mb-3 text-gray-600"></i>
                <p>No orders yet.</p>
            </div>
        @else
            <div class="p-6 space-y-4">
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
                        $statusClass = match ($status) {
                            'Delivered' => 'bg-green-900/40 text-green-300 border-green-700',
                            'Canceled' => 'bg-red-900/40 text-red-300 border-red-700',
                            default => 'bg-blue-900/40 text-blue-300 border-blue-700',
                        };

                        $productsCount = $order->productsSold->sum('quantity');
                        $rentalsCount = $order->rentals->sum('quantity');
                    @endphp

                    <details class="group bg-[#111111] rounded-xl border border-gray-800 overflow-hidden">
                        <summary class="cursor-pointer select-none px-5 py-4 hover:bg-[#0f0f0f] transition-colors">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-900 border border-gray-800 text-orange-400 flex-shrink-0">
                                        <i class="fa-solid fa-receipt"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-white font-extrabold">Order #{{ $order->orderinfo_id }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                <i class="fa-regular fa-calendar mr-1"></i> {{ $order->date_placed }}
                                            </span>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 truncate">
                                            <span class="text-gray-300 font-semibold">{{ $order->user->name ?? '—' }}</span>
                                            <span class="text-gray-600 mx-2">•</span>
                                            <span>{{ $order->user->email ?? 'No email' }}</span>
                                        </div>
                                        <div class="mt-2 flex flex-wrap gap-2 text-[11px]">
                                            @if($productsCount > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-900 border border-gray-800 text-gray-300">
                                                    <i class="fa-solid fa-box mr-1 text-orange-400"></i> {{ (int)$productsCount }} products
                                                </span>
                                            @endif
                                            @if($rentalsCount > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-900 border border-gray-800 text-gray-300">
                                                    <i class="fa-solid fa-wrench mr-1 text-orange-400"></i> {{ (int)$rentalsCount }} rentals
                                                </span>
                                            @endif
                                            @if($order->payment)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-900/20 border border-green-800 text-green-300">
                                                    <i class="fa-solid fa-check mr-1"></i> Paid ₱{{ number_format($order->payment->amount, 2) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-900/20 border border-red-800 text-red-300">
                                                    <i class="fa-solid fa-circle-exclamation mr-1"></i> No payment record
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:ml-auto flex flex-col sm:flex-row sm:items-center gap-3">
                                    <div class="text-left sm:text-right">
                                        <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total</div>
                                        <div class="text-lg font-black text-orange-500">₱{{ number_format($orderTotal, 2) }}</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->orderinfo_id) }}" class="flex items-center gap-2">
                                            @csrf
                                            <select name="status"
                                                class="bg-[#0b0b0b] border border-gray-700 text-gray-200 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 px-3 py-2">
                                                <option value="Processing" {{ $order->status === 'Processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="Canceled" {{ $order->status === 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                            </select>
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-md transition-colors">
                                                <i class="fa-solid fa-paper-plane mr-2"></i> Update
                                            </button>
                                        </form>
                                        <span class="text-gray-500 group-open:rotate-180 transition-transform">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </summary>

                        <div class="px-5 pb-5 pt-2 border-t border-gray-800 space-y-5">
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('receipts.download', $order->orderinfo_id) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 text-sm font-bold transition-colors">
                                    <i class="fa-solid fa-file-arrow-down mr-2 text-orange-400"></i> PDF Receipt
                                </a>
                                @if($order->user && $order->user->email)
                                    <a href="mailto:{{ $order->user->email }}"
                                       class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-gray-900 hover:bg-gray-800 border border-gray-800 text-gray-300 text-sm font-bold transition-colors">
                                        <i class="fa-solid fa-envelope mr-2 text-orange-400"></i> Email Customer
                                    </a>
                                @endif
                            </div>

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
                                                    <tr class="hover:bg-[#0b0b0b] transition-colors">
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
                                                    <tr class="hover:bg-[#0b0b0b] transition-colors">
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
                                <div class="bg-[#0b0b0b] border border-gray-800 rounded-lg p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div class="text-sm font-semibold text-gray-200 flex items-center gap-2">
                                            <i class="fa-solid fa-credit-card text-orange-400"></i> Payment
                                        </div>
                                        <div class="text-sm text-gray-300">
                                            <span class="text-gray-500">Amount:</span>
                                            <span class="font-bold text-white">₱{{ number_format($order->payment->amount_paid, 2) }}</span>
                                            <span class="text-gray-600 mx-2">•</span>
                                            <span class="text-gray-500">Paid on:</span>
                                            <span class="font-semibold text-gray-200">{{ $order->payment->paid_on }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

