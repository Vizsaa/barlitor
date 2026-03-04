@extends('layouts.app')
@section('title', 'My Orders - BruTor Shop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa-solid fa-box"></i> My Orders</h2>

    <form method="GET" action="{{ route('orders.mine') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">From</label>
            <input type="date" name="from" value="{{ $dateFrom }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">To</label>
            <input type="date" name="to" value="{{ $dateTo }}" class="form-control">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="{{ route('orders.mine') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    @if($orders->isEmpty())
        <div class="alert alert-info">No orders found.</div>
    @else
        <div class="accordion" id="ordersAccordion">
            @foreach($orders as $order)
                @php
                    $orderTotal = 0;
                    foreach ($order->productsSold as $ps) {
                        $orderTotal += $ps->quantity * $ps->rate_charged;
                    }
                    foreach ($order->rentals as $r) {
                        $orderTotal += $r->rate_charged;
                    }
                @endphp
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#order{{ $order->orderinfo_id }}">
                            <strong>Order #{{ $order->orderinfo_id }}</strong>
                            <span class="ms-3 text-muted">{{ $order->date_placed }}</span>
                            <span class="ms-3 badge bg-info">{{ $order->status }}</span>
                            <span class="ms-auto fw-bold text-success">₱{{ number_format($orderTotal, 2) }}</span>
                        </button>
                    </h2>
                    <div id="order{{ $order->orderinfo_id }}" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body">
                            @if($order->productsSold->count() > 0)
                                <h6>Products</h6>
                                <table class="table table-sm table-bordered">
                                    <thead><tr><th>Item</th><th>Qty</th><th>Rate</th><th>Total</th></tr></thead>
                                    <tbody>
                                        @foreach($order->productsSold as $ps)
                                            <tr>
                                                <td>{{ $ps->item->title ?? 'N/A' }}</td>
                                                <td>{{ $ps->quantity }}</td>
                                                <td>₱{{ number_format($ps->rate_charged, 2) }}</td>
                                                <td>₱{{ number_format($ps->quantity * $ps->rate_charged, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if($order->rentals->count() > 0)
                                <h6>Tool Rentals</h6>
                                <table class="table table-sm table-bordered">
                                    <thead><tr><th>Tool</th><th>Start</th><th>Due</th><th>Qty</th><th>Total</th></tr></thead>
                                    <tbody>
                                        @foreach($order->rentals as $r)
                                            <tr>
                                                <td>{{ $r->item->title ?? 'N/A' }}</td>
                                                <td>{{ $r->start_date }}</td>
                                                <td>{{ $r->due_date }}</td>
                                                <td>{{ $r->quantity }}</td>
                                                <td>₱{{ number_format($r->rate_charged, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if($order->payment)
                                <p class="mt-2"><strong>Payment:</strong> ₱{{ number_format($order->payment->amount_paid, 2) }} on {{ $order->payment->paid_on }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
