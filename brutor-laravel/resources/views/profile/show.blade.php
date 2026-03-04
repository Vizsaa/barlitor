@extends('layouts.app')
@section('title', $user->name . ' - Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa-solid fa-user"></i> {{ $isOwnProfile ? 'My Profile' : $user->name . "'s Profile" }}</h4>
                    @if($isOwnProfile && !$isEditing)
                        <a href="{{ route('profile.show', ['id' => $user->id, 'edit' => 1]) }}" class="btn btn-sm btn-light">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        @php
                            $avatarPath = $user->avatar ? asset($user->avatar) : 'https://bootdey.com/img/Content/avatar/avatar1.png';
                        @endphp
                        <img src="{{ $avatarPath }}" alt="Avatar" class="rounded-circle border" width="120" height="120" style="object-fit:cover;" id="avatarPreview">

                        @if($isOwnProfile || $isAdmin)
                            <div class="mt-2">
                                <input type="file" id="avatarInput" accept="image/*" class="d-none">
                                <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('avatarInput').click()">
                                    <i class="fa-solid fa-camera"></i> Change Avatar
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($isEditing)
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Title</label>
                                    <select name="title" class="form-select">
                                        <option value="">--</option>
                                        @foreach(['Mr', 'Mrs', 'Ms', 'Dr'] as $t)
                                            <option value="{{ $t }}" {{ old('title', $user->title) === $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">First Name</label>
                                    <input type="text" name="fname" class="form-control" value="{{ old('fname', $user->fname) }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Last Name</label>
                                    <input type="text" name="lname" class="form-control" value="{{ old('lname', $user->lname) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <input type="text" name="addressline" class="form-control" value="{{ old('addressline', $user->addressline) }}">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Town</label>
                                    <input type="text" name="town" class="form-control" value="{{ old('town', $user->town) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Zipcode</label>
                                    <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode', $user->zipcode) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('profile.show', $user->id) }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                            </div>
                        </form>
                    @else
                        <table class="table">
                            <tr><th>Name</th><td>{{ $user->title ? $user->title . ' ' : '' }}{{ $user->fname }} {{ $user->lname }}</td></tr>
                            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                            <tr><th>Role</th><td><span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">{{ ucfirst($user->role) }}</span></td></tr>
                            <tr><th>Address</th><td>{{ $user->addressline ?: 'N/A' }}</td></tr>
                            <tr><th>Town</th><td>{{ $user->town ?: 'N/A' }}</td></tr>
                            <tr><th>Zipcode</th><td>{{ $user->zipcode ?: 'N/A' }}</td></tr>
                            <tr><th>Phone</th><td>{{ $user->phone ?: 'N/A' }}</td></tr>
                            <tr><th>Joined</th><td>{{ $user->created_at }}</td></tr>
                        </table>

                        @if($isOwnProfile)
                            <a href="{{ route('profile.changePassword') }}" class="btn btn-outline-warning">
                                <i class="fa-solid fa-key"></i> Change Password
                            </a>
                        @endif
                    @endif
                </div>
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
