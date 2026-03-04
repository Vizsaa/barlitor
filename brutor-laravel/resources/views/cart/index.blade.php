@extends('layouts.app')
@section('title', 'Cart - BruTor Shop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping"></i> Shopping Cart</h2>

    @if(empty($cart['products']) && empty($cart['tools']))
        <div class="alert alert-info text-center">Your cart is empty. <a href="{{ route('items.index') }}">Browse items</a></div>
    @else

        {{-- Products Table --}}
        @if(!empty($cart['products']))
            <h4>Products</h4>
            <form method="POST" action="{{ route('cart.update') }}">
                @csrf
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th style="width:120px">Qty</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart['products'] as $pid => $product)
                                <tr>
                                    <td>{{ $product['title'] }}</td>
                                    <td>₱{{ number_format($product['price'], 2) }}</td>
                                    <td>
                                        <input type="number" name="quantities[{{ $pid }}]" value="{{ $product['quantity'] }}" min="1" class="form-control form-control-sm">
                                    </td>
                                    <td>₱{{ number_format($product['price'] * $product['quantity'], 2) }}</td>
                                    <td>
                                        <a href="{{ route('cart.removeProduct', $pid) }}" class="btn btn-sm btn-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Products Total:</td>
                                <td colspan="2" class="fw-bold text-success">₱{{ number_format($productTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button type="submit" class="btn btn-outline-primary mb-4"><i class="fa-solid fa-sync"></i> Update Quantities</button>
            </form>
        @endif

        {{-- Tools Table --}}
        @if(!empty($cart['tools']))
            <h4>Tool Rentals</h4>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tool</th>
                            <th>Rate/Day</th>
                            <th>Start</th>
                            <th>Due</th>
                            <th>Days</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $toolTotal = 0; @endphp
                        @foreach($cart['tools'] as $index => $tool)
                            @php
                                $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                                $subtotal = $tool['rate'] * $days * $tool['quantity'];
                                $toolTotal += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $tool['title'] }}</td>
                                <td>₱{{ number_format($tool['rate'], 2) }}</td>
                                <td>{{ $tool['start_date'] }}</td>
                                <td>{{ $tool['due_date'] }}</td>
                                <td>{{ (int)$days }}</td>
                                <td>{{ $tool['quantity'] }}</td>
                                <td>₱{{ number_format($subtotal, 2) }}</td>
                                <td>
                                    <a href="{{ route('cart.removeTool', $index) }}" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end fw-bold">Rentals Total:</td>
                            <td colspan="2" class="fw-bold text-success">₱{{ number_format($toolTotal, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        @php
            $grandTotal = $productTotal;
            if (!empty($cart['tools'])) {
                foreach ($cart['tools'] as $tool) {
                    $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                    $grandTotal += $tool['rate'] * $days * $tool['quantity'];
                }
            }
        @endphp

        <div class="d-flex justify-content-between align-items-center border-top pt-4">
            <h4 class="mb-0">Grand Total: <span class="text-success">₱{{ number_format($grandTotal, 2) }}</span></h4>
            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
                <i class="fa-solid fa-credit-card"></i> Proceed to Checkout
            </a>
        </div>
    @endif
</div>
@endsection
