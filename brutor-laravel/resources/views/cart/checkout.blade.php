@extends('layouts.app')
@section('title', 'Checkout - BruTor Shop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa-solid fa-credit-card"></i> Checkout</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white"><h5 class="mb-0">Order Summary</h5></div>
                <div class="card-body">
                    @if(!empty($cart['products']))
                        <h6>Products</h6>
                        <table class="table table-sm">
                            <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                            <tbody>
                                @foreach($cart['products'] as $pid => $product)
                                    <tr>
                                        <td>{{ $product['title'] }}</td>
                                        <td>{{ $product['quantity'] }}</td>
                                        <td>₱{{ number_format($product['price'], 2) }}</td>
                                        <td>₱{{ number_format($product['price'] * $product['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    @if(!empty($cart['tools']))
                        <h6>Tool Rentals</h6>
                        <table class="table table-sm">
                            <thead><tr><th>Tool</th><th>Dates</th><th>Days</th><th>Qty</th><th>Total</th></tr></thead>
                            <tbody>
                                @foreach($cart['tools'] as $tool)
                                    @php
                                        $days = (strtotime($tool['due_date']) - strtotime($tool['start_date'])) / 86400 + 1;
                                        $subtotal = $tool['rate'] * $days * $tool['quantity'];
                                    @endphp
                                    <tr>
                                        <td>{{ $tool['title'] }}</td>
                                        <td>{{ $tool['start_date'] }} to {{ $tool['due_date'] }}</td>
                                        <td>{{ (int)$days }}</td>
                                        <td>{{ $tool['quantity'] }}</td>
                                        <td>₱{{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <hr>
                    <h4 class="text-end">Grand Total: <span class="text-success">₱{{ number_format($grandTotal, 2) }}</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white"><h5 class="mb-0">Payment</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label fw-semibold">Amount Paid (₱)</label>
                            <input type="number" step="0.01" min="{{ $grandTotal }}" name="amount_paid" id="amount_paid" class="form-control" value="{{ $grandTotal }}" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="fa-solid fa-check-circle"></i> Confirm & Pay
                        </button>
                    </form>
                    <p class="text-muted small mt-2 text-center">A receipt will be emailed to you after checkout.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
