<x-app-layout>
    <x-slot name="title">{{ $photographer->full_name }} — Photographer Details</x-slot>
    @section('page-title', $photographer->full_name)

    <div class="px-6 py-8 max-w-5xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('admin.directory') }}" class="hover:text-[#43664c] font-semibold transition-colors">Photographer Directory</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold truncate">{{ $photographer->full_name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Profile Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden">
                    <div class="h-24 w-full" style="background:linear-gradient(135deg,#43664c,#7ba082)"></div>
                    <div class="px-6 pb-6 -mt-12">
                        <div class="mb-4">
                            @if($photographer->avatar_url)
                                <img src="{{ $photographer->avatar_url }}" alt="{{ $photographer->full_name }}"
                                     class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                            @else
                                <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-md"
                                     style="background:#7ba082">
                                    {{ strtoupper(substr($photographer->full_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h1 class="text-xl font-bold text-[#141b2b]">{{ $photographer->full_name }}</h1>
                        <p class="text-[12px] text-[#43664c] font-bold uppercase tracking-widest mt-1">{{ $photographer->specialty }}</p>

                        <span class="inline-block mt-2 text-[11px] font-bold px-2 py-0.5 rounded-full {{ $photographer->is_active ? 'bg-[#c5eccb] text-[#43664c]' : 'bg-[#f1f3ff] text-[#727971]' }}">
                            {{ $photographer->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        @if($photographer->paid_until)
                        <p class="text-[11px] text-[#727971] mt-2">
                            Featured until: {{ \Carbon\Carbon::parse($photographer->paid_until)->format('d M Y') }}
                        </p>
                        @endif

                        <div class="mt-5 space-y-2">
                            @if($photographer->whatsapp_link)
                            <a href="{{ $photographer->whatsapp_link }}" target="_blank"
                               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-[13px] font-bold text-white"
                               style="background:#25D366">
                                WhatsApp
                            </a>
                            @endif
                            @if($photographer->instagram_link)
                            <a href="{{ $photographer->instagram_link }}" target="_blank"
                               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-[13px] font-bold text-white"
                               style="background:linear-gradient(135deg,#f09433,#dc2743)">
                                Instagram
                            </a>
                            @endif
                            @if($photographer->portfolio_url)
                            <a href="{{ $photographer->portfolio_url }}" target="_blank"
                               class="flex items-center justify-center w-full py-2.5 rounded-xl text-[13px] font-bold text-[#43664c] border border-[#7ba082] hover:bg-[#f1f3ff] transition-colors">
                                Portfolio ↗
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <h2 class="text-[15px] font-bold text-[#141b2b] mb-4">Directory Details</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Specialty</p>
                                <p class="text-[14px] font-semibold text-[#141b2b]">{{ $photographer->specialty }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Status</p>
                                <p class="text-[14px] font-semibold text-[#141b2b]">{{ $photographer->is_active ? 'Active' : 'Inactive' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#f1f3ff]">
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Photographer ID</p>
                                <p class="text-[14px] font-semibold text-[#141b2b] font-mono">#{{ $photographer->id }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Listed Since</p>
                                <p class="text-[14px] font-semibold text-[#141b2b]">{{ $photographer->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if($photographer->paid_until)
                        <div class="pt-4 border-t border-[#f1f3ff]">
                            <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Featured Until</p>
                            <p class="text-[14px] font-semibold text-[#141b2b]">{{ \Carbon\Carbon::parse($photographer->paid_until)->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Admin Actions --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <h2 class="text-[15px] font-bold text-[#141b2b] mb-4">Admin Actions</h2>
                    <div class="flex gap-3 flex-wrap">
                        <a href="{{ route('admin.directory.create') }}" id="admin-dir-add-btn"
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-bold text-white"
                           style="background:#43664c">
                            <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
                            Add Photographer
                        </a>
                        <a href="{{ route('admin.directory') }}" id="admin-dir-back-btn"
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">arrow_back</span>
                            Back to Directory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
