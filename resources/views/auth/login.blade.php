@extends('layouts.app')
@section('title', 'Login - BruTor Shop')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-10 px-4">
    <div class="max-w-md w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl shadow-xl px-6 py-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-orange-500/10 border border-orange-500/40 mb-3">
                <i class="fa-solid fa-right-to-bracket text-2xl text-orange-400"></i>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">
                Welcome back
            </h1>
            <p class="text-sm text-gray-400 mt-1">Sign in to your BruTor Shop account</p>
        </div>

        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-900/20 border border-red-800/50 text-red-200 text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-900/20 border border-green-700/50 text-green-200 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mb-4 p-3 rounded-lg bg-blue-900/20 border border-blue-700/50 text-blue-200 text-sm">
                {{ session('info') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2.5"
                       placeholder="you@example.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Password</label>
                <input type="password" id="password" name="password" required
                       class="bg-[#111111] border border-gray-700 text-gray-100 text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2.5"
                       placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-md shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-colors">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                Sign in
            </button>
        </form>

        @if(session('unverified_email'))
            <div class="mt-4 rounded-lg border border-yellow-700/50 bg-yellow-900/20 p-4 text-sm">
                <p class="text-yellow-200 mb-2">
                    Your email address is not verified. Please check your inbox for the verification link.
                </p>
                <form method="POST" action="{{ route('verify.resend') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                    <button type="submit" class="text-orange-400 hover:text-orange-300 font-semibold underline text-sm">
                        Resend verification email
                    </button>
                </form>
            </div>
        @endif

        <p class="mt-6 text-center text-xs text-gray-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-orange-400 hover:text-orange-300 font-semibold">Register here</a>
        </p>
    </div>
</div>
@endsection
