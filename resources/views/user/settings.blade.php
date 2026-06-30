<x-app-layout>
    <x-slot name="title">Settings</x-slot>
    @section('page-title', 'Settings')

    <div class="px-6 py-8 max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#141b2b] tracking-tight">Account Settings</h1>
            <p class="text-[#727971] mt-1 text-[15px]">Manage your profile information and account preferences.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-[#c5eccb] border border-[#a9d0b0] rounded-xl text-sm text-[#14361f] flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:18px;color:#43664c">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        {{-- Profile Info --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6 mb-5">
            <h2 class="text-[15px] font-bold text-[#141b2b] mb-5">Profile Information</h2>
            <form method="POST" action="{{ route('user.settings.update') }}" id="settings-profile-form">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label for="settings-name" class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Full Name</label>
                    <input id="settings-name" type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-3 border border-[#c2c8c0] rounded-xl text-[14px] text-[#141b2b] outline-none transition-all"
                           style="background:#fff"
                           onfocus="this.style.borderColor='#7ba082';this.style.boxShadow='0 0 0 3px rgba(123,160,130,.18)'"
                           onblur="this.style.borderColor='#c2c8c0';this.style.boxShadow='none'">
                    @error('name')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="mb-5">
                    <label for="settings-email" class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Email Address</label>
                    <input id="settings-email" type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-3 border border-[#c2c8c0] rounded-xl text-[14px] text-[#141b2b] outline-none transition-all"
                           style="background:#fff"
                           onfocus="this.style.borderColor='#7ba082';this.style.boxShadow='0 0 0 3px rgba(123,160,130,.18)'"
                           onblur="this.style.borderColor='#c2c8c0';this.style.boxShadow='none'">
                    @error('email')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <button type="submit" id="settings-save-profile"
                        class="px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-colors"
                        style="background:#43664c"
                        onmouseover="this.style.background='#2c4e35'" onmouseout="this.style.background='#43664c'">
                    Save Changes
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6 mb-5">
            <h2 class="text-[15px] font-bold text-[#141b2b] mb-5">Update Password</h2>
            <form method="POST" action="{{ route('password.update') }}" id="settings-password-form">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label for="settings-current-pw" class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Current Password</label>
                    <input id="settings-current-pw" type="password" name="current_password"
                           class="w-full px-4 py-3 border border-[#c2c8c0] rounded-xl text-[14px] outline-none"
                           autocomplete="current-password"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    @error('current_password', 'updatePassword')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label for="settings-new-pw" class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">New Password</label>
                    <input id="settings-new-pw" type="password" name="password"
                           class="w-full px-4 py-3 border border-[#c2c8c0] rounded-xl text-[14px] outline-none"
                           autocomplete="new-password"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    @error('password', 'updatePassword')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="mb-5">
                    <label for="settings-confirm-pw" class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Confirm New Password</label>
                    <input id="settings-confirm-pw" type="password" name="password_confirmation"
                           class="w-full px-4 py-3 border border-[#c2c8c0] rounded-xl text-[14px] outline-none"
                           autocomplete="new-password"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                </div>
                <button type="submit" id="settings-save-password"
                        class="px-6 py-2.5 rounded-xl text-[13px] font-bold border-2 border-[#43664c] text-[#43664c] transition-colors bg-white hover:bg-[#f1f3ff]">
                    Update Password
                </button>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white border border-red-100 rounded-2xl p-6">
            <h2 class="text-[15px] font-bold text-red-700 mb-2">Danger Zone</h2>
            <p class="text-[13px] text-[#727971] mb-4">Once you delete your account, all your data will be permanently removed. This action cannot be undone.</p>
            <button type="button" id="settings-delete-btn"
                    onclick="document.getElementById('delete-modal').style.display='flex'"
                    class="px-5 py-2.5 rounded-xl text-[13px] font-bold border-2 border-red-400 text-red-600 hover:bg-red-50 transition-colors">
                Delete Account
            </button>
        </div>
    </div>

    {{-- Delete Account Confirmation Modal --}}
    <div id="delete-modal"
         style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;padding:1rem">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mb-5 mx-auto" style="background:#fef2f2">
                <span class="material-symbols-outlined text-red-500" style="font-size:28px">warning</span>
            </div>
            <h3 class="text-[20px] font-bold text-[#141b2b] text-center mb-2">Delete Your Account?</h3>
            <p class="text-[13px] text-[#727971] text-center mb-6 leading-relaxed">
                This will permanently delete your account and all your bookings.
                Please enter your password to confirm.
            </p>
            <form method="POST" action="{{ route('user.account.destroy') }}" id="settings-delete-form">
                @csrf @method('DELETE')
                <div class="mb-4">
                    <label for="delete-confirm-pw" class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Your Password</label>
                    <input id="delete-confirm-pw" type="password" name="password" required
                           placeholder="Enter your current password"
                           class="w-full px-4 py-3 border border-[#c2c8c0] rounded-xl text-[14px] outline-none"
                           onfocus="this.style.borderColor='#ef4444'" onblur="this.style.borderColor='#c2c8c0'">
                    @error('password')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="button" id="delete-modal-cancel"
                            onclick="document.getElementById('delete-modal').style.display='none'"
                            class="flex-1 py-3 rounded-xl text-[13px] font-bold border border-[#e5e7eb] text-[#424842] hover:bg-[#f1f3ff] transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="delete-modal-confirm"
                            class="flex-1 py-3 rounded-xl text-[13px] font-bold text-white bg-red-600 hover:bg-red-700 transition-colors">
                        Yes, Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
