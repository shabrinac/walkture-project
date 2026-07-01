<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct(private CloudinaryService $storage) {}

    public function index()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    /**
     * Update the user's profile information (name, email, avatar).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload avatar to Cloudinary
        if ($request->hasFile('avatar')) {
            if ($user->avatar_url && str_contains($user->avatar_url, 'cloudinary.com')) {
                $this->storage->delete($user->avatar_url);
            }

            $validated['avatar_url'] = $this->storage->store(
                $request->file('avatar'),
                'avatars'
            );
        }
        unset($validated['avatar']);

        $user->update($validated);

        return redirect()->route('user.settings')
                         ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.settings')
                         ->with('success', 'Password updated successfully.');
    }

    /**
     * Permanently delete the authenticated user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        // Clean up avatar from Cloudinary
        if ($user->avatar_url && str_contains($user->avatar_url, 'cloudinary.com')) {
            $this->storage->delete($user->avatar_url);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
                         ->with('success', 'Your account has been permanently deleted.');
    }
}
