@extends('layouts.app')
@section('title', 'Manage Users - BruTor Shop Admin')
@section('title_header', 'User Management')

@section('content')
<div class="bg-[#1a1a1a] rounded-xl border border-gray-800 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-[#111111]">
        <div>
            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Registered Users</h3>
            <p class="text-xs text-gray-400 mt-1">Total system users: {{ $users->count() }}</p>
        </div>
    </div>

    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table id="usersTable" class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase tracking-wider bg-gray-800/50 border-b border-gray-800">
                    <tr>
                        <th class="px-6 py-4 font-semibold">#</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Registered</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/60">
                    @foreach($users as $index => $user)
                        <tr class="hover:bg-[#111111] transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-400 w-16">
                                {{ $index + 1 }}
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
                            <td class="px-6 py-4">
                                @php $isActive = ($user->status ?? 'active') === 'active'; @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold tracking-wide uppercase
                                    {{ $isActive ? 'bg-green-900/40 text-green-300 border border-green-700' : 'bg-red-900/40 text-red-300 border border-red-700' }}">
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-y-1">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('profile.show', $user->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white rounded text-xs font-semibold transition-colors border border-gray-600 shadow-sm">
                                        <i class="fa-solid fa-eye mr-1.5"></i> View
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
                                </div>

                                @if(auth()->id() !== $user->id)
                                    <div class="mt-2 flex flex-col sm:flex-row justify-end gap-2">
                                        <!-- Role inline control -->
                                        <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="flex items-center gap-1 text-xs">
                                            @csrf
                                            <select name="role"
                                                    class="bg-[#111111] border border-gray-700 text-gray-100 rounded-md px-2 py-1 focus:ring-orange-500 focus:border-orange-500">
                                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 rounded-md bg-gray-800 hover:bg-gray-700 text-gray-100 border border-gray-700">
                                                Set
                                            </button>
                                        </form>

                                        <!-- Status inline control -->
                                        <form method="POST" action="{{ route('admin.users.updateStatus', $user->id) }}" class="text-xs">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ $isActive ? 'inactive' : 'active' }}">
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 rounded-md border transition-colors
                                                        {{ $isActive ? 'bg-red-900/40 hover:bg-red-800 text-red-200 border-red-700' : 'bg-green-900/40 hover:bg-green-800 text-green-200 border-green-700' }}">
                                                <i class="fa-solid {{ $isActive ? 'fa-user-slash' : 'fa-user-check' }} mr-1.5"></i>
                                                {{ $isActive ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <style>
        #usersTable_wrapper {
            color: #e5e7eb;
        }
        #usersTable_wrapper .dataTables_length select,
        #usersTable_wrapper .dataTables_filter input {
            background-color: #111111;
            border-color: #374151;
            color: #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
        }
        #usersTable_wrapper .dataTables_paginate .paginate_button {
            color: #9ca3af !important;
        }
        #usersTable_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #f97316 !important;
            color: #ffffff !important;
            border-color: #ea580c !important;
        }
        #usersTable_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #374151 !important;
            color: #ffffff !important;
        }
        #usersTable tbody tr:hover {
            background-color: #111111;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && $('#usersTable').length) {
                $('#usersTable').DataTable({
                    pageLength: 15,
                    order: [[4, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 1, 5] }
                    ],
                    language: {
                        search: "Search users:",
                        lengthMenu: "Show _MENU_ users per page",
                    }
                });
            }
        });
    </script>
@endpush
