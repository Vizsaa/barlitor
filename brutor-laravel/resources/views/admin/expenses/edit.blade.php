@extends('layouts.app')
@section('title', 'Edit Expense - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-pen"></i> Edit Expense</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.expenses.update', $expense->expense_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $expense->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount (₱)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
