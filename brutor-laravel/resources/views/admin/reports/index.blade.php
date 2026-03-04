@extends('layouts.app')
@section('title', 'Reports & Analytics - BruTor Shop')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Reports &amp; Analytics</h2>

    <form method="get" action="{{ route('admin.reports') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">From (Order Date)</label>
            <input type="date" name="from" value="{{ $dateFrom }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">To (Order Date)</label>
            <input type="date" name="to" value="{{ $dateTo }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">View</label>
            <div class="btn-group w-100" role="group">
                <button type="submit" name="type" value="all" class="btn btn-outline-primary {{ $viewType === 'all' ? 'active' : '' }}">All</button>
                <button type="submit" name="type" value="materials" class="btn btn-outline-primary {{ $viewType === 'materials' ? 'active' : '' }}">Materials</button>
                <button type="submit" name="type" value="rentals" class="btn btn-outline-primary {{ $viewType === 'rentals' ? 'active' : '' }}">Rentals</button>
            </div>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary mt-4 w-100">Apply</button>
            <a href="{{ route('admin.reports', ['from' => $dateFrom, 'to' => $dateTo, 'type' => $viewType, 'export' => 1]) }}"
               class="btn btn-success mt-4 w-100">
                Export CSV
            </a>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Materials Income</div>
                <div class="card-body"><h5 class="card-title">₱{{ number_format($materialsTotal, 2) }}</h5></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Rentals Income</div>
                <div class="card-body"><h5 class="card-title">₱{{ number_format($rentalsTotal, 2) }}</h5></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Expenses</div>
                <div class="card-body"><h5 class="card-title text-danger">₱{{ number_format($expenseTotal, 2) }}</h5></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-header">Net Income</div>
                <div class="card-body">
                    <h5 class="card-title {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                        ₱{{ number_format($netIncome, 2) }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    @if($viewType === 'all' || $viewType === 'materials')
        <h4 class="mt-4">Materials Sold</h4>
        @if($materialsRows->isEmpty())
            <p class="text-muted">No materials sold in the selected range.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr><th>Transaction #</th><th>Order Date</th><th>Item</th><th>Quantity</th><th>Rate</th><th>Line Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($materialsRows as $row)
                            <tr>
                                <td>{{ $row->orderinfo_id }}</td>
                                <td>{{ $row->date_placed }}</td>
                                <td>{{ $row->title }}</td>
                                <td>{{ (int)$row->quantity }}</td>
                                <td>₱{{ number_format($row->rate_charged, 2) }}</td>
                                <td>₱{{ number_format($row->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

    @if($viewType === 'all' || $viewType === 'rentals')
        <h4 class="mt-4">Tool Rentals</h4>
        @if($rentalsRows->isEmpty())
            <p class="text-muted">No rentals in the selected range.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr><th>Transaction #</th><th>Order Date</th><th>Item</th><th>Start Date</th><th>Due Date</th><th>Quantity</th><th>Total Charged</th></tr>
                    </thead>
                    <tbody>
                        @foreach($rentalsRows as $row)
                            <tr>
                                <td>{{ $row->orderinfo_id }}</td>
                                <td>{{ $row->date_placed }}</td>
                                <td>{{ $row->title }}</td>
                                <td>{{ $row->start_date }}</td>
                                <td>{{ $row->due_date }}</td>
                                <td>{{ (int)$row->quantity }}</td>
                                <td>₱{{ number_format($row->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
@endsection
