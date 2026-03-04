@extends('layouts.app')
@section('title', 'Suppliers - BruTor Shop')

@section('content')
<div class="container py-5">
    <h2>Suppliers</h2>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i> Add Supplier</a>

    <h4>Active Suppliers</h4>
    <table class="table table-bordered table-striped mb-5">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Lead Time</th><th>Website</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activeSuppliers as $row)
                <tr>
                    <td>{{ $row->supplier_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->contact_email }}</td>
                    <td>{{ $row->contact_phone }}</td>
                    <td>{{ $row->lead_time }}</td>
                    <td>@if($row->website)<a href="{{ $row->website }}" target="_blank">{{ $row->website }}</a>@endif</td>
                    <td>
                        <a href="{{ route('admin.suppliers.edit', $row->supplier_id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <form action="{{ route('admin.suppliers.destroy', $row->supplier_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No active suppliers.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h4>Deleted Suppliers</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Lead Time</th><th>Website</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deletedSuppliers as $row)
                <tr class="table-secondary">
                    <td>{{ $row->supplier_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->contact_email }}</td>
                    <td>{{ $row->contact_phone }}</td>
                    <td>{{ $row->lead_time }}</td>
                    <td>@if($row->website)<a href="{{ $row->website }}" target="_blank">{{ $row->website }}</a>@endif</td>
                    <td>
                        <a href="{{ route('admin.suppliers.restore', $row->supplier_id) }}" class="btn btn-success btn-sm" onclick="return confirm('Restore this supplier?');">
                            <i class="fa-solid fa-rotate-left"></i> Restore
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No deleted suppliers.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
