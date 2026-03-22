@extends('layouts.app')
@section('title', 'Cart - BarliTor Shop')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center space-x-3 mb-8 pb-4 border-b border-gray-800">
        <i class="fa-solid fa-cart-shopping text-3xl text-orange-500"></i>
        <h1 class="text-3xl font-extrabold text-white tracking-tight uppercase">Shopping Cart</h1>
    </div>

    @if(empty($cart['products']) && empty($cart['tools']))
        <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 p-12 text-center shadow-lg">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#111111] border border-gray-700 mb-6">
                <i class="fa-solid fa-cart-arrow-down text-3xl text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Your cart is empty</h3>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">Looks like you haven't added any products or tools to your cart yet.</p>
            <a href="{{ route('items.index') }}" class="inline-flex justify-center items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 rounded-md font-bold text-white transition-colors shadow-lg">
                <i class="fa-solid fa-bag-shopping mr-2"></i> Browse Items
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Cart Items List -->
            <div class="lg:w-2/3 space-y-8">
                
                {{-- Products Table --}}
                @if(!empty($cart['products']))
                    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden">
                        <div class="bg-gray-800/50 px-6 py-4 border-b border-gray-800">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-box mr-2 text-gray-400"></i> Retail Products
                            </h2>
                        </div>
                        
                        <form method="POST" action="{{ route('cart.update') }}" class="p-6">
                            @csrf
                            <div class="overflow-x-auto text-left">
                                <table class="w-full text-sm text-left whitespace-nowrap">
                                    <thead class="text-xs text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-800">
                                        <tr>
                                            <th scope="col" class="pb-3 px-2">Item</th>
                                            <th scope="col" class="pb-3 px-2 text-right">Price</th>
                                            <th scope="col" class="pb-3 px-2 text-center w-32">Qty</th>
                                            <th scope="col" class="pb-3 px-2 text-right">Subtotal</th>
                                            <th scope="col" class="pb-3 px-2 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-800/80">
                                        @foreach($cart['products'] as $pid => $product)
                                            <tr class="hover:bg-[#111111] transition-colors">
                                                <td class="py-4 px-2 font-medium text-white whitespace-normal min-w-[200px]">
                                                    {{ $product['title'] }}
                                                </td>
                                                <td class="py-4 px-2 text-right text-gray-300">
                                                    ₱{{ number_format($product['price'], 2) }}
                                                </td>
                                                <td class="py-4 px-2">
                                                    <input type="number" name="quantities[{{ $pid }}]" value="{{ $product['quantity'] }}" min="1" 
                                                        class="bg-[#111111] border border-gray-700 text-white text-sm rounded focus:ring-orange-500 focus:border-orange-500 block w-20 mx-auto px-2 py-1.5 text-center">
                                                </td>
                                                <td class="py-4 px-2 text-right font-bold text-white">
                                                    ₱{{ number_format($product['price'] * $product['quantity'], 2) }}
                                                </td>
                                                <td class="py-4 px-2 text-center">
                                                    <a href="{{ route('cart.removeProduct', $pid) }}" class="text-red-500 hover:text-red-400 p-2 transition-colors" title="Remove Item">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-800">
                                <button type="submit" class="text-sm font-semibold text-gray-300 hover:text-white bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded transition-colors flex items-center">
                                    <i class="fa-solid fa-sync mr-2 flex-shrink-0"></i> Update Qty
                                </button>
                                <div class="text-right">
                                    <span class="text-sm text-gray-400 uppercase tracking-wider mr-3">Subtotal</span>
                                    <span class="text-xl font-bold text-orange-500">₱{{ number_format($productTotal, 2) }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Tools Table --}}
                @if(!empty($cart['tools']))
                    <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden">
                        <div class="bg-gray-800/50 px-6 py-4 border-b border-gray-800">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fa-solid fa-wrench mr-2 text-gray-400"></i> Tool Rentals
                            </h2>
                        </div>
                        
                        <div class="p-6 overflow-x-auto">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="text-xs text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-800">
                                    <tr>
                                        <th scope="col" class="pb-3 px-2">Tool</th>
                                        <th scope="col" class="pb-3 px-2 text-right">Rate/Day</th>
                                        <th scope="col" class="pb-3 px-2 text-center">Period</th>
                                        <th scope="col" class="pb-3 px-2 text-center">Qty</th>
                                        <th scope="col" class="pb-3 px-2 text-right">Subtotal</th>
                                        <th scope="col" class="pb-3 px-2 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/80">
                                    @php $toolTotal = 0; @endphp
                                    @foreach($cart['tools'] as $index => $tool)
                                        @php
                                            $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                                            $subtotal = $tool['rate'] * $days * $tool['quantity'];
                                            $toolTotal += $subtotal;
                                        @endphp
                                        <tr class="hover:bg-[#111111] transition-colors">
                                            <td class="py-4 px-2 font-medium text-white whitespace-normal min-w-[180px]">
                                                {{ $tool['title'] }}
                                            </td>
                                            <td class="py-4 px-2 text-right text-gray-300">
                                                ₱{{ number_format($tool['rate'], 2) }}
                                            </td>
                                            <td class="py-4 px-2 text-center">
                                                <div class="flex flex-col text-xs text-gray-400">
                                                    <span>{{ \Carbon\Carbon::parse($tool['start_date'])->format('M d') }} - {{ \Carbon\Carbon::parse($tool['due_date'])->format('M d') }}</span>
                                                    <span class="text-orange-500 font-semibold mt-0.5">{{ (int)$days }} day{{ $days > 1 ? 's' : '' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-2 text-center text-white font-medium">
                                                {{ $tool['quantity'] }}
                                            </td>
                                            <td class="py-4 px-2 text-right font-bold text-white">
                                                ₱{{ number_format($subtotal, 2) }}
                                            </td>
                                            <td class="py-4 px-2 text-center">
                                                <a href="{{ route('cart.removeTool', $index) }}" class="text-red-500 hover:text-red-400 p-2 transition-colors" title="Remove Rental">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="mt-4 flex justify-end pt-4 border-t border-gray-800">
                                <div class="text-right">
                                    <span class="text-sm text-gray-400 uppercase tracking-wider mr-3">Subtotal</span>
                                    <span class="text-xl font-bold text-orange-500">₱{{ number_format($toolTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:w-1/3">
                <div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-wider border-b border-gray-800 pb-4">Order Summary</h2>
                    
                    @php
                        $grandTotal = $productTotal;
                        if (!empty($cart['tools'])) {
                            foreach ($cart['tools'] as $tool) {
                                $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                                $grandTotal += $tool['rate'] * $days * $tool['quantity'];
                            }
                        }
                    @endphp

                    <div class="space-y-3 mb-6">
                        @if(!empty($cart['products']))
                            <div class="flex justify-between text-gray-300">
                                <span>Products ({{ array_sum(array_column($cart['products'], 'quantity')) }})</span>
                                <span>₱{{ number_format($productTotal, 2) }}</span>
                            </div>
                        @endif
                        
                        @if(!empty($cart['tools']))
                            <div class="flex justify-between text-gray-300">
                                @php
                                    $toolCount = array_sum(array_column($cart['tools'], 'quantity'));
                                @endphp
                                <span>Tool Rentals ({{ $toolCount }})</span>
                                <span>
                                    @php
                                        $tTotal = 0;
                                        foreach ($cart['tools'] as $tool) {
                                            $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                                            $tTotal += $tool['rate'] * $days * $tool['quantity'];
                                        }
                                        echo '₱' . number_format($tTotal, 2);
                                    @endphp
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-800 pt-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-white">Estimated Total</span>
                            <span class="text-2xl font-black text-orange-500">₱{{ number_format($grandTotal, 2) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-right">Taxes and shipping calculated at checkout.</p>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full flex justify-center items-center px-6 py-4 bg-orange-500 hover:bg-orange-600 rounded-md font-bold text-white transition-colors shadow-[0_0_15px_rgba(249,115,22,0.3)] text-lg uppercase tracking-wide">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('items.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    @endif
</div>
@endsection
