<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request, $id = null)
    {
        $viewUid = $id ?: (Auth::check() ?Auth::id() : null);

        if (!$viewUid) {
            return redirect('/')->with('error', 'No user specified.');
        }

        $user = User::find($viewUid);
        if (!$user) {
            return redirect('/')->with('error', 'User not found.');
        }

        $isOwnProfile = Auth::check() && Auth::id() == $viewUid;
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $isEditing = $request->query('edit') == '1' && $isOwnProfile;

        return view('profile.show', compact('user', 'isOwnProfile', 'isAdmin', 'isEditing'));
    }

    public function update(Request $request)
    {
        $targetUid = $request->input('user_id', Auth::id());
        $isOwnProfile = Auth::id() == $targetUid;

        if (!$isOwnProfile && !Auth::user()->isAdmin()) {
            return redirect()->route('profile.show', $targetUid)->with('error', 'You do not have permission to edit this profile.');
        }

        $request->validate([
            'fname' => 'required|string|max:50',
            'lname' => 'required|string|max:50',
            'title' => 'nullable|string|max:4',
            'addressline' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:50',
            'zipcode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::findOrFail($targetUid);
        $fullName = $request->fname . ' ' . $request->lname;

        $user->update([
            'name' => $fullName,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'title' => $request->title,
            'addressline' => $request->addressline,
            'town' => $request->town,
            'zipcode' => $request->zipcode,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.show', $targetUid)->with('success', 'Profile updated successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:5120',
        ]);

        $userId = $request->input('user_id', Auth::id());

        if (Auth::id() != $userId && !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);

        $file = $request->file('avatar');
        $ext = $file->getClientOriginalExtension();
        $filename = "user_{$userId}_" . time() . ".{$ext}";
        $file->move(public_path('images/avatars'), $filename);

        $avatarPath = 'images/avatars/' . $filename;
        $user->update(['avatar' => $avatarPath]);

        return response()->json(['success' => true, 'path' => $avatarPath]);
    }

    public function changePassword()
    {
        return view('profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:4|confirmed',
        ], [
            'new_password.confirmed' => 'New password and confirmation do not match.',
            'new_password.min' => 'Password must be at least 4 characters long.',
        ]);

        $user = Auth::user();

        if ($request->current_password !== $user->password) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update(['password' => $request->new_password]);

        return redirect()->route('profile.show', $user->id)->with('success', 'Password updated successfully!');
    }
}
