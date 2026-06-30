<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    @section('page-title', 'Dashboard')

    <div class="px-6 py-8 max-w-6xl mx-auto">

        {{-- ── Welcome Hero ───────────────────────────────────────── --}}
        <div class="rounded-2xl overflow-hidden mb-8 relative"
             style="background: linear-gradient(135deg, #43664c 0%, #7ba082 100%); min-height: 180px;">
            <div class="relative z-10 p-8">
                <p class="text-[#a9d0b0] text-sm font-semibold uppercase tracking-widest mb-2">Photo Spots · GIS · Samarinda</p>
                <h1 class="text-3xl font-bold text-white mb-1 tracking-tight leading-tight">
                    Welcome to Walkture.
                </h1>
                <p class="text-[#c5eccb] text-lg font-light italic mb-3">Step into curated tranquility.</p>
                <p class="text-[#c5eccb] text-[15px] max-w-lg leading-relaxed">
                    Explore sponsored photo spots, discover local photographers, and find the perfect gear for your next shoot in Samarinda.
                </p>
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('user.map') }}" id="hero-btn-map"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-[#43664c] bg-white hover:bg-[#f1f3ff] transition-colors shadow-sm">
                        <span class="material-symbols-outlined" style="font-size:18px">map</span>
                        Open Map
                    </a>
                    <a href="{{ route('user.photographers') }}" id="hero-btn-photographers"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white border-2 border-white/40 hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">photo_camera</span>
                        Browse Photographers
                    </a>
                </div>
            </div>
            {{-- Decorative circles --}}
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10" style="background:#ffffff;transform:translate(30%,-30%)"></div>
            <div class="absolute bottom-0 right-20 w-32 h-32 rounded-full opacity-10" style="background:#ffffff;transform:translateY(40%)"></div>
        </div>

        {{-- ── Quick Stats Row ─────────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            @php
                $quickStats = [
                    ['icon'=>'location_on', 'label'=>'Photo Spots', 'val'=>'Explore', 'route'=>'user.map'],
                    ['icon'=>'camera', 'label'=>'Photographers', 'val'=>'Directory', 'route'=>'user.photographers'],
                    ['icon'=>'support_agent', 'label'=>'Contact Support', 'val'=>'Get Help', 'route'=>'guest.contact'],
                    ['icon'=>'camera_alt', 'label'=>'Book a Photographer', 'val'=>'Browse', 'route'=>'user.photographers'],
                ];
            @endphp
            @foreach($quickStats as $stat)
            <a href="{{ route($stat['route']) }}" id="quick-stat-{{ $loop->index }}"
               class="bg-white border border-[#e5e7eb] rounded-2xl p-4 flex flex-col gap-3 hover:shadow-md hover:border-[#7ba082] transition-all group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#f1f3ff">
                    <span class="material-symbols-outlined text-[#43664c]">{{ $stat['icon'] }}</span>
                </div>
                <div>
                    <p class="text-xs text-[#727971] font-medium">{{ $stat['label'] }}</p>
                    <p class="text-[13px] font-semibold text-[#43664c] group-hover:underline">{{ $stat['val'] }} →</p>
                </div>
            </a>
            @endforeach
        </div>

        {{-- ── Sponsored Places ─────────────────────────────────────── --}}
        <div class="mb-10">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl font-bold text-[#141b2b] tracking-tight">Sponsored Places</h2>
                    <p class="text-sm text-[#727971] mt-0.5">Featured photo spots in Samarinda</p>
                </div>
                <a href="{{ route('user.map') }}" id="dashboard-see-all-spots"
                   class="text-[13px] font-semibold text-[#43664c] hover:underline flex items-center gap-1">
                    See all on map
                    <span class="material-symbols-outlined" style="font-size:16px">arrow_forward</span>
                </a>
            </div>

            @if($sponsoredSpots->isEmpty())
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-10 text-center">
                    <span class="material-symbols-outlined text-[#c2c8c0]" style="font-size:48px">location_off</span>
                    <p class="text-[#727971] mt-3 text-sm">No sponsored spots yet. Check back soon!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($sponsoredSpots as $spot)
                    <a href="{{ route('user.spots.show', $spot->id) }}" id="spot-card-{{ $spot->id }}"
                       class="polaroid group cursor-pointer block hover:no-underline">
                        {{-- Spot image or placeholder --}}
                        @php $spotImg = $spot->display_image; @endphp
                        @if($spotImg)
                            <img src="{{ $spotImg }}" alt="{{ $spot->name }}"
                                 class="w-full h-44 object-cover rounded-sm">
                        @else
                            <div class="w-full h-44 rounded-sm flex items-center justify-center"
                                 style="background: linear-gradient(135deg,#e9edff,#c5eccb)">
                                <span class="material-symbols-outlined text-[#7ba082]" style="font-size:40px">photo_camera</span>
                            </div>
                        @endif
                        <div class="mt-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-[13px] font-bold text-[#141b2b]">{{ $spot->name }}</p>
                                    <p class="text-[11px] text-[#727971] mt-0.5 uppercase tracking-wide">{{ $spot->category }}</p>
                                </div>
                                <span class="flex-shrink-0 text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-2 py-0.5 rounded-full">
                                    Sponsored
                                </span>
                            </div>
                            @if($spot->promo_detail)
                                <p class="text-[12px] text-[#424842] mt-1.5 leading-relaxed line-clamp-2">{{ $spot->promo_detail }}</p>
                            @endif
                            <div class="inline-flex items-center gap-1 mt-3 text-[12px] font-semibold text-[#43664c] group-hover:underline">
                                <span class="material-symbols-outlined" style="font-size:14px">near_me</span>
                                View Details
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Featured Photographers ───────────────────────────────── --}}
        <div class="mb-10">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl font-bold text-[#141b2b] tracking-tight">Featured Photographers</h2>
                    <p class="text-sm text-[#727971] mt-0.5">Discover talented photographers in Samarinda</p>
                </div>
                <a href="{{ route('user.photographers') }}" id="dashboard-see-all-photographers"
                   class="text-[13px] font-semibold text-[#43664c] hover:underline flex items-center gap-1">
                    Full directory
                    <span class="material-symbols-outlined" style="font-size:16px">arrow_forward</span>
                </a>
            </div>

            @if($featuredPhotographers->isEmpty())
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-10 text-center">
                    <span class="material-symbols-outlined text-[#c2c8c0]" style="font-size:48px">no_photography</span>
                    <p class="text-[#727971] mt-3 text-sm">No photographers listed yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($featuredPhotographers as $photographer)
                    <div onclick="window.location='{{ route('user.photographers.show', $photographer->id) }}'"
                         id="photographer-card-{{ $photographer->id }}"
                         class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col items-center text-center transition-transform hover:-translate-y-1 cursor-pointer h-full justify-between">

                        <div class="flex flex-col items-center w-full">
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">Featured</span>
                            <img src="{{ $photographer->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ $photographer->name ?? $photographer->full_name }}"
                                 class="w-20 h-20 rounded-full object-cover mb-4">
                            <h3 class="text-base font-bold text-gray-900">{{ $photographer->name ?? $photographer->full_name }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-1 mb-5">{{ $photographer->specialty }}</p>
                        </div>

                        <div class="flex gap-2 w-full mt-auto">
                            <a href="{{ $photographer->whatsapp_link }}" onclick="event.stopPropagation();" target="_blank"
                               id="photographer-wa-{{ $photographer->id }}"
                               class="flex-1 bg-[#25D366] text-white py-2 rounded-lg text-[12px] font-semibold text-center hover:bg-[#20bd5a]">WhatsApp</a>
                            <a href="{{ $photographer->instagram_link }}" onclick="event.stopPropagation();" target="_blank"
                               id="photographer-ig-{{ $photographer->id }}"
                               class="flex-1 bg-gradient-to-tr from-[#fd5949] to-[#d6249f] text-white py-2 rounded-lg text-[12px] font-semibold text-center hover:opacity-90">Instagram</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
