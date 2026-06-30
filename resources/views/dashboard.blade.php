<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- HERO SECTION — Clean Light Minimalist with Sage Accent         --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-b from-[#eef3ef] to-[#f9fafb] px-8 py-16 lg:px-12 lg:py-20">
        <div class="max-w-4xl flex flex-col items-start">

            {{-- Eyebrow badge --}}
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#dcfce7] text-[#166534] text-sm font-medium mb-6">
                <span class="w-2 h-2 rounded-full bg-[#166534] animate-pulse mr-2"></span>
                Samarinda Photo Map — Live
            </div>

            {{-- Main Heading --}}
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 mb-4">
                Welcome to <span class="text-[#5c8065]">Walkture.</span>
            </h1>

            {{-- Sub Heading --}}
            <p class="text-lg text-gray-600 mb-8 max-w-2xl leading-relaxed">
                Discover, capture, and explore the hidden aesthetics of Samarinda.
                Find your perfect spot and connect with local creators.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-wrap items-center gap-4">
                {{-- Primary: Explore Map --}}
                <a href="{{ route('map') }}"
                   id="hero-cta-map"
                   class="bg-[#7ba082] hover:bg-[#64846a] text-white px-6 py-3 rounded-xl font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                        <line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>
                    </svg>
                    Explore Map
                </a>


            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- BODY CONTENT                                                    --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="page-body space-y-6">

        {{-- ─── Row 1: Balance + Level ─────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Balance Card --}}
            <div class="card lg:col-span-2">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <p class="card-label">Current Balance</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-3xl font-bold text-neutral-900">100</span>
                            <span class="text-lg font-semibold text-neutral-400">Credits</span>
                            <span class="pill-premium">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Premium
                            </span>
                        </div>
                        <p class="text-xs text-neutral-400 mt-2">Credits refresh monthly with your Premium plan.</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('user.photographers') }}" id="btn-find-photographer" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/>
                            </svg>
                            Find Photographer
                        </a>
                        <a href="{{ route('user.map') }}" id="btn-explore-map" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>
                            </svg>
                            Explore Map
                        </a>
                    </div>
                </div>
                <div class="mt-5 grid grid-cols-2 gap-4 pt-5 border-t border-neutral-100">
                    <div>
                        <p class="text-xs text-neutral-400">Spots Unlocked</p>
                        <p class="text-lg font-bold text-neutral-800 mt-0.5">8</p>
                    </div>
                    <div>
                        <p class="text-xs text-neutral-400">Assets Purchased</p>
                        <p class="text-lg font-bold text-neutral-800 mt-0.5">12</p>
                    </div>
                </div>
            </div>

            {{-- Photography Level --}}
            <div class="card">
                <p class="card-label">Photography Level</p>
                <div class="mt-3 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-sage-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-sage-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <circle cx="12" cy="13" r="3"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-neutral-900">Enthusiast</p>
                        <p class="text-xs text-neutral-400">Level 3 of 5</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-neutral-400 mb-1.5">
                        <span>XP Progress</span>
                        <span class="font-semibold text-sage-600">680 / 1000</span>
                    </div>
                    <div class="h-2 bg-neutral-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-sage-400 to-sage-300 rounded-full transition-all duration-700" style="width: 68%"></div>
                    </div>
                    <p class="text-xs text-neutral-400 mt-2">320 XP to reach <span class="font-semibold text-neutral-600">Advanced</span></p>
                </div>
                <div class="mt-4 pt-4 border-t border-neutral-100 grid grid-cols-2 gap-2">
                    <div class="bg-neutral-50 rounded-xl p-2.5 text-center">
                        <p class="text-base font-bold text-neutral-800">42</p>
                        <p class="text-[10px] text-neutral-400">Photos Tagged</p>
                    </div>
                    <div class="bg-neutral-50 rounded-xl p-2.5 text-center">
                        <p class="text-base font-bold text-neutral-800">8</p>
                        <p class="text-[10px] text-neutral-400">Walks Completed</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Row 2: Local Photographers Directory (Etalase) ─── --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="section-title">Fotografer Lokal Samarinda</h2>
                    <p class="section-sub">Hubungi langsung untuk booking sesi foto</p>
                </div>
                <a href="{{ route('map') }}" class="btn-ghost" id="view-all-photographers">Lihat di Peta</a>
            </div>
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                @foreach($photographers as $i => $photographer)
                <div class="flex-shrink-0 w-56 bg-neutral-50 rounded-2xl p-4 hover:shadow-card-hover transition-all duration-200 border border-neutral-100 group" id="photographer-card-{{ $i }}">
                    <div class="flex items-center gap-3 mb-2.5">
                        <img src="{{ $photographer['avatar'] }}" alt="{{ $photographer['name'] }}"
                             class="w-11 h-11 rounded-full object-cover ring-2 ring-sage-100 group-hover:ring-sage-300 transition-all">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-neutral-800 truncate">{{ $photographer['name'] }}</p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-semibold text-neutral-600">{{ $photographer['rating'] }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="pill-sage text-[10px] mb-2.5 inline-block">{{ $photographer['specialty'] }}</span>
                    <p class="text-[11px] text-neutral-400 leading-relaxed mb-3 line-clamp-2">{{ $photographer['bio'] }}</p>

                    {{-- CTA: WhatsApp & Instagram --}}
                    <div class="grid grid-cols-2 gap-1.5">
                        <a href="{{ $photographer['whatsapp_link'] }}"
                           target="_blank" rel="noopener noreferrer"
                           id="wa-btn-{{ $i }}"
                           class="flex items-center justify-center gap-1.5 py-2 px-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-[11px] font-bold transition-colors duration-150">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                        <a href="{{ $photographer['instagram_link'] }}"
                           target="_blank" rel="noopener noreferrer"
                           id="ig-btn-{{ $i }}"
                           class="flex items-center justify-center gap-1.5 py-2 px-2 bg-pink-50 hover:bg-pink-100 text-pink-700 rounded-lg text-[11px] font-bold transition-colors duration-150">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                            Instagram
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ─── Row 3: Premium Spots ──────────────────────────── --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="section-title">Spot Foto Unggulan</h2>
                    <p class="section-sub">Lokasi eksklusif di Kota Samarinda</p>
                </div>
                <a href="{{ route('map') }}" class="btn-ghost" id="view-all-spots">Lihat di Peta</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($premiumSpots as $i => $spot)
                <a href="{{ route('map') }}" class="relative rounded-2xl overflow-hidden aspect-[4/3] group cursor-pointer block" id="spot-card-{{ $i }}">
                    <img src="{{ $spot['image'] }}" alt="{{ $spot['name'] }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @if($spot['locked'])
                    <div class="locked-overlay">
                        <div class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mb-2">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-center px-2">{{ $spot['name'] }}</p>
                        <p class="text-[10px] text-white/70 mt-0.5">Premium Only</p>
                    </div>
                    @else
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/70 via-transparent to-transparent rounded-2xl group-hover:from-neutral-900/80 transition-all duration-300">
                        <div class="absolute bottom-0 left-0 right-0 p-3">
                            <p class="text-xs font-bold text-white">{{ $spot['name'] }}</p>
                        </div>
                    </div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
