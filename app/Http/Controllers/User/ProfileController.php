<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private CloudinaryService $storage) {}

    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update profile picture (avatar) — uploads to Cloudinary.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        // Delete old avatar from Cloudinary if it was stored there
        if ($user->avatar_url && str_contains($user->avatar_url, 'cloudinary.com')) {
            $this->storage->delete($user->avatar_url);
        }

        // Upload new avatar to Cloudinary
        $avatarUrl = $this->storage->store(
            $request->file('avatar'),
            'avatars'
        );

        $user->update(['avatar_url' => $avatarUrl]);

        return redirect()->route('user.profile')
                         ->with('success', 'Profile picture updated successfully.');
    }
}
