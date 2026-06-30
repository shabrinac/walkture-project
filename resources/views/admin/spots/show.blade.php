<x-app-layout>
    <x-slot name="title">{{ $spot->name }} — Spot Details (Admin)</x-slot>
    @section('page-title', $spot->name)

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #admin-spot-map { height: 420px; border-radius: 16px; overflow: hidden; border: 1.5px solid #e5e7eb; }
        .leaflet-popup-content-wrapper { border-radius: 12px !important; font-family: 'Inter', sans-serif; }
        .geom-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700;
                      padding: 3px 8px; border-radius: 999px; }
    </style>
    @endpush

    <div class="px-6 py-8 max-w-5xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('admin.spatial-data') }}" class="hover:text-[#43664c] font-semibold transition-colors">Spatial Data</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold truncate">{{ $spot->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Main Column ──────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Hero Image --}}
                @php $heroImg = $spot->display_image; @endphp
                @if($heroImg)
                    <div class="rounded-2xl overflow-hidden shadow-sm">
                        <img src="{{ $heroImg }}" alt="{{ $spot->name }}" class="w-full h-56 object-cover">
                    </div>
                @else
                    <div class="rounded-2xl h-44 flex items-center justify-center"
                         style="background:linear-gradient(135deg,#43664c,#7ba082)">
                        <span class="material-symbols-outlined text-white/40" style="font-size:64px">location_on</span>
                    </div>
                @endif

                {{-- Info Card --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-5 flex-wrap">
                        <h1 class="text-2xl font-bold text-[#141b2b]">{{ $spot->name }}</h1>
                        @if($spot->is_sponsored)
                            <span class="text-[11px] font-bold bg-[#c5eccb] text-[#43664c] px-2 py-1 rounded-full">★ Sponsored</span>
                        @endif
                        @if($spot->polygon_geojson)
                            <span class="geom-badge" style="background:#e8f5e9;color:#2e7d32">
                                <span class="material-symbols-outlined" style="font-size:12px">pentagon</span> Zone
                            </span>
                        @endif
                        @if($spot->route_geojson)
                            <span class="geom-badge" style="background:#e3f2fd;color:#1565c0">
                                <span class="material-symbols-outlined" style="font-size:12px">route</span> Route
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Category</p>
                            <p class="text-[14px] font-semibold text-[#141b2b]">{{ $spot->category }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Coordinates</p>
                            <p class="text-[13px] font-mono text-[#424842]">{{ number_format($spot->latitude, 6) }}, {{ number_format($spot->longitude, 6) }}</p>
                        </div>
                    </div>

                    @if($spot->description)
                    <div class="mb-4">
                        <p class="text-[11px] text-[#727971] uppercase tracking-wide mb-1">Description</p>
                        <p class="text-[14px] text-[#424842] leading-relaxed">{{ $spot->description }}</p>
                    </div>
                    @endif

                    @if($spot->promo_detail)
                    <div class="bg-[#f0faf2] border border-[#7ba082] rounded-xl p-4">
                        <p class="text-[11px] text-[#43664c] font-bold uppercase tracking-wide mb-1">Promo</p>
                        <p class="text-[13px] text-[#43664c]">{{ $spot->promo_detail }}</p>
                    </div>
                    @endif
                </div>

                {{-- ── Interactive Geometry Map ─────────────── --}}
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[#43664c]">map</span>
                        <h2 class="text-[15px] font-bold text-[#141b2b]">Spatial Map</h2>
                        <div class="flex gap-2 ml-auto">
                            @if($spot->polygon_geojson)
                                <span class="geom-badge" style="background:#e8f5e9;color:#2e7d32">
                                    <span style="display:inline-block;width:10px;height:10px;background:#7ba082;border-radius:2px"></span> Zone Polygon
                                </span>
                            @endif
                            @if($spot->route_geojson)
                                <span class="geom-badge" style="background:#e3f2fd;color:#1565c0">
                                    <span style="display:inline-block;width:14px;height:3px;background:#3b82f6;border-radius:2px"></span> Route Line
                                </span>
                            @endif
                        </div>
                    </div>
                    <div id="admin-spot-map"></div>
                    @if(!$spot->polygon_geojson && !$spot->route_geojson)
                        <p class="text-[12px] text-[#727971] mt-3 text-center">
                            No zone polygon or route drawn yet. Use the <strong>Edit</strong> button to draw geometry.
                        </p>
                    @endif
                </div>
            </div>

            {{-- ── Sidebar ─────────────────────────────────── --}}
            <div class="space-y-4">
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-5">
                    <p class="text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-3">Admin Actions</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.spots.create') }}"
                           class="flex items-center gap-2 w-full py-2.5 px-4 rounded-xl text-[13px] font-bold text-white"
                           style="background:#43664c">
                            <span class="material-symbols-outlined" style="font-size:16px">add_location_alt</span>
                            Add New Spot
                        </a>
                        <a href="{{ route('admin.spatial-data') }}"
                           class="flex items-center gap-2 w-full py-2.5 px-4 rounded-xl text-[13px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] transition-colors">
                            <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span>
                            Back to Spatial Data
                        </a>
                    </div>
                </div>

                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-5">
                    <p class="text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-3">Metadata</p>
                    <div class="space-y-2 text-[12px] text-[#727971]">
                        <div class="flex justify-between"><span>Spot ID</span><span class="font-mono font-semibold text-[#141b2b]">#{{ $spot->id }}</span></div>
                        <div class="flex justify-between"><span>Created</span><span class="font-semibold text-[#141b2b]">{{ $spot->created_at->format('d M Y') }}</span></div>
                        <div class="flex justify-between"><span>Sponsored</span><span class="font-semibold text-[#141b2b]">{{ $spot->is_sponsored ? 'Yes ★' : 'No' }}</span></div>
                        <div class="flex justify-between"><span>Zone</span>
                            <span class="font-semibold" style="color:{{ $spot->polygon_geojson ? '#2e7d32' : '#727971' }}">
                                {{ $spot->polygon_geojson ? 'Drawn ✓' : 'None' }}
                            </span>
                        </div>
                        <div class="flex justify-between"><span>Route</span>
                            <span class="font-semibold" style="color:{{ $spot->route_geojson ? '#1565c0' : '#727971' }}">
                                {{ $spot->route_geojson ? 'Drawn ✓' : 'None' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

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

        const map = L.map('admin-spot-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap © CARTO', maxZoom: 19
        }).addTo(map);

        // Collect all bounds for fitBounds
        const allBounds = [];

        // ── Main location marker ─────────────────────────────────
        const pinIcon = L.divIcon({
            className: '',
            html: `<div style="width:20px;height:20px;background:#43664c;border:3px solid #fff;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 2px 8px rgba(0,0,0,.3)">
                       <div style="width:6px;height:6px;background:#fff;border-radius:50%;position:absolute;top:4px;left:4px;transform:rotate(45deg)"></div>
                   </div>`,
            iconSize: [20, 20], iconAnchor: [10, 20], popupAnchor: [0, -22]
        });
        const mainMarker = L.marker([lat, lng], { icon: pinIcon })
            .addTo(map)
            .bindPopup(`<b style="font-size:13px">${@json($spot->name)}</b><br><small style="color:#727971">${@json($spot->category)}</small>`);
        allBounds.push([lat, lng]);

        // ── Zone Polygon ─────────────────────────────────────────
        if (polygonData) {
            try {
                const geojson = typeof polygonData === 'string' ? JSON.parse(polygonData) : polygonData;
                const layer = L.geoJSON(geojson, {
                    style: {
                        color: '#43664c',
                        weight: 2.5,
                        opacity: 0.9,
                        fillColor: '#7ba082',
                        fillOpacity: 0.25
                    }
                }).addTo(map).bindPopup('<b style="font-size:12px;color:#2e7d32">📐 Zone Polygon</b>');
                allBounds.push(...layer.getBounds().toBBoxString().split(',').reduce((acc, v, i) => {
                    if (i % 2 === 0) acc.push([]);
                    acc[acc.length - 1].push(parseFloat(v));
                    return acc;
                }, []).map(pair => [pair[1], pair[0]]));
                // Simpler: use layer bounds directly
                try { const b = layer.getBounds(); allBounds.push(b.getNorthEast(), b.getSouthWest()); } catch(e){}
            } catch (e) { console.warn('polygon_geojson parse error', e); }
        }

        // ── Route Polyline ───────────────────────────────────────
        if (routeData) {
            try {
                const geojson = typeof routeData === 'string' ? JSON.parse(routeData) : routeData;
                const layer = L.geoJSON(geojson, {
                    style: {
                        color: '#3b82f6',
                        weight: 3.5,
                        opacity: 0.85,
                        dashArray: '8 5'
                    }
                }).addTo(map).bindPopup('<b style="font-size:12px;color:#1565c0">🛣️ Route Line</b>');
                try { const b = layer.getBounds(); allBounds.push(b.getNorthEast(), b.getSouthWest()); } catch(e){}
            } catch (e) { console.warn('route_geojson parse error', e); }
        }

        // ── fitBounds to all geometry ────────────────────────────
        if (allBounds.length > 1) {
            try {
                map.fitBounds(L.latLngBounds(allBounds), { padding: [32, 32], maxZoom: 17 });
            } catch(e) {
                map.setView([lat, lng], 15);
            }
        }
    })();
    </script>
    @endpush
</x-app-layout>
