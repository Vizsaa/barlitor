@extends('layouts.admin')
@section('title', 'Manage Users - BruTor Shop Admin')
@section('title_header', 'User Management')

@section('content')
<div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-[#111111]">
        <div>
            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Registered Users</h3>
            <p class="text-xs text-gray-400 mt-1">Total system users: {{ $users->count() }}</p>
        </div>
        <!-- Add User button could go here if implemented -->
    </div>

    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">ID</th>
                        <th scope="col" class="px-6 py-4 font-semibold">User Details</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Role</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Registered</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @foreach($users as $user)
                        <tr class="hover:bg-[#111111] transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400 w-16">
                                #{{ $user->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @php
                                        $avatar = $user->avatar ? asset($user->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                                    @endphp
                                    <img class="h-10 w-10 rounded-full border border-gray-700 object-cover mr-3" src="{{ $avatar }}" alt="">
                                    <div>
                                        <div class="text-white font-medium">{{ $user->name }}</div>
                                        <div class="text-gray-400 text-xs mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-orange-900/40 text-orange-400 border border-orange-800/50 tracking-wide uppercase">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-gray-800 text-gray-300 border border-gray-700 tracking-wide uppercase">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('profile.show', $user->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs font-semibold transition-colors border border-gray-600 shadow-sm">
                                    <i class="fa-solid fa-eye mr-1.5"></i> View Profile
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center justify-center px-3 py-1.5 bg-red-900/30 hover:bg-red-900/50 border border-red-800/50 text-red-400 hover:text-red-300 rounded text-xs font-semibold transition-colors shadow-sm" type="submit">
                                            <i class="fa-solid fa-trash mr-1.5"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-8 text-center text-gray-400">
            <i class="fa-solid fa-users-slash text-4xl mb-3 text-gray-600"></i>
            <h4 class="text-white font-bold mb-1">No Users Found</h4>
            <p>There are currently no registered users in the system.</p>
        </div>
    @endif
</div>
@endsection
