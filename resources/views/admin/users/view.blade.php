@extends('layouts.app')
@section('title', $user->name . ' - Admin View')
@section('title_header', 'User Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: avatar and basic info -->
        <div class="space-y-6">
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 flex flex-col items-center">
                @php
                    $avatarPath = $user->avatar ? asset($user->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                @endphp
                <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-orange-500/60 shadow mb-4">
                    <img src="{{ $avatarPath }}" alt="Avatar" class="w-full h-full object-cover">
                </div>

                <div class="text-center mb-3">
                    <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>
                </div>

                <div class="flex flex-wrap justify-center gap-2 mb-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $user->role === 'admin' ? 'bg-orange-900/50 text-orange-300 border border-orange-700' : 'bg-gray-800 text-gray-300 border border-gray-700' }}">
                        <i class="fa-solid {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} mr-1.5"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    @php $isActive = ($user->status ?? 'active') === 'active'; @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $isActive ? 'bg-green-900/40 text-green-300 border border-green-700' : 'bg-red-900/40 text-red-300 border border-red-700' }}">
                        <i class="fa-solid {{ $isActive ? 'fa-circle-check' : 'fa-circle-xmark' }} mr-1.5"></i>
                        {{ $isActive ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <p class="text-xs text-gray-500">
                    Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Right column: details and admin controls -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider">User Details</h3>
                <dl class="divide-y divide-gray-800 text-sm">
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">User ID</dt>
                        <dd class="text-white text-right">#{{ $user->id }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Name</dt>
                        <dd class="text-white text-right">{{ $user->name }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Email</dt>
                        <dd class="text-white text-right">{{ $user->email }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Phone</dt>
                        <dd class="text-white text-right">{{ $user->phone ?: 'N/A' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Address</dt>
                        <dd class="text-white text-right">{{ $user->addressline ?: 'N/A' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Town</dt>
                        <dd class="text-white text-right">{{ $user->town ?: 'N/A' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Zipcode</dt>
                        <dd class="text-white text-right">{{ $user->zipcode ?: 'N/A' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-400">Joined</dt>
                        <dd class="text-white text-right">{{ $user->created_at }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-[#1a1a1a] border border-orange-900/60 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-orange-400 mb-4 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i> Admin Controls
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Role control -->
                    <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="space-y-2">
                        @csrf
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Role</label>
                        <div class="flex gap-2">
                            <select name="role"
                                    class="flex-1 bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 px-3 py-2">
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-2 rounded-md bg-gray-800 hover:bg-gray-700 text-gray-100 border border-gray-700 text-xs font-semibold">
                                Set
                            </button>
                        </div>
                    </form>

                    <!-- Status control -->
                    <form method="POST" action="{{ route('admin.users.updateStatus', $user->id) }}" class="space-y-2">
                        @csrf
                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider">Status</label>
                        <input type="hidden" name="status" value="{{ $isActive ? 'inactive' : 'active' }}">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-md text-xs font-semibold border transition-colors
                                    {{ $isActive ? 'bg-red-900/40 hover:bg-red-800 text-red-200 border-red-700' : 'bg-green-900/40 hover:bg-green-800 text-green-200 border-green-700' }}">
                            <i class="fa-solid {{ $isActive ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i>
                            {{ $isActive ? 'Deactivate' : 'Activate' }} Account
                        </button>
                    </form>
                </div>

                @if(auth()->id() !== $user->id)
                    <div class="mt-6 pt-4 border-t border-gray-800 flex items-center justify-between">
                        <a href="{{ route('admin.users') }}"
                           class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Back to Users
                        </a>
                        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                              onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 rounded-md bg-red-900/50 hover:bg-red-800 text-red-200 border border-red-700 text-sm font-semibold">
                                <i class="fa-solid fa-trash mr-2"></i>
                                Delete User
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-6 pt-4 border-t border-gray-800">
                        <a href="{{ route('admin.users') }}"
                           class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 text-sm font-semibold transition-colors">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Back to Users
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
