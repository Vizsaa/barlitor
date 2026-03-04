@extends('layouts.app')
@section('title', 'Edit Supplier - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-pen"></i> Edit Supplier</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.suppliers.update', $supplier->supplier_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $supplier->contact_email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $supplier->contact_phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lead Time</label>
                            <input type="text" name="lead_time" class="form-control" value="{{ old('lead_time', $supplier->lead_time) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Website</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website', $supplier->website) }}">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
