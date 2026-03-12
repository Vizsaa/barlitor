@extends('layouts.app')
@section('title', $user->name . ' - Profile')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-user text-orange-500"></i>
                <span>{{ $isOwnProfile ? 'My Profile' : $user->name . '\'s Profile' }}</span>
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Manage your personal information and account settings.
            </p>
        </div>
        @if($isOwnProfile && !$isEditing)
            <a href="{{ route('profile.show', ['id' => $user->id, 'edit' => 1]) }}"
               class="hidden sm:inline-flex items-center px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold shadow">
                <i class="fa-solid fa-pen mr-2"></i> Edit Profile
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: Avatar and summary -->
        <div class="space-y-6">
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 flex flex-col items-center">
                @php
                    $avatarPath = $user->avatar ? asset($user->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                @endphp
                <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-orange-500/60 shadow mb-4">
                    <img src="{{ $avatarPath }}" alt="Avatar" id="avatarPreview" class="w-full h-full object-cover">
                </div>

                <div class="text-center mb-3">
                    <h2 class="text-xl font-bold text-white">
                        {{ $user->title ? $user->title . ' ' : '' }}{{ $user->fname ?: $user->name }} {{ $user->lname }}
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>
                </div>

                <div class="flex flex-wrap justify-center gap-2 mb-3">
                    <!-- Role badge -->
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $user->role === 'admin' ? 'bg-orange-900/50 text-orange-300 border border-orange-700' : 'bg-gray-800 text-gray-300 border border-gray-700' }}">
                        <i class="fa-solid {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} mr-1.5"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    <!-- Status badge (fallback active if null) -->
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ ($user->status ?? 'active') === 'active' ? 'bg-green-900/40 text-green-300 border border-green-700' : 'bg-red-900/40 text-red-300 border border-red-700' }}">
                        <i class="fa-solid {{ ($user->status ?? 'active') === 'active' ? 'fa-circle-check' : 'fa-circle-xmark' }} mr-1.5"></i>
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </div>

                <p class="text-xs text-gray-500 mb-4">
                    Member since {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                </p>

                @if($isOwnProfile || $isAdmin)
                    <input type="file" id="avatarInput" accept="image/*" class="hidden">
                    <button type="button"
                            onclick="document.getElementById('avatarInput').click()"
                            class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 text-sm font-semibold transition-colors">
                        <i class="fa-solid fa-camera mr-2 text-orange-400"></i>
                        Change Photo
                    </button>
                @endif
            </div>

            @if($isOwnProfile)
                <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-white mb-3 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-key text-orange-500"></i> Security
                    </h3>
                    <p class="text-xs text-gray-400 mb-3">
                        Change your password regularly to keep your account secure.
                    </p>
                    <a href="{{ route('profile.changePassword') }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-800 hover:bg-gray-700 text-xs font-semibold text-gray-100 border border-gray-700 transition-colors">
                        <i class="fa-solid fa-key mr-2"></i>
                        Change Password
                    </a>
                </div>
            @endif
        </div>

        <!-- Right column: details / edit -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6">
                @if($isEditing)
                    <h3 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider">Edit Profile</h3>
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Title</label>
                                <select name="title"
                                        class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                                    <option value="">--</option>
                                    @foreach(['Mr', 'Mrs', 'Ms', 'Dr'] as $t)
                                        <option value="{{ $t }}" {{ old('title', $user->title) === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">First Name</label>
                                    <input type="text" name="fname" value="{{ old('fname', $user->fname) }}" required
                                           class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Last Name</label>
                                    <input type="text" name="lname" value="{{ old('lname', $user->lname) }}" required
                                           class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="bg-[#111111] border border-gray-800 text-gray-400 text-sm rounded-md block w-full px-3 py-2 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Address</label>
                            <input type="text" name="addressline" value="{{ old('addressline', $user->addressline) }}"
                                   class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Town</label>
                                <input type="text" name="town" value="{{ old('town', $user->town) }}"
                                       class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Zipcode</label>
                                <input type="text" name="zipcode" value="{{ old('zipcode', $user->zipcode) }}"
                                       class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2">
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-800 mt-4">
                            <a href="{{ route('profile.show', $user->id) }}"
                               class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 text-sm font-semibold transition-colors">
                                <i class="fa-solid fa-arrow-left mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-colors">
                                <i class="fa-solid fa-check mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                @else
                    <h3 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider">Profile Details</h3>
                    <dl class="divide-y divide-gray-800 text-sm">
                        <div class="py-3 flex justify-between">
                            <dt class="text-gray-400">Full Name</dt>
                            <dd class="text-white text-right">
                                {{ $user->title ? $user->title . ' ' : '' }}{{ $user->fname }} {{ $user->lname }}
                            </dd>
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

                    <div class="mt-6 flex flex-wrap gap-3">
                        @if($isOwnProfile)
                            <a href="{{ route('profile.show', ['id' => $user->id, 'edit' => 1]) }}"
                               class="inline-flex items-center px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold shadow">
                                <i class="fa-solid fa-pen mr-2"></i> Edit Profile
                            </a>
                            <a href="{{ route('profile.changePassword') }}"
                               class="inline-flex items-center px-4 py-2 rounded-md bg-gray-800 hover:bg-gray-700 text-gray-100 text-sm font-semibold border border-gray-700">
                                <i class="fa-solid fa-key mr-2"></i> Change Password
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($isOwnProfile || $isAdmin)
@push('scripts')
<script>
document.getElementById('avatarInput')?.addEventListener('change', function() {
    let formData = new FormData();
    formData.append('avatar', this.files[0]);
    formData.append('user_id', '{{ $user->id }}');
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("profile.avatar") }}', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avatarPreview').src = '/' + data.path;
                location.reload();
            } else {
                alert(data.message || 'Upload failed');
            }
        })
        .catch(() => alert('Upload failed'));
});
</script>
@endpush
@endif
@endsection
