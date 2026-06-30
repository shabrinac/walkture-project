<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update profile picture (avatar).
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        // Store the uploaded file in the public disk under 'avatars/'
        $path = $request->file('avatar')->store('avatars', 'public');

        // Delete old avatar if it exists and is not a URL
        if ($user->avatar_url && !str_starts_with($user->avatar_url, 'http')) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $user->update(['avatar_url' => $path]);

        return redirect()->route('user.profile')
                         ->with('success', 'Profile picture updated successfully.');
    }
}
