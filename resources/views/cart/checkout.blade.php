@extends('layouts.app')
@section('title', 'Checkout - BarliTor Shop')

@section('content')
<div class="bg-[#1a1a1a] border-b border-gray-800 py-6 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-3 text-white">
            <i class="fa-solid fa-lock text-orange-500 text-xl"></i>
            <h1 class="text-2xl font-bold tracking-tight uppercase">Secure Checkout</h1>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <!-- Order Items Review -->
        <div class="lg:w-7/12 order-2 lg:order-1">
            <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-800 bg-gray-800/30 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-white uppercase tracking-wide">Order Review</h2>
                    <a href="{{ route('cart.index') }}" class="text-sm text-orange-500 hover:text-orange-400 transition-colors">Edit Cart</a>
                </div>
                
                <div class="p-6">
                    @if(!empty($cart['products']))
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4 pb-2 border-b border-gray-800"><i class="fa-solid fa-box mr-2"></i> Products</h3>
                        <div class="space-y-4 mb-8">
                            @foreach($cart['products'] as $pid => $product)
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 pr-4">
                                        <h4 class="text-white font-medium text-sm">{{ $product['title'] }}</h4>
                                        <div class="text-xs text-gray-500 mt-1">Qty: {{ $product['quantity'] }} &times; ₱{{ number_format($product['price'], 2) }}</div>
                                    </div>
                                    <div class="text-right font-semibold text-white">
                                        ₱{{ number_format($product['price'] * $product['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($cart['tools']))
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4 pb-2 border-b border-gray-800"><i class="fa-solid fa-wrench mr-2"></i> Tool Rentals</h3>
                        <div class="space-y-4">
                            @foreach($cart['tools'] as $tool)
                                @php
                                    $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                                    $subtotal = $tool['rate'] * $days * $tool['quantity'];
                                @endphp
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 pr-4">
                                        <h4 class="text-white font-medium text-sm">{{ $tool['title'] }}</h4>
                                        <div class="text-xs text-gray-500 mt-1 flex flex-wrap gap-x-3">
                                            <span>Period: {{ \Carbon\Carbon::parse($tool['start_date'])->format('M d') }} to {{ \Carbon\Carbon::parse($tool['due_date'])->format('M d') }} ({{(int)$days}} days)</span>
                                            <span>Qty: {{ $tool['quantity'] }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right font-semibold text-white">
                                        ₱{{ number_format($subtotal, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm p-6 flex flex-col items-center text-center">
                 <i class="fa-solid fa-shield-halved text-4xl text-gray-700 mb-3"></i>
                 <h4 class="text-white font-bold mb-1">Secure Transaction</h4>
                 <p class="text-gray-400 text-sm max-w-sm">All transactions are secure and encrypted. Your information is never shared with third parties.</p>
            </div>
        </div>

        <!-- Payment Setup -->
        <div class="lg:w-5/12 order-1 lg:order-2">
            <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-lg sticky top-24">
                <div class="px-6 py-4 border-b border-gray-800 bg-[#111111] rounded-t-xl">
                    <h2 class="text-lg font-bold text-white uppercase tracking-wide">Summary & Payment</h2>
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-800">
                        <span class="text-gray-300">Total Amount Due</span>
                        <span class="text-3xl font-black text-orange-500">₱{{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="amount_paid" class="block text-sm font-bold text-gray-300 mb-2">Payment Amount (₱)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">₱</span>
                                </div>
                                <input type="number" step="0.01" min="{{ $grandTotal }}" name="amount_paid" id="amount_paid" value="{{ $grandTotal }}" required
                                    class="bg-[#111111] border border-gray-700 text-white text-lg rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full pl-8 pr-3 py-3 font-semibold shadow-inner">
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Must be at least the total amount due.</p>
                        </div>
                        
                        <!-- Simulated Payment Method Selection -->
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-300 mb-3">Payment Method</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-orange-500 bg-orange-900/10 rounded cursor-pointer transition-colors relative">
                                    <input type="radio" name="payment_method" value="cash" checked class="text-orange-500 focus:ring-orange-500 bg-black border-gray-700 w-5 h-5">
                                    <div class="ml-3 flex flex-col">
                                        <span class="text-white font-medium text-sm">Cash on Pickup / Store Counter</span>
                                        <span class="text-gray-500 text-xs">Pay when you collect your items</span>
                                    </div>
                                    <i class="fa-solid fa-store absolute right-4 text-gray-600"></i>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center items-center px-6 py-4 bg-orange-500 hover:bg-orange-600 rounded-md font-bold text-white transition-all transform hover:-translate-y-0.5 shadow-[0_0_15px_rgba(249,115,22,0.3)] text-lg uppercase tracking-wide">
                            <i class="fa-solid fa-check-circle mr-2"></i> Confirm & Pay
                        </button>
                    </form>
                    
                    <div class="mt-4 pt-4 border-t border-gray-800 text-center">
                        <p class="text-xs text-gray-500">
                            By placing this order, you agree to the <a href="#" class="text-orange-500 hover:underline">Terms & Conditions</a> and <a href="#" class="text-orange-500 hover:underline">Rental Agreement</a> (if applicable).
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
