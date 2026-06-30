<x-app-layout>
    <x-slot name="title">{{ $spot->name }} — Spot Details</x-slot>
    @section('page-title', $spot->name)

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #user-spot-map { height: 380px; border-radius: 16px; overflow: hidden; border: 1.5px solid #e5e7eb; }
        .leaflet-popup-content-wrapper { border-radius: 12px !important; font-family: 'Inter', sans-serif; }
    </style>
    @endpush

    <div class="px-6 py-8 max-w-4xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('user.map') }}" class="hover:text-[#43664c] font-semibold transition-colors">Map</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold truncate">{{ $spot->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Main Content ──────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Hero Image --}}
                @php $heroImg = $spot->display_image; @endphp
                @if($heroImg)
                    <div class="rounded-2xl overflow-hidden shadow-sm">
                        <img src="{{ $heroImg }}" alt="{{ $spot->name }}" class="w-full h-64 object-cover">
                    </div>
                @else
                    <div class="rounded-2xl overflow-hidden h-64 flex items-center justify-center"
                         style="background:linear-gradient(135deg,#43664c,#7ba082)">
                        <span class="material-symbols-outlined text-white/60" style="font-size:64px">location_on</span>
                    </div>
                @endif

                {{-- Spot Info Card --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:{{ $spot->is_sponsored ? '#c5eccb' : '#f1f3ff' }}">
                            <span class="material-symbols-outlined" style="color:{{ $spot->is_sponsored ? '#43664c' : '#727971' }}">location_on</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-2xl font-bold text-[#141b2b] tracking-tight">{{ $spot->name }}</h1>
                                @if($spot->is_sponsored)
                                    <span class="text-[11px] font-bold bg-[#c5eccb] text-[#43664c] px-2 py-0.5 rounded-full">★ Sponsored</span>
                                @endif
                            </div>
                            <p class="text-[13px] text-[#727971] mt-1 uppercase tracking-widest font-semibold">{{ $spot->category }}</p>
                        </div>
                    </div>

                    @if($spot->description)
                    <div class="mb-5">
                        <p class="text-[13px] font-bold text-[#424842] uppercase tracking-wide mb-2">About This Spot</p>
                        <p class="text-[14px] text-[#424842] leading-relaxed">{{ $spot->description }}</p>
                    </div>
                    @endif

                    @if($spot->promo_detail)
                    <div class="bg-[#f0faf2] border border-[#7ba082] rounded-xl p-4 mb-5">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:18px">workspace_premium</span>
                            <p class="text-[12px] font-bold text-[#43664c] uppercase tracking-wide">Promo</p>
                        </div>
                        <p class="text-[13px] text-[#43664c] leading-relaxed">{{ $spot->promo_detail }}</p>
                    </div>
                    @endif

                    @if($spot->nearest_route)
                    <div class="bg-[#f9f9ff] border border-[#e5e7eb] rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:18px">route</span>
                            <p class="text-[12px] font-bold text-[#424842] uppercase tracking-wide">Route to Nearest Spot</p>
                        </div>
                        <p class="text-[13px] text-[#424842] leading-relaxed">{{ $spot->nearest_route }}</p>
                    </div>
                    @endif
                </div>

                {{-- ── Interactive Map ────────────────────────── --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[#43664c]">map</span>
                        <h2 class="text-[15px] font-bold text-[#141b2b]">Location Map</h2>
                        @if($spot->polygon_geojson || $spot->route_geojson)
                        <div class="flex gap-2 ml-auto">
                            @if($spot->polygon_geojson)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#e8f5e9;color:#2e7d32">
                                    <span style="display:inline-block;width:8px;height:8px;background:#7ba082;border-radius:2px"></span> Zone
                                </span>
                            @endif
                            @if($spot->route_geojson)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#e3f2fd;color:#1565c0">
                                    <span style="display:inline-block;width:12px;height:3px;background:#3b82f6;border-radius:2px"></span> Route
                                </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div id="user-spot-map"></div>
                </div>

            </div>

            {{-- ── Sidebar ─────────────────────────────────── --}}
            <div class="space-y-4">

                {{-- Location --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-5">
                    <p class="text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-3">Location</p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:16px">my_location</span>
                            <span class="text-[13px] font-mono text-[#424842]">{{ number_format($spot->latitude, 6) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:16px">explore</span>
                            <span class="text-[13px] font-mono text-[#424842]">{{ number_format($spot->longitude, 6) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('user.map') }}" id="spot-view-on-map"
                       class="flex items-center justify-center gap-2 mt-4 py-2.5 rounded-xl text-[13px] font-bold text-white w-full"
                       style="background:#43664c">
                        <span class="material-symbols-outlined" style="font-size:16px">map</span>
                        View on Map
                    </a>
                </div>

                {{-- Explore More --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-5">
                    <p class="text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-3">Explore More</p>
                    <div class="space-y-2">
                        <a href="{{ route('user.photographers') }}" id="spot-find-photographer"
                           class="flex items-center gap-2 py-2 text-[13px] text-[#424842] hover:text-[#43664c] transition-colors font-semibold">
                            <span class="material-symbols-outlined" style="font-size:18px">photo_camera</span>
                            Find a Photographer
                        </a>
                        <a href="{{ route('user.map') }}" id="spot-back-to-map"
                           class="flex items-center gap-2 py-2 text-[13px] text-[#424842] hover:text-[#43664c] transition-colors font-semibold">
                            <span class="material-symbols-outlined" style="font-size:18px">map</span>
                            Back to Map
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-8">
            <a href="{{ route('user.dashboard') }}" id="spot-back-btn"
               class="inline-flex items-center gap-2 text-[13px] font-semibold text-[#727971] hover:text-[#43664c] transition-colors">
                <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span>
                Back to Dashboard
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function () {
        const lat = {{ $spot->latitude }};
        const lng = {{ $spot->longitude }};
        const polygonData = @json($spot->polygon_geojson);
        const routeData   = @json($spot->route_geojson);

        const map = L.map('user-spot-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com">CARTO</a>',
            maxZoom: 19
        }).addTo(map);

        const allBounds = [];

        // ── Main marker ──────────────────────────────────────────
        const pinIcon = L.divIcon({
            className: '',
            html: `<div style="width:22px;height:22px;background:#43664c;border:3px solid #fff;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 2px 10px rgba(0,0,0,.3)">
                       <div style="width:7px;height:7px;background:#fff;border-radius:50%;position:absolute;top:4px;left:4px;transform:rotate(45deg)"></div>
                   </div>`,
            iconSize: [22, 22], iconAnchor: [11, 22], popupAnchor: [0, -24]
        });
        L.marker([lat, lng], { icon: pinIcon })
            .addTo(map)
            .bindPopup(`<div style="font-family:'Inter',sans-serif;min-width:160px">
                <b style="font-size:13px;color:#141b2b">${@json($spot->name)}</b><br>
                <small style="color:#727971;text-transform:uppercase;font-size:10px;letter-spacing:.05em">${@json($spot->category)}</small>
            </div>`);
        allBounds.push([lat, lng]);

        // ── Zone Polygon ─────────────────────────────────────────
        if (polygonData) {
            try {
                const geojson = typeof polygonData === 'string' ? JSON.parse(polygonData) : polygonData;
                const layer = L.geoJSON(geojson, {
                    style: { color: '#43664c', weight: 2, opacity: 0.85, fillColor: '#7ba082', fillOpacity: 0.2 }
                }).addTo(map).bindPopup('<b style="font-size:12px;color:#2e7d32">📐 Zone Area</b>');
                try { const b = layer.getBounds(); allBounds.push(b.getNorthEast(), b.getSouthWest()); } catch(e){}
            } catch(e) { console.warn('polygon error', e); }
        }

        // ── Route Polyline ───────────────────────────────────────
        if (routeData) {
            try {
                const geojson = typeof routeData === 'string' ? JSON.parse(routeData) : routeData;
                const layer = L.geoJSON(geojson, {
                    style: { color: '#3b82f6', weight: 3, opacity: 0.8, dashArray: '8 5' }
                }).addTo(map).bindPopup('<b style="font-size:12px;color:#1565c0">🛣️ Walking Route</b>');
                try { const b = layer.getBounds(); allBounds.push(b.getNorthEast(), b.getSouthWest()); } catch(e){}
            } catch(e) { console.warn('route error', e); }
        }

        // ── fitBounds ────────────────────────────────────────────
        if (allBounds.length > 1) {
            try {
                map.fitBounds(L.latLngBounds(allBounds), { padding: [28, 28], maxZoom: 17 });
            } catch(e) { map.setView([lat, lng], 15); }
        }
    })();
    </script>
    @endpush
</x-app-layout>
