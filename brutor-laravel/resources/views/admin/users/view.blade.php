@extends('layouts.app')
@section('title', $user->name . ' - Admin View')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-user"></i> {{ $user->name }}</h4>
                </div>
                <div class="card-body p-4">
                    @php
                        $avatarPath = $user->avatar ? asset($user->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                    @endphp
                    <div class="text-center mb-4">
                        <img src="{{ $avatarPath }}" alt="Avatar" class="rounded-circle border" width="100" height="100" style="object-fit:cover;">
                    </div>
                    <table class="table">
                        <tr><th>ID</th><td>{{ $user->id }}</td></tr>
                        <tr><th>Name</th><td>{{ $user->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                        <tr><th>Role</th><td><span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">{{ ucfirst($user->role) }}</span></td></tr>
                        <tr><th>Phone</th><td>{{ $user->phone ?: 'N/A' }}</td></tr>
                        <tr><th>Address</th><td>{{ $user->addressline ?: 'N/A' }}</td></tr>
                        <tr><th>Town</th><td>{{ $user->town ?: 'N/A' }}</td></tr>
                        <tr><th>Zipcode</th><td>{{ $user->zipcode ?: 'N/A' }}</td></tr>
                        <tr><th>Joined</th><td>{{ $user->created_at }}</td></tr>
                    </table>
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Users</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
