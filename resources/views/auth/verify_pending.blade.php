@extends('layouts.app')
@section('title', 'Verify Your Email - BruTor Shop')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-10 px-4">
    <div class="max-w-md w-full bg-[#1a1a1a] border border-gray-800 rounded-2xl shadow-xl px-6 py-7 text-center">
        <div class="mb-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-500/10 border border-orange-500/40 mb-3">
                <i class="fa-solid fa-envelope-open-text text-2xl text-orange-400"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">Check Your Email</h1>
            <p class="text-sm text-gray-400">
                We've sent a verification link to
                <span class="font-semibold text-gray-100">
                    {{ session('registered_email') ?? 'your email address' }}
                </span>.
            </p>
        </div>

        <p class="text-xs text-gray-500 mb-4">
            Click the link in that email to verify your account and complete your registration.
            If you don't see it, check your spam or junk folder.
        </p>

        @if(session('registered_email'))
            <form method="POST" action="{{ route('verify.resend') }}" class="mb-4">
                @csrf
                <input type="hidden" name="email" value="{{ session('registered_email') }}">
                <button type="submit"
                        class="inline-flex items-center px-3 py-2 rounded-md border border-gray-700 text-xs font-semibold text-gray-200 hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-paper-plane mr-2 text-orange-400"></i>
                    Resend Verification Email
                </button>
            </form>
        @endif

        <a href="{{ route('login') }}"
           class="inline-flex items-center px-3 py-2 rounded-md bg-gray-800 hover:bg-gray-700 text-xs font-semibold text-gray-100 border border-gray-700 transition-colors">
            <i class="fa-solid fa-right-to-bracket mr-2"></i>
            Back to Login
        </a>
    </div>
</div>
@endsection

