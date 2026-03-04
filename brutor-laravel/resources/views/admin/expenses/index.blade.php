@extends('layouts.app')
@section('title', 'Expenses - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Expenses</h2>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add Expense</a>
    </div>

    @if($expenses->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Title</th><th>Amount</th><th>Date</th><th>Notes</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_id }}</td>
                            <td>{{ $expense->title }}</td>
                            <td class="text-danger">₱{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->expense_date }}</td>
                            <td>{{ $expense->notes ?: '-' }}</td>
                            <td>
                                <a href="{{ route('admin.expenses.edit', $expense->expense_id) }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen"></i> Edit</a>
                                <form action="{{ route('admin.expenses.destroy', $expense->expense_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">No expenses recorded yet.</div>
    @endif
</div>
@endsection
