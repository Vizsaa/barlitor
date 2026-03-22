@extends('layouts.app')
@section('title', 'Change Password - BarliTor Shop')

@section('content')
<div class="max-w-md mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-[#111111] rounded-xl border border-gray-800 shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-800">
            <h4 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-key text-orange-500"></i> Change Password
            </h4>
        </div>

        {{-- Form Body --}}
        <div class="px-6 py-6 border-b border-gray-800">
            <form method="POST" action="{{ route('profile.updatePassword') }}" class="space-y-5">
                @csrf
                
                {{-- Current Password --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-400 mb-1">
                        Current Password
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full bg-[#151515] border border-gray-700 rounded-md py-2 px-3 text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                    @error('current_password')
                        <p class="mt-1 text-red-500 text-sm"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-400 mb-1">
                        New Password
                    </label>
                    <input type="password" id="new_password" name="new_password" required
                           class="w-full bg-[#151515] border border-gray-700 rounded-md py-2 px-3 text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                    @error('new_password')
                        <p class="mt-1 text-red-500 text-sm"><i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-400 mb-1">
                        Confirm New Password
                    </label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                           class="w-full bg-[#151515] border border-gray-700 rounded-md py-2 px-3 text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-colors">
                </div>

                {{-- Actions --}}
                <div class="pt-2 flex justify-end gap-3">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-500 text-white px-5 py-2.5 rounded-md font-semibold transition-colors flex items-center shadow-lg">
                        <i class="fa-solid fa-check mr-2"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
        
        {{-- Footer/Cancel --}}
        <div class="px-6 py-4 bg-[#151515] flex justify-between items-center">
            <span class="text-sm text-gray-500">Secure your account</span>
            <a href="{{ route('profile.show', auth()->id()) }}" class="text-sm text-gray-400 hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Profile
            </a>
        </div>
    </div>
</div>
@endsection
