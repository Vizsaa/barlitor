@extends('layouts.app')
@section('title', 'Manage Users - BruTor Shop')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Users</h3>
    </div>

    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">{{ $user->role }}</span></td>
                            <td>{{ $user->created_at }}</td>
                            <td class="text-end">
                                <a href="{{ route('profile.show', $user->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">No users found.</div>
    @endif
</div>
@endsection
