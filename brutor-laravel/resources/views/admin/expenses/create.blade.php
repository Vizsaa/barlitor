@extends('layouts.app')
@section('title', 'Add Expense - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-plus"></i> Add Expense</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.expenses.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount (₱)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
