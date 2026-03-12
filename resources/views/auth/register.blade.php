@extends('layouts.app')
@section('title', 'Register - BruTor Shop')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-10 px-4">
    <div class="max-w-md w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl shadow-xl px-6 py-7">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center justify-center gap-2">
                <i class="fa-solid fa-user-plus text-orange-500"></i>
                <span>Create Account</span>
            </h1>
            <p class="text-sm text-gray-400 mt-1">Join BruTor Shop and start shopping or renting tools.</p>
        </div>

        <form id="registerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Avatar Upload -->
            <div class="flex flex-col items-center mb-2">
                @php
                    $defaultAvatar = 'https://bootdey.com/img/Content/avatar/avatar1.png';
                @endphp
                <div class="w-28 h-28 rounded-full border-2 border-orange-500/80 overflow-hidden mb-2">
                    <img src="{{ $defaultAvatar }}" alt="Avatar" id="regAvatarPreview" class="w-full h-full object-cover">
                </div>
                <input type="file" name="avatar" id="regAvatarInput" accept="image/*" class="hidden">
                <button type="button"
                        class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-700 text-xs font-semibold text-gray-200 hover:bg-gray-800 transition-colors"
                        onclick="document.getElementById('regAvatarInput').click()">
                    <i class="fa-solid fa-camera mr-2 text-orange-400"></i>
                    Upload Photo
                </button>
                <p class="mt-1 text-[11px] text-gray-500">Optional. JPG/PNG up to 5MB.</p>
                @error('avatar')
                    <p class="mt-1 text-xs text-red-400 flex items-center justify-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required minlength="2" maxlength="255" autofocus
                    class="bg-[#111111] border {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-gray-100 text-sm rounded-md block w-full px-3 py-2.5">
                @error('name')
                    <p class="mt-1 text-xs text-red-400 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255"
                    class="bg-[#111111] border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-gray-100 text-sm rounded-md block w-full px-3 py-2.5">
                @error('email')
                    <p class="mt-1 text-xs text-red-400 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Password</label>
                <input type="password" id="password" name="password" required minlength="4"
                    class="bg-[#111111] border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-gray-100 text-sm rounded-md block w-full px-3 py-2.5">
                <p id="passwordStrength" class="mt-1 text-xs"></p>
                @error('password')
                    <p class="mt-1 text-xs text-red-400 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="4"
                    class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2.5">
                <p id="passwordMatchMsg" class="mt-1 text-xs"></p>
            </div>

            <button type="submit"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-md shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-colors mt-1">
                <i class="fa-solid fa-user-plus mr-2"></i>
                Create Account
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-orange-400 hover:text-orange-300 font-semibold">Log in here</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('registerForm');
        var input = document.getElementById('regAvatarInput');
        var preview = document.getElementById('regAvatarPreview');
        var passwordEl = document.getElementById('password');
        var passwordStrengthEl = document.getElementById('passwordStrength');
        var confirmEl = document.getElementById('password_confirmation');
        var matchMsgEl = document.getElementById('passwordMatchMsg');

        if (input && preview) {
            input.addEventListener('change', function () {
                var file = this.files && this.files[0];
                if (!file || !file.type.startsWith('image/')) return;
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        function updatePasswordStrength() {
            if (!passwordStrengthEl || !passwordEl) return;
            var len = (passwordEl.value || '').length;
            passwordStrengthEl.textContent = '';
            passwordStrengthEl.className = 'mt-1 text-xs';
            if (len === 0) return;
            if (len < 4) {
                passwordStrengthEl.textContent = 'Too short';
                passwordStrengthEl.classList.add('text-red-400');
            } else if (len <= 7) {
                passwordStrengthEl.textContent = 'Weak';
                passwordStrengthEl.classList.add('text-amber-400');
            } else {
                passwordStrengthEl.textContent = 'Strong';
                passwordStrengthEl.classList.add('text-green-400');
            }
        }
        if (passwordEl) {
            passwordEl.addEventListener('input', updatePasswordStrength);
            passwordEl.addEventListener('blur', updatePasswordStrength);
        }

        function updatePasswordMatch() {
            if (!matchMsgEl || !confirmEl || !passwordEl) return;
            var confirmVal = (confirmEl.value || '').trim();
            matchMsgEl.textContent = '';
            matchMsgEl.className = 'mt-1 text-xs';
            confirmEl.classList.remove('border-red-500', 'border-green-500');
            confirmEl.classList.add('border-gray-700');
            if (confirmVal.length === 0) return;
            if (confirmVal !== passwordEl.value) {
                matchMsgEl.textContent = 'Passwords do not match.';
                matchMsgEl.classList.add('text-red-400');
                confirmEl.classList.add('border-red-500');
                confirmEl.classList.remove('border-gray-700');
            } else {
                matchMsgEl.textContent = '\u2713 Passwords match.';
                matchMsgEl.classList.add('text-green-400');
                confirmEl.classList.add('border-green-500');
                confirmEl.classList.remove('border-gray-700');
            }
        }
        if (confirmEl) {
            confirmEl.addEventListener('input', updatePasswordMatch);
            confirmEl.addEventListener('blur', updatePasswordMatch);
        }
        if (passwordEl && confirmEl) {
            passwordEl.addEventListener('input', function () {
                if (confirmEl.value.length > 0) updatePasswordMatch();
            });
        }

        function showError(inp, message) {
            inp.classList.add('border-red-500');
            inp.classList.remove('border-gray-700');
            inp.classList.add('focus:ring-red-500', 'focus:border-red-500');
            inp.classList.remove('focus:ring-orange-500', 'focus:border-orange-500');
            var err = inp.parentElement.querySelector('.js-error');
            if (!err) {
                err = document.createElement('p');
                err.className = 'js-error mt-1 text-xs text-red-400 flex items-center gap-1';
                err.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
                inp.parentElement.appendChild(err);
            } else {
                err.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
            }
        }

        function clearError(inp) {
            inp.classList.remove('border-red-500');
            inp.classList.add('border-gray-700');
            inp.classList.remove('focus:ring-red-500', 'focus:border-red-500');
            inp.classList.add('focus:ring-orange-500', 'focus:border-orange-500');
            var err = inp.parentElement.querySelector('.js-error');
            if (err) err.remove();
        }

        function validateField(inp) {
            if (inp.hasAttribute('required') && !inp.value.trim()) {
                showError(inp, 'This field is required.');
                return false;
            }
            if (inp.getAttribute('name') === 'name' && inp.value.trim().length > 0 && inp.value.trim().length < 2) {
                showError(inp, 'Name must be at least 2 characters.');
                return false;
            }
            if (inp.type === 'email' && inp.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(inp.value)) {
                showError(inp, 'Please enter a valid email address.');
                return false;
            }
            if (inp.getAttribute('name') === 'password' && inp.value.length > 0 && inp.value.length < 4) {
                showError(inp, 'Password must be at least 4 characters.');
                return false;
            }
            if (inp.getAttribute('name') === 'password_confirmation') {
                if (inp.value.trim() !== '') {
                    var pwd = document.getElementById('password');
                    if (pwd && inp.value !== pwd.value) {
                        showError(inp, 'Passwords do not match.');
                        return false;
                    }
                } else if (inp.hasAttribute('required')) {
                    showError(inp, 'This field is required.');
                    return false;
                }
            }
            clearError(inp);
            return true;
        }

        if (form) {
            form.querySelectorAll('input:not([type=file]):not([type=hidden]), select, textarea').forEach(function (field) {
                field.addEventListener('blur', function () { validateField(this); });
                field.addEventListener('input', function () {
                    if (this.parentElement.querySelector('.js-error')) validateField(this);
                });
            });

            form.addEventListener('submit', function (e) {
                var valid = true;
                var firstError = null;
                form.querySelectorAll('input:not([type=file]):not([type=hidden]), select, textarea').forEach(function (inp) {
                    if (!validateField(inp)) {
                        valid = false;
                        if (!firstError) firstError = inp;
                    }
                });
                if (!valid) {
                    e.preventDefault();
                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    });
</script>
@endpush
