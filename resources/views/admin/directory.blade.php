<x-app-layout>
    <x-slot name="title">Photographer Directory</x-slot>
    @section('page-title', 'Photographer Directory')

    <div class="px-6 py-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#141b2b] tracking-tight">Photographer Directory</h1>
                <p class="text-[#727971] mt-1 text-[15px]">Manage photographer listings and featured promotions.</p>
            </div>
            {{-- Opens Create modal instead of navigating away --}}
            <button id="admin-dir-add-btn" type="button" onclick="openModal('dir-create-modal')"
               class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-opacity hover:opacity-90"
               style="background:#43664c">
                <span class="material-symbols-outlined" style="font-size:18px">person_add</span> Add Photographer
            </button>
        </div>

        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-[#f0faf2] border border-[#7ba082] text-[#43664c] rounded-xl px-4 py-3 text-[13px] font-semibold" id="flash-success">
            <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#f1f3ff] flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-[13px] font-semibold text-[#141b2b]">{{ $photographers->count() }} photographers</span>
                </div>
                <input type="text" id="dir-search" placeholder="Search by name or specialty…"
                       class="px-3 py-2 border border-[#c2c8c0] rounded-xl text-[13px] outline-none w-full sm:w-72"
                       onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'"
                       oninput="filterDir(this.value)">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#f1f3ff]">
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Photographer</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Specialty</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Status</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Featured Until</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Contact</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dir-tbody">
                        @forelse($photographers as $photographer)
                        <tr class="border-b border-[#f1f3ff] hover:bg-[#f9f9ff] transition-colors dir-row" id="dir-row-{{ $photographer->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                         style="background:#7ba082">{{ strtoupper(substr($photographer->full_name,0,1)) }}</div>
                                    <div>
                                        <p class="text-[14px] font-semibold text-[#141b2b]">{{ $photographer->full_name }}</p>
                                        @if($photographer->portfolio_url)
                                            <a href="{{ $photographer->portfolio_url }}" target="_blank" id="dir-portfolio-{{ $photographer->id }}"
                                               class="text-[11px] text-[#43664c] hover:underline">Portfolio ↗</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[13px] text-[#727971]">{{ $photographer->specialty }}</td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ $photographer->is_active ? 'bg-[#c5eccb] text-[#43664c]' : 'bg-[#f1f3ff] text-[#727971]' }}">
                                    {{ $photographer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[12px] text-[#727971]">
                                {{ $photographer->paid_until ? \Carbon\Carbon::parse($photographer->paid_until)->format('d M Y') : '—' }}
                                @if($photographer->paid_until && $photographer->paid_until >= now())
                                    <span class="ml-1 text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-1.5 py-0.5 rounded-full">⭐ Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    @if($photographer->whatsapp_link)
                                    <a href="{{ $photographer->whatsapp_link }}" target="_blank" id="dir-admin-wa-{{ $photographer->id }}"
                                       class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-white" style="background:#25D366">WA</a>
                                    @endif
                                    @if($photographer->instagram_link)
                                    <a href="{{ $photographer->instagram_link }}" target="_blank" id="dir-admin-ig-{{ $photographer->id }}"
                                       class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-white"
                                       style="background:linear-gradient(135deg,#f09433,#dc2743)">IG</a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                 <div class="flex gap-2">
                                     <a href="{{ route('admin.directory.show', $photographer->id) }}"
                                        id="dir-view-{{ $photographer->id }}"
                                        class="px-3 py-1.5 rounded-lg text-[12px] font-semibold border border-[#7ba082] text-[#43664c] hover:bg-[#f1f3ff] transition-colors">
                                         View
                                     </a>
                                     <button id="dir-edit-{{ $photographer->id }}" type="button"
                                             onclick="openEditModal(
                                                {{ $photographer->id }},
                                                '{{ addslashes($photographer->full_name) }}',
                                                '{{ $photographer->specialty }}',
                                                '{{ $photographer->avatar_url }}',
                                                '{{ $photographer->portfolio_url }}',
                                                '{{ $photographer->whatsapp_link }}',
                                                '{{ $photographer->instagram_link }}',
                                                {{ $photographer->is_active ? 'true' : 'false' }},
                                                '{{ $photographer->paid_until ?? '' }}',
                                                '{{ addslashes(json_encode($photographer->pricing_packages)) }}'
                                             )"
                                             class="px-3 py-1.5 rounded-lg text-[12px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">Edit</button>
                                     <button id="dir-delete-{{ $photographer->id }}" type="button"
                                             onclick="deletePhotographer({{ $photographer->id }}, '{{ addslashes($photographer->full_name) }}')"
                                             class="px-3 py-1.5 rounded-lg text-[12px] font-semibold border border-red-100 text-red-500 hover:bg-red-50 transition-colors">Delete</button>
                                 </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-[13px] text-[#727971]">No photographers listed yet. Add the first one!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         CREATE MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div id="dir-create-modal" class="wt-modal-backdrop" onclick="handleBackdropClick(event,'dir-create-modal')" aria-hidden="true">
        <div class="wt-modal-box" role="dialog" aria-modal="true" aria-labelledby="dir-create-title">
            {{-- Modal Header --}}
            <div class="wt-modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#c5eccb">
                        <span class="material-symbols-outlined" style="color:#43664c;font-size:20px">person_add</span>
                    </div>
                    <div>
                        <h2 id="dir-create-title" class="text-[16px] font-bold text-[#141b2b]">Add Photographer</h2>
                        <p class="text-[12px] text-[#727971]">New listings are active by default and visible to all users.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('dir-create-modal')" class="wt-modal-close" aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Modal Form --}}
            <form method="POST" action="{{ route('admin.directory.store') }}" class="wt-modal-body"
                  enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-[13px] text-red-600 space-y-1 mb-2">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined" style="font-size:14px">error</span>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="ph-name" class="wt-label">Full Name <span class="text-red-400">*</span></label>
                        <input id="ph-name" name="full_name" type="text" required value="{{ old('full_name') }}"
                               placeholder="e.g. Ahmad Fauzan" class="wt-input">
                    </div>
                    <div>
                        <label for="ph-specialty" class="wt-label">Specialty <span class="text-red-400">*</span></label>
                        <select id="ph-specialty" name="specialty" required class="wt-input cursor-pointer">
                            <option value="">Select specialty…</option>
                            @foreach(['Street', 'Portrait', 'Landscape', 'Analog', 'Architecture', 'Wildlife', 'Event', 'Product', 'Other'] as $sp)
                                <option value="{{ $sp }}" {{ old('specialty') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="wt-label">Avatar / Profile Photo</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <label for="ph-avatar-file"
                                   class="flex items-center gap-2 px-4 py-2 border border-dashed border-[#c2c8c0] rounded-xl text-[13px] text-[#727971] cursor-pointer hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                                <span class="material-symbols-outlined" style="font-size:16px">upload</span>
                                Upload image
                            </label>
                            <span class="text-[12px] text-[#727971]" id="ph-avatar-fname">JPG, PNG or WebP &middot; max 3 MB</span>
                            <input id="ph-avatar-file" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="hidden"
                                   onchange="document.getElementById('ph-avatar-fname').textContent = this.files[0]?.name || 'No file chosen'">
                        </div>
                        <input id="ph-avatar" name="avatar_url" type="url" value="{{ old('avatar_url') }}"
                               placeholder="Or paste an image URL…" class="wt-input text-[13px]">
                    </div>
                </div>

                <div>
                    <label for="ph-portfolio" class="wt-label">Portfolio Website</label>
                    <input id="ph-portfolio" name="portfolio_url" type="url" value="{{ old('portfolio_url') }}"
                           placeholder="https://photographer-portfolio.com" class="wt-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="ph-wa" class="wt-label"><span class="text-[#25D366]">●</span> WhatsApp Link</label>
                        <input id="ph-wa" name="whatsapp_link" type="url" value="{{ old('whatsapp_link') }}"
                               placeholder="https://wa.me/628…" class="wt-input">
                    </div>
                    <div>
                        <label for="ph-ig" class="wt-label"><span style="color:#dc2743">●</span> Instagram Link</label>
                        <input id="ph-ig" name="instagram_link" type="url" value="{{ old('instagram_link') }}"
                               placeholder="https://instagram.com/handle" class="wt-input">
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 rounded-xl border border-[#e5e7eb] bg-[#f9f9ff]">
                    <input id="ph-active" name="is_active" type="checkbox" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 mt-0.5 accent-[#43664c] cursor-pointer">
                    <div>
                        <label for="ph-active" class="text-[14px] font-semibold text-[#141b2b] cursor-pointer">Active Listing</label>
                        <p class="text-[12px] text-[#727971] mt-0.5">Inactive photographers are hidden from the public directory.</p>
                    </div>
                </div>

                <div>
                    <label for="ph-paid" class="wt-label">Featured Until (Paid Until)</label>
                    <input id="ph-paid" name="paid_until" type="date" value="{{ old('paid_until') }}"
                           class="wt-input sm:w-56">
                    <p class="text-[11px] text-[#727971] mt-1">Leave blank for free listing. Set a future date for a paid ⭐ slot.</p>
                </div>

                {{-- Pricing Packages --}}
                <div class="border border-[#e5e7eb] rounded-xl p-4 space-y-4 bg-[#f9f9ff]">
                    <p class="text-[12px] font-bold text-[#141b2b] uppercase tracking-wide">💰 Pricing Packages</p>
                    @foreach(['basic' => 'Basic', 'standard' => 'Standard', 'premium' => 'Premium', 'fullday' => 'Full Day Event'] as $key => $label)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pb-3 border-b border-[#f1f3ff] last:border-0 last:pb-0">
                        <div>
                            <label class="wt-label">{{ $label }} — Price</label>
                            <input name="packages[{{ $key }}][price]" type="text"
                                   value="{{ old('packages.' . $key . '.price') }}"
                                   placeholder="e.g. Rp150k – 300k" class="wt-input">
                        </div>
                        <div>
                            <label class="wt-label">{{ $label }} — Details / Features</label>
                            <input name="packages[{{ $key }}][description]" type="text"
                                   value="{{ old('packages.' . $key . '.description') }}"
                                   placeholder="e.g. 1 jam, 30–50 foto, Google Drive" class="wt-input">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="wt-modal-footer">
                    <button type="button" onclick="closeModal('dir-create-modal')" class="wt-btn-cancel">Cancel</button>
                    <button type="submit" id="ph-form-submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-opacity hover:opacity-90"
                            style="background:#43664c">
                        <span class="material-symbols-outlined" style="font-size:18px">person_add</span>
                        Add Photographer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         EDIT MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div id="dir-edit-modal" class="wt-modal-backdrop" onclick="handleBackdropClick(event,'dir-edit-modal')" aria-hidden="true">
        <div class="wt-modal-box" role="dialog" aria-modal="true" aria-labelledby="dir-edit-title">
            <div class="wt-modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#e9edff">
                        <span class="material-symbols-outlined" style="color:#43664c;font-size:20px">edit</span>
                    </div>
                    <div>
                        <h2 id="dir-edit-title" class="text-[16px] font-bold text-[#141b2b]">Edit Photographer</h2>
                        <p class="text-[12px] text-[#727971]">Changes are saved immediately on submit.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('dir-edit-modal')" class="wt-modal-close" aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="dir-edit-form" method="POST" action="" class="wt-modal-body"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-ph-name" class="wt-label">Full Name <span class="text-red-400">*</span></label>
                        <input id="edit-ph-name" name="full_name" type="text" required class="wt-input">
                    </div>
                    <div>
                        <label for="edit-ph-specialty" class="wt-label">Specialty <span class="text-red-400">*</span></label>
                        <select id="edit-ph-specialty" name="specialty" required class="wt-input cursor-pointer">
                            <option value="">Select specialty…</option>
                            @foreach(['Street', 'Portrait', 'Landscape', 'Analog', 'Architecture', 'Wildlife', 'Event', 'Product', 'Other'] as $sp)
                                <option value="{{ $sp }}">{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="wt-label">Avatar / Profile Photo</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <label for="edit-ph-avatar-file"
                                   class="flex items-center gap-2 px-4 py-2 border border-dashed border-[#c2c8c0] rounded-xl text-[13px] text-[#727971] cursor-pointer hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                                <span class="material-symbols-outlined" style="font-size:16px">upload</span>
                                Upload new image
                            </label>
                            <span class="text-[12px] text-[#727971]" id="edit-ph-avatar-fname">Leave empty to keep existing</span>
                            <input id="edit-ph-avatar-file" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="hidden"
                                   onchange="document.getElementById('edit-ph-avatar-fname').textContent = this.files[0]?.name || 'Leave empty to keep existing'">
                        </div>
                        <input id="edit-ph-avatar" name="avatar_url" type="url" class="wt-input text-[13px]" placeholder="Or paste a new image URL…">
                    </div>
                </div>

                <div>
                    <label for="edit-ph-portfolio" class="wt-label">Portfolio Website</label>
                    <input id="edit-ph-portfolio" name="portfolio_url" type="url" class="wt-input" placeholder="https://portfolio.com">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-ph-wa" class="wt-label"><span class="text-[#25D366]">●</span> WhatsApp Link</label>
                        <input id="edit-ph-wa" name="whatsapp_link" type="url" class="wt-input" placeholder="https://wa.me/628…">
                    </div>
                    <div>
                        <label for="edit-ph-ig" class="wt-label"><span style="color:#dc2743">●</span> Instagram Link</label>
                        <input id="edit-ph-ig" name="instagram_link" type="url" class="wt-input" placeholder="https://instagram.com/handle">
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 rounded-xl border border-[#e5e7eb] bg-[#f9f9ff]">
                    <input id="edit-ph-active" name="is_active" type="checkbox" value="1"
                           class="w-4 h-4 mt-0.5 accent-[#43664c] cursor-pointer">
                    <div>
                        <label for="edit-ph-active" class="text-[14px] font-semibold text-[#141b2b] cursor-pointer">Active Listing</label>
                        <p class="text-[12px] text-[#727971] mt-0.5">Inactive photographers are hidden from the public directory.</p>
                    </div>
                </div>

                <div>
                    <label for="edit-ph-paid" class="wt-label">Featured Until (Paid Until)</label>
                    <input id="edit-ph-paid" name="paid_until" type="date" class="wt-input sm:w-56">
                    <p class="text-[11px] text-[#727971] mt-1">Leave blank for free listing. Set a future date for a paid ⭐ slot.</p>
                </div>

                {{-- Pricing Packages --}}
                <div class="border border-[#e5e7eb] rounded-xl p-4 space-y-4 bg-[#f9f9ff]">
                    <p class="text-[12px] font-bold text-[#141b2b] uppercase tracking-wide">💰 Pricing Packages</p>
                    @foreach(['basic' => 'Basic', 'standard' => 'Standard', 'premium' => 'Premium', 'fullday' => 'Full Day Event'] as $key => $label)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pb-3 border-b border-[#f1f3ff] last:border-0 last:pb-0">
                        <div>
                            <label class="wt-label">{{ $label }} — Price</label>
                            <input id="edit-pkg-{{ $key }}-price" name="packages[{{ $key }}][price]" type="text"
                                   placeholder="e.g. Rp150k – 300k" class="wt-input">
                        </div>
                        <div>
                            <label class="wt-label">{{ $label }} — Details</label>
                            <input id="edit-pkg-{{ $key }}-desc" name="packages[{{ $key }}][description]" type="text"
                                   placeholder="e.g. 1 jam, 30–50 foto, Google Drive" class="wt-input">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="wt-modal-footer">
                    <button type="button" onclick="closeModal('dir-edit-modal')" class="wt-btn-cancel">Cancel</button>
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-opacity hover:opacity-90"
                            style="background:#43664c">
                        <span class="material-symbols-outlined" style="font-size:18px">save</span>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div id="dir-delete-modal" class="wt-modal-backdrop" onclick="handleBackdropClick(event,'dir-delete-modal')" aria-hidden="true">
        <div class="wt-modal-box max-w-sm" role="dialog" aria-modal="true" aria-labelledby="dir-delete-title">
            <div class="wt-modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-red-50">
                        <span class="material-symbols-outlined" style="color:#dc2626;font-size:20px">delete</span>
                    </div>
                    <h2 id="dir-delete-title" class="text-[16px] font-bold text-[#141b2b]">Delete Photographer</h2>
                </div>
                <button type="button" onclick="closeModal('dir-delete-modal')" class="wt-modal-close" aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="px-6 py-5">
                <p class="text-[14px] text-[#424842]">Are you sure you want to remove <strong id="dir-delete-name" class="text-[#141b2b]"></strong> from the directory? This action cannot be undone.</p>
                <form id="dir-delete-form" method="POST" action="" class="flex gap-3 mt-6 justify-end">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeModal('dir-delete-modal')" class="wt-btn-cancel">Cancel</button>
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white bg-red-500 hover:bg-red-600 transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">delete</span>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('head')
    <style>
        /* ── Shared Modal Styles ─────────────────────────────── */
        .wt-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(20, 27, 43, 0.55);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .wt-modal-backdrop.open {
            display: flex;
            animation: wtModalFadeIn 0.2s ease forwards;
        }
        @keyframes wtModalFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .wt-modal-box {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 24px 64px -12px rgba(0,0,0,0.25);
            animation: wtModalSlideIn 0.25s cubic-bezier(0.34,1.56,0.64,1) forwards;
        }
        @keyframes wtModalSlideIn {
            from { transform: translateY(20px) scale(0.97); opacity: 0; }
            to   { transform: translateY(0) scale(1);       opacity: 1; }
        }
        .wt-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f1f3ff;
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 1;
            border-radius: 20px 20px 0 0;
        }
        .wt-modal-close {
            padding: 6px;
            border-radius: 10px;
            color: #727971;
            transition: background 0.15s, color 0.15s;
            display: flex;
            align-items: center;
        }
        .wt-modal-close:hover { background: #f1f3ff; color: #141b2b; }
        .wt-modal-body {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .wt-modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid #f1f3ff;
        }
        .wt-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #424842;
            margin-bottom: 6px;
        }
        .wt-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #c2c8c0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s;
            background: #fff;
        }
        .wt-input:focus { border-color: #7ba082; box-shadow: 0 0 0 3px rgba(123,160,130,0.15); }
        .wt-btn-cancel {
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            color: #424842;
            background: #fff;
            transition: border-color 0.15s, color 0.15s;
        }
        .wt-btn-cancel:hover { border-color: #7ba082; color: #43664c; }
    </style>
    @endpush

    @push('scripts')
    <script>
        /* ── Modal helpers ──────────────────────────────────── */
        function openModal(id) {
            const el = document.getElementById(id);
            el.classList.add('open');
            el.removeAttribute('aria-hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('open');
            el.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        function handleBackdropClick(e, id) {
            if (e.target === document.getElementById(id)) closeModal(id);
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                ['dir-create-modal','dir-edit-modal','dir-delete-modal'].forEach(closeModal);
            }
        });

        /* ── Open create modal if there are validation errors ── */
        @if($errors->any())
            openModal('dir-create-modal');
        @endif

        /* ── Edit Modal ─────────────────────────────────────── */
        function openEditModal(id, name, specialty, avatar, portfolio, wa, ig, isActive, paidUntil, pricingPackagesJson) {
            const baseUrl = '{{ url("admin/directory") }}/' + id;
            document.getElementById('dir-edit-form').action = baseUrl;
            document.getElementById('edit-ph-name').value      = name;
            document.getElementById('edit-ph-avatar').value    = avatar    || '';
            document.getElementById('edit-ph-portfolio').value = portfolio || '';
            document.getElementById('edit-ph-wa').value        = wa        || '';
            document.getElementById('edit-ph-ig').value        = ig        || '';
            document.getElementById('edit-ph-active').checked  = isActive;
            document.getElementById('edit-ph-paid').value      = paidUntil || '';

            const sel = document.getElementById('edit-ph-specialty');
            for (let opt of sel.options) { opt.selected = (opt.value === specialty); }

            // Pre-fill pricing packages
            let pkgs = {};
            try { pkgs = JSON.parse(pricingPackagesJson || '{}') || {}; } catch(e) {}
            ['basic','standard','premium','fullday'].forEach(tier => {
                const p = pkgs[tier] || {};
                const priceEl = document.getElementById('edit-pkg-' + tier + '-price');
                const descEl  = document.getElementById('edit-pkg-' + tier + '-desc');
                if (priceEl) priceEl.value = p.price || '';
                if (descEl)  descEl.value  = p.description || '';
            });

            openModal('dir-edit-modal');
        }

        /* ── Delete Modal ───────────────────────────────────── */
        function deletePhotographer(id, name) {
            const baseUrl = '{{ url("admin/directory") }}/' + id;
            document.getElementById('dir-delete-form').action = baseUrl;
            document.getElementById('dir-delete-name').textContent = name;
            openModal('dir-delete-modal');
        }

        /* ── Table search ───────────────────────────────────── */
        function filterDir(q) {
            document.querySelectorAll('.dir-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
            });
        }
    </script>
    @endpush
</x-app-layout>
