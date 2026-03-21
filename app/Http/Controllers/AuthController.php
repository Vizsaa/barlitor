<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailVerificationMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/')->with('info', 'You are already logged in.');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status === 'inactive') {
                Auth::logout();
                return back()
                    ->with('error', 'Your account has been deactivated. Please contact support.')
                    ->withInput(['email' => $request->email]);
            }

            // Seeded demo accounts bypass email verification
            $bypassVerification = in_array($user->email, ['admin@brutor.com', 'john@example.com'], true);
            if (!$bypassVerification && !$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()
                    ->with('error', 'Your email address is not verified. Please check your inbox.')
                    ->with('unverified_email', $user->email)
                    ->withInput(['email' => $request->email]);
            }

            $request->session()->regenerate();
            return redirect('/')->with('success', 'Login successful. Welcome, ' . $user->name . '!');
        }

        return back()->with('error', 'Wrong email or password.')->withInput(['email' => $request->email]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:2|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:4|confirmed',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'name.required'     => 'Full name is required.',
            'name.min'          => 'Name must be at least 2 characters.',
            'email.required'    => 'Email address is required.',
            'email.email'       => 'Please enter a valid email address.',
            'email.unique'      => 'This email is already registered. Please log in.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 4 characters.',
            'password.confirmed'=> 'Passwords do not match.',
            'avatar.image'      => 'Avatar must be an image file.',
            'avatar.mimes'      => 'Accepted avatar formats: JPG, PNG, WEBP.',
            'avatar.max'        => 'Avatar must not exceed 5MB.',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'user_' . time() . '_reg.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/avatars'), $filename);
            $avatarPath = 'images/avatars/' . $filename;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'customer',
            'status' => 'active',
            'avatar' => $avatarPath,
        ]);

        $token = Str::random(64);
        $user->email_verification_token = $token;
        $user->save();

        Mail::to($user->email)->send(new EmailVerificationMail($user));

        return redirect()->route('verify.pending')
            ->with('registered_email', $user->email)
            ->with('success', 'Registration successful! Please verify your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    public function verifyEmail(string $token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('info', 'Your email is already verified. Please log in.');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now log in.');
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No account found with that email address.');
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'This account is already verified. Please log in.');
        }

        $token = Str::random(64);
        $user->email_verification_token = $token;
        $user->save();

        Mail::to($user->email)->send(new EmailVerificationMail($user));

        return back()->with('success', 'Verification email resent. Please check your inbox.');
    }
}
