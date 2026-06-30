<x-app-layout>
    <x-slot name="title">Add Photographer</x-slot>
    @section('page-title', 'Add Photographer')

    <div class="px-6 py-8 max-w-3xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('admin.directory') }}" class="hover:text-[#43664c] font-semibold transition-colors">Photographer Directory</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold">Add Photographer</span>
        </div>

        <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-[#f1f3ff] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#c5eccb">
                    <span class="material-symbols-outlined" style="color:#43664c">person_add</span>
                </div>
                <div>
                    <h1 class="text-[17px] font-bold text-[#141b2b]">Add Photographer to Directory</h1>
                    <p class="text-[12px] text-[#727971]">New listings are active by default and visible to all users.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.directory.store') }}" class="px-6 py-6 space-y-5">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-[13px] text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined" style="font-size:14px">error</span>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                {{-- Full Name & Specialty --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="ph-name" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Full Name <span class="text-red-400">*</span></label>
                        <input id="ph-name" name="full_name" type="text" required value="{{ old('full_name') }}"
                               placeholder="e.g. Ahmad Fauzan"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                    <div>
                        <label for="ph-specialty" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Specialty <span class="text-red-400">*</span></label>
                        <select id="ph-specialty" name="specialty" required
                                class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none cursor-pointer transition-colors"
                                onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                            <option value="">Select specialty…</option>
                            @foreach(['Street', 'Portrait', 'Landscape', 'Analog', 'Architecture', 'Wildlife', 'Event', 'Product', 'Other'] as $sp)
                                <option value="{{ $sp }}" {{ old('specialty') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Avatar URL --}}
                <div>
                    <label for="ph-avatar" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Avatar / Profile Photo URL</label>
                    <input id="ph-avatar" name="avatar_url" type="url" value="{{ old('avatar_url') }}"
                           placeholder="https://example.com/photo.jpg"
                           class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                </div>

                {{-- Portfolio URL --}}
                <div>
                    <label for="ph-portfolio" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Portfolio Website</label>
                    <input id="ph-portfolio" name="portfolio_url" type="url" value="{{ old('portfolio_url') }}"
                           placeholder="https://photographer-portfolio.com"
                           class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                </div>

                {{-- Social Links --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="ph-wa" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">
                            <span class="inline-flex items-center gap-1">
                                <span class="text-[#25D366]">●</span> WhatsApp Link
                            </span>
                        </label>
                        <input id="ph-wa" name="whatsapp_link" type="url" value="{{ old('whatsapp_link') }}"
                               placeholder="https://wa.me/6281234567890"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                    <div>
                        <label for="ph-ig" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">
                            <span class="inline-flex items-center gap-1">
                                <span style="color:#dc2743">●</span> Instagram Link
                            </span>
                        </label>
                        <input id="ph-ig" name="instagram_link" type="url" value="{{ old('instagram_link') }}"
                               placeholder="https://instagram.com/yourhandle"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                </div>

                {{-- Active Status --}}
                <div class="flex items-start gap-3 p-4 rounded-xl border border-[#e5e7eb] bg-[#f9f9ff]">
                    <input id="ph-active" name="is_active" type="checkbox" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 mt-0.5 accent-[#43664c] cursor-pointer">
                    <div>
                        <label for="ph-active" class="text-[14px] font-semibold text-[#141b2b] cursor-pointer">Active Listing</label>
                        <p class="text-[12px] text-[#727971] mt-0.5">Inactive photographers are hidden from the public user directory.</p>
                    </div>
                </div>

                {{-- Featured Until (Paid) --}}
                <div>
                    <label for="ph-paid" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Featured Until (Paid Until)</label>
                    <input id="ph-paid" name="paid_until" type="date" value="{{ old('paid_until') }}"
                           class="w-full sm:w-56 px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    <p class="text-[11px] text-[#727971] mt-1">Leave blank for a free listing. Set a future date for a paid/featured slot (shown with ⭐ badge).</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-[#f1f3ff]">
                    <a href="{{ route('admin.directory') }}" id="ph-form-cancel"
                       class="px-5 py-2.5 rounded-xl text-[13px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="ph-form-submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-all hover:opacity-90"
                            style="background:#43664c">
                        <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
                        Add Photographer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
