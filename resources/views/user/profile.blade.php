<x-app-layout>
    <x-slot name="title">My Profile</x-slot>
    @section('page-title', 'My Profile')

    <div class="px-6 py-8 max-w-3xl mx-auto">

        @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-[#f0faf2] border border-[#7ba082] text-[#43664c] rounded-xl px-4 py-3 text-[13px] font-semibold">
            <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        {{-- Profile Header --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden mb-6">
            <div class="h-24 w-full" style="background:linear-gradient(135deg,#43664c,#7ba082)"></div>
            <div class="px-6 pb-6">
                <div class="flex items-end gap-4 -mt-10 mb-4">
                    {{-- Avatar with upload button --}}
                    <div class="relative flex-shrink-0">
                        @if($user->avatar_url && !str_starts_with($user->avatar_url, 'http'))
                            <img src="{{ asset('storage/'.$user->avatar_url) }}" alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-2xl border-4 border-white object-cover shadow-md">
                        @elseif($user->avatar_url && str_starts_with($user->avatar_url, 'http'))
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-2xl border-4 border-white object-cover shadow-md">
                        @else
                            <div class="w-20 h-20 rounded-2xl border-4 border-white flex items-center justify-center text-white text-2xl font-bold shadow-md"
                                 style="background:#7ba082">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        {{-- Upload trigger button --}}
                        <button type="button" onclick="document.getElementById('avatar-file-input').click()"
                                id="profile-avatar-edit-btn"
                                class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-[#43664c] flex items-center justify-center border-2 border-white shadow-sm hover:bg-[#2c4e35] transition-colors">
                            <span class="material-symbols-outlined text-white" style="font-size:14px">edit</span>
                        </button>
                    </div>
                    <div class="pb-1 flex-1">
                        <h1 class="text-xl font-bold text-[#141b2b] leading-tight">{{ $user->name }}</h1>
                        <p class="text-[13px] text-[#727971] mt-0.5">{{ $user->email }}</p>
                    </div>
                    <div class="ml-auto pb-1">
                        <span class="text-[11px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full"
                              style="background:#c5eccb;color:#43664c">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                {{-- Hidden avatar upload form --}}
                <form method="POST" action="{{ route('user.profile.avatar') }}" enctype="multipart/form-data" id="avatar-upload-form">
                    @csrf
                    <input type="file" id="avatar-file-input" name="avatar" accept="image/*"
                           class="hidden" onchange="this.form.submit()">
                </form>
                @error('avatar')
                    <p class="text-red-500 text-[12px] mt-1 mb-2">{{ $message }}</p>
                @enderror

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#f1f3ff]">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#141b2b]">—</p>
                        <p class="text-[11px] text-[#727971] mt-0.5">Spots Visited</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[#141b2b]">—</p>
                        <p class="text-[11px] text-[#727971] mt-0.5">Assets Owned</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6 mb-6">
            <h2 class="text-[15px] font-bold text-[#141b2b] mb-4">Account Details</h2>
            <div class="space-y-4">
                {{-- Full Name --}}
                <div class="flex items-center justify-between py-3 border-b border-[#f1f3ff]">
                    <div class="flex-1">
                        <p class="text-[12px] text-[#727971] font-medium uppercase tracking-wide">Full Name</p>
                        <p class="text-[14px] font-semibold text-[#141b2b] mt-0.5">{{ $user->name }}</p>
                    </div>
                    <a href="{{ route('user.settings') }}" id="profile-edit-name"
                       class="flex-shrink-0 ml-4 text-[12px] font-semibold text-[#43664c] hover:underline">Edit</a>
                </div>
                {{-- Email --}}
                <div class="flex items-center justify-between py-3 border-b border-[#f1f3ff]">
                    <div class="flex-1">
                        <p class="text-[12px] text-[#727971] font-medium uppercase tracking-wide">Email Address</p>
                        <p class="text-[14px] font-semibold text-[#141b2b] mt-0.5">{{ $user->email }}</p>
                    </div>
                    <a href="{{ route('user.settings') }}" id="profile-edit-email"
                       class="flex-shrink-0 ml-4 text-[12px] font-semibold text-[#43664c] hover:underline">Edit</a>
                </div>
                {{-- Role --}}
                <div class="flex items-center justify-between py-3">
                    <div class="flex-1">
                        <p class="text-[12px] text-[#727971] font-medium uppercase tracking-wide">Account Role</p>
                        <p class="text-[14px] font-semibold text-[#141b2b] mt-0.5">{{ ucfirst($user->role) }}</p>
                    </div>
                    <span class="flex-shrink-0 ml-4 text-[12px] text-[#727971]">Read-only</span>
                </div>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('user.settings') }}" id="profile-to-settings"
               class="bg-white border border-[#e5e7eb] rounded-2xl p-5 flex items-center gap-3 hover:border-[#7ba082] hover:shadow-sm transition-all group">
                <span class="material-symbols-outlined text-[#43664c]">settings</span>
                <div>
                    <p class="text-[13px] font-bold text-[#141b2b] group-hover:text-[#43664c]">Settings</p>
                    <p class="text-[11px] text-[#727971]">Account preferences</p>
                </div>
            </a>
            <a href="{{ route('user.map') }}" id="profile-to-map"
               class="bg-white border border-[#e5e7eb] rounded-2xl p-5 flex items-center gap-3 hover:border-[#7ba082] hover:shadow-sm transition-all group">
                <span class="material-symbols-outlined text-[#43664c]">map</span>
                <div>
                    <p class="text-[13px] font-bold text-[#141b2b] group-hover:text-[#43664c]">Interactive Map</p>
                    <p class="text-[11px] text-[#727971]">Explore photo spots</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
