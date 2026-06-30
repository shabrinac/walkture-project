<x-app-layout>
    <x-slot name="title">Photographer Directory</x-slot>
    @section('page-title', 'Photographer Directory')

    <div class="px-6 py-8 max-w-6xl mx-auto">

        {{-- ── Hero Banner ─────────────────────────────────────────────── --}}
        <div class="rounded-2xl overflow-hidden mb-8 relative"
             style="background: linear-gradient(135deg, #2d4a35 0%, #7ba082 100%); min-height: 140px;">
            <div class="relative z-10 px-8 py-7 flex flex-col sm:flex-row sm:items-center gap-6 justify-between">
                <div>
                    <p class="text-[#a9d0b0] text-xs font-bold uppercase tracking-widest mb-1">Walkture Directory</p>
                    <h1 class="text-2xl font-bold text-white tracking-tight leading-snug">Photographer Directory</h1>
                    <p class="text-[#c5eccb] text-[14px] mt-1 max-w-md">Connect with talented local photographers in Samarinda.</p>
                </div>
                {{-- Specialty filter pills --}}
                <div class="flex gap-2 flex-wrap shrink-0" id="filter-buttons">
                    <button id="ph-filter-all" onclick="filterPhotographers('all')"
                            class="ph-filter-btn px-4 py-1.5 rounded-full text-[12px] font-bold border-2 border-white/60 text-white bg-white/20 hover:bg-white/30 transition-colors">
                        All
                    </button>
                    @foreach($specialties as $spec)
                    <button id="ph-filter-{{ Str::slug($spec) }}" onclick="filterPhotographers('{{ $spec }}')"
                            data-specialty="{{ $spec }}"
                            class="ph-filter-btn px-4 py-1.5 rounded-full text-[12px] font-semibold border-2 border-white/30 text-white/70 bg-transparent hover:bg-white/20 hover:text-white hover:border-white/60 transition-colors">
                        {{ $spec }}
                    </button>
                    @endforeach
                </div>
            </div>
            {{-- Decorative circle --}}
            <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-10 pointer-events-none"
                 style="background:#ffffff;transform:translate(30%,-30%)"></div>
        </div>

        @if($photographers->isEmpty())
            <div class="bg-white border border-[#e5e7eb] rounded-2xl p-16 text-center">
                <span class="material-symbols-outlined text-[#c2c8c0]" style="font-size:56px">no_photography</span>
                <p class="text-[#727971] mt-3 font-medium">No photographers listed yet.</p>
            </div>
        @else
        {{-- ── Grid ─────────────────────────────────────────────────────── --}}
        <div id="photographers-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($photographers as $photographer)

            {{-- Entire card is clickable via onclick. Social links use stopPropagation()
                 so they open their own targets without triggering the card navigation. --}}
            <div onclick="window.location='{{ route('user.photographers.show', $photographer->id) }}'"
                 class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden flex flex-col cursor-pointer
                         shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]
                         hover:shadow-[0_8px_30px_-4px_rgba(67,102,76,0.18)] hover:border-[#7ba082]
                         hover:-translate-y-1 transition-all duration-200 group"
                 id="dir-photographer-{{ $photographer->id }}" data-specialty="{{ $photographer->specialty }}">

                {{-- Featured badge row --}}
                <div class="flex justify-end px-4 pt-3 min-h-[28px]">
                    @if($photographer->paid_until && $photographer->paid_until >= now())
                        <span class="text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-2.5 py-1 rounded-full">⭐ Featured</span>
                    @endif
                </div>

                {{-- Avatar area --}}
                <div class="flex flex-col items-center px-5 pb-4">
                    @if($photographer->avatar_url)
                        <img src="{{ $photographer->avatar_url }}" alt="{{ $photographer->full_name }}"
                             class="w-20 h-20 rounded-full object-cover border-4 border-[#f0f8f2] shadow-sm">
                    @else
                        <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-[#f0f8f2] shadow-sm"
                             style="background: linear-gradient(135deg, #43664c, #7ba082)">
                            {{ strtoupper(substr($photographer->full_name, 0, 1)) }}
                        </div>
                    @endif

                    <h2 class="text-[15px] font-bold text-gray-900 mt-3 leading-tight text-center">{{ $photographer->full_name }}</h2>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1 mb-1">{{ $photographer->specialty }}</p>
                </div>

                {{-- Divider --}}
                <div class="mx-5 border-t border-[#f1f3ff]"></div>

                {{-- Action buttons — pushed to bottom --}}
                <div class="flex flex-col gap-2 px-5 py-4 mt-auto">

                    {{-- Social buttons. stopPropagation prevents card onclick from firing. --}}
                    <div class="flex gap-2 w-full">
                        @if($photographer->whatsapp_link)
                        <a href="{{ $photographer->whatsapp_link }}" target="_blank" rel="noopener"
                           onclick="event.stopPropagation()"
                           id="dir-wa-{{ $photographer->id }}"
                           class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-[12px] font-bold text-white transition-opacity hover:opacity-90"
                           style="background:#25D366">
                            <span class="material-symbols-outlined" style="font-size:14px">chat</span>
                            WhatsApp
                        </a>
                        @endif
                        @if($photographer->instagram_link)
                        <a href="{{ $photographer->instagram_link }}" target="_blank" rel="noopener"
                           onclick="event.stopPropagation()"
                           id="dir-ig-{{ $photographer->id }}"
                           class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-[12px] font-bold text-white transition-opacity hover:opacity-90"
                           style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366)">
                            <span class="material-symbols-outlined" style="font-size:14px">photo_camera</span>
                            Instagram
                        </a>
                        @endif
                    </div>

                    {{-- View Full Profile affordance — navigation handled by parent div onclick --}}
                    <span class="flex items-center justify-center gap-1 text-[12px] font-semibold text-gray-500 group-hover:text-[#7ba082] transition-colors mt-1">
                        View Full Profile
                        <span class="material-symbols-outlined" style="font-size:14px">arrow_forward</span>
                    </span>

                </div>
            </div>

            @endforeach
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function filterPhotographers(spec) {
            document.querySelectorAll('#photographers-grid [data-specialty]').forEach(el => {
                el.style.display = (spec === 'all' || el.dataset.specialty === spec) ? '' : 'none';
            });

            document.querySelectorAll('.ph-filter-btn').forEach(btn => {
                const isAll   = spec === 'all' && btn.id === 'ph-filter-all';
                const isMatch = btn.dataset.specialty === spec;
                const active  = isAll || isMatch;

                if (active) {
                    btn.style.background  = 'rgba(255,255,255,0.3)';
                    btn.style.color       = '#ffffff';
                    btn.style.borderColor = 'rgba(255,255,255,0.7)';
                    btn.style.fontWeight  = '700';
                } else {
                    btn.style.background  = 'transparent';
                    btn.style.color       = 'rgba(255,255,255,0.65)';
                    btn.style.borderColor = 'rgba(255,255,255,0.3)';
                    btn.style.fontWeight  = '600';
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
