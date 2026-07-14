<x-app-layout>
    <x-slot name="title">Interactive Map</x-slot>
    @section('page-title', 'Interactive Map')

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #wt-main { padding-top: 64px; }
        #map-container { height: calc(100vh - 64px); width: 100%; position: relative; }
        #leaflet-map { height: 100%; width: 100%; z-index: 1; }

        /* ── Map sidebar panel ── */
        #map-panel {
            position: absolute; top: 16px; right: 16px; z-index: 10;
            width: 300px; background: rgba(255,255,255,0.96);
            backdrop-filter: blur(8px);
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        .map-spot-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; cursor: pointer;
            border-bottom: 1px solid #f1f3ff;
            transition: background .12s;
        }
        .map-spot-item:hover { background: #f1f3ff; }
        .map-spot-item:last-child { border-bottom: none; }
        .spot-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
            vertical-align: middle;
        }

        /* ── Category dropdown — fix arrow alignment ── */
        .map-category-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23727971' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }

        /* ── Leaflet popup ── */
        .wt-popup img { display: block; width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px; }
    </style>
    @endpush

    <div id="map-container">
        <div id="leaflet-map"></div>

        {{-- Floating search / filter bar (form-based — submits to backend) --}}
        <form method="GET" action="{{ route('user.map') }}"
              class="absolute top-4 left-4 z-10 flex gap-2" id="map-search-form">
            <div class="flex items-center gap-2 bg-white/95 backdrop-blur rounded-xl px-3 py-2.5 border border-[#e5e7eb] shadow-sm">
                <span class="material-symbols-outlined text-[#727971]" style="font-size:18px">search</span>
                <input id="map-search-input" name="search" type="text"
                       value="{{ $search }}"
                       placeholder="Search spots…"
                       class="bg-transparent outline-none text-[13px] text-[#141b2b] w-44 placeholder:text-[#c2c8c0]">
                @if($search || $category)
                    <a href="{{ route('user.map') }}" class="text-[#727971] hover:text-red-400 transition-colors" title="Clear filters">
                        <span class="material-symbols-outlined" style="font-size:16px">close</span>
                    </a>
                @endif
            </div>
            <select id="map-category-filter" name="category"
                    class="map-category-select bg-white/95 backdrop-blur rounded-xl px-3 py-2.5 border border-[#e5e7eb] shadow-sm text-[13px] text-[#141b2b] outline-none cursor-pointer"
                    onchange="document.getElementById('map-search-form').submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </form>

        {{-- Info Panel --}}
        <div id="map-panel">
            <div class="px-4 py-3 border-b border-[#f1f3ff] flex items-center justify-between">
                <div>
                    <p class="text-[13px] font-bold text-[#141b2b]">
                        @if($search || $category)
                            Search Results
                        @else
                            Photo Spots
                        @endif
                    </p>
                    <p class="text-[11px] text-[#727971]">
                        {{ $spots->count() }} location{{ $spots->count() !== 1 ? 's' : '' }}
                        @if($search) for "{{ $search }}"@endif
                    </p>
                </div>
                <a href="{{ route('user.photographers') }}" id="map-to-photographers-top"
                   class="text-[11px] font-semibold text-[#43664c] hover:underline">Photographers →</a>
            </div>
            <div class="overflow-y-auto" style="max-height:360px">
                @forelse($spots as $spot)
                <div class="map-spot-item" id="map-spot-{{ $spot->id }}"
                     onclick="flyToSpot({{ $spot->latitude }}, {{ $spot->longitude }}, {{ $spot->id }})">
                    <span class="spot-dot" style="background:{{ $spot->is_sponsored ? '#7ba082' : '#c2c8c0' }}"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] font-semibold text-[#141b2b] truncate">{{ $spot->name }}</p>
                        <p class="text-[10px] text-[#727971] uppercase tracking-wide">{{ $spot->category }}</p>
                    </div>
                    @if($spot->is_sponsored)
                        <span class="text-[9px] font-bold bg-[#c5eccb] text-[#43664c] px-1.5 py-0.5 rounded-full flex-shrink-0">★</span>
                    @endif
                </div>
                @empty
                <div class="px-4 py-8 text-center">
                    <span class="material-symbols-outlined text-[#c2c8c0]" style="font-size:36px">location_off</span>
                    <p class="text-[12px] text-[#727971] mt-2">No spots found.</p>
                    @if($search || $category)
                        <a href="{{ route('user.map') }}" class="text-[11px] text-[#43664c] hover:underline font-semibold">Clear filters</a>
                    @endif
                </div>
                @endforelse
            </div>
            <div class="px-4 py-3 border-t border-[#f1f3ff]">
                <a href="{{ route('user.photographers') }}" id="map-to-photographers"
                   class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-[13px] font-semibold text-white w-full transition-colors"
                   style="background:#43664c">
                    <span class="material-symbols-outlined" style="font-size:16px">photo_camera</span>
                    Browse Photographers
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function() {
        const spotsData = @json($spots);

        // Default center: Samarinda, East Kalimantan
        const map = L.map('leaflet-map').setView([-0.5022, 117.1536], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com">CARTO</a>',
            maxZoom: 19
        }).addTo(map);

        // Custom marker icons
        function makeIcon(color, size) {
            return L.divIcon({
                className: '',
                html: `<div style="width:${size}px;height:${size}px;background:${color};border:3px solid #fff;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 2px 8px rgba(0,0,0,0.25)"><div style="width:${Math.round(size*0.3)}px;height:${Math.round(size*0.3)}px;background:#fff;border-radius:50%;position:absolute;top:${Math.round(size*0.32)}px;left:${Math.round(size*0.32)}px;transform:rotate(45deg)"></div></div>`,
                iconSize: [size, size],
                iconAnchor: [size/2, size],
                popupAnchor: [0, -(size+4)]
            });
        }
        const markerIcon    = makeIcon('#43664c', 28);
        const sponsoredIcon = makeIcon('#7ba082', 34);

        // Build marker map for sidebar click → flyTo
        const markerMap = {};

        spotsData.forEach(spot => {
            if (spot.latitude == null || spot.longitude == null) return;

            const icon      = spot.is_sponsored ? sponsoredIcon : markerIcon;
            const imgHtml   = spot.display_image
                ? `<img src="${spot.display_image}" alt="${spot.name}" style="display:block;width:100%;height:110px;object-fit:cover;border-radius:8px;margin-bottom:8px">`
                : '';
            const catHtml   = spot.category
                ? `<span style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:#727971;font-weight:600">${spot.category}</span>`
                : '';
            const descHtml  = spot.description
                ? `<p style="font-size:12px;color:#424842;margin:6px 0;line-height:1.5">${spot.description}</p>`
                : '';
            const badge     = spot.is_sponsored
                ? `<span style="display:inline-block;margin-top:4px;background:#c5eccb;color:#43664c;font-size:10px;font-weight:700;padding:2px 8px;border-radius:999px">★ Sponsored</span>`
                : '';

            const detailUrl = `/spots/${spot.id}`;
            const detailBtn = `
                <a href="${detailUrl}"
                   style="display:block;margin-top:10px;text-align:center;padding:7px 0;background:#43664c;color:#fff;font-size:12px;font-weight:700;border-radius:8px;text-decoration:none;letter-spacing:.03em;transition:background .15s"
                   onmouseover="this.style.background='#33503c'"
                   onmouseout="this.style.background='#43664c'"
                >Lihat Detail</a>`;

            const marker = L.marker([spot.latitude, spot.longitude], { icon })
                .addTo(map)
                .bindPopup(`
                    <div style="font-family:'Inter',sans-serif;min-width:220px;max-width:260px">
                        ${imgHtml}
                        <p style="font-weight:700;font-size:14px;color:#141b2b;margin:0 0 2px">${spot.name}</p>
                        ${catHtml}
                        ${descHtml}
                        ${badge}
                        ${detailBtn}
                    </div>
                `, { maxWidth: 280 });

            // Attach polygon to marker (but do NOT add to map yet)
            if (spot.polygon_geojson) {
                try {
                    const geo = typeof spot.polygon_geojson === 'string'
                        ? JSON.parse(spot.polygon_geojson)
                        : spot.polygon_geojson;
                    marker.polygonData = L.geoJSON(geo, {
                        style: { color: '#7ba082', weight: 2, opacity: 0.8, fillColor: '#7ba082', fillOpacity: 0.4 }
                    });
                } catch(e) {}
            }

            // On marker click: clear old GeoJSON layers, show this spot's polygon,
            // then fly to polygon bounds (if present) or to the marker latlng.
            marker.on('click', function() {
                map.eachLayer(function(layer) {
                    if (layer instanceof L.GeoJSON) { map.removeLayer(layer); }
                });
                if (this.polygonData) {
                    this.polygonData.addTo(map);
                    // Fly to fit the whole polygon on screen
                    map.flyToBounds(this.polygonData.getBounds(), { padding: [40, 40], duration: 1.2 });
                } else {
                    // No polygon — just fly to the marker position
                    map.flyTo([spot.latitude, spot.longitude], 16, { animate: true, duration: 1.2 });
                }
            });

            markerMap[spot.id] = marker;
        });

        // Click on empty map area → clear any active polygon
        map.on('click', function() {
            map.eachLayer(function(layer) {
                if (layer instanceof L.GeoJSON) { map.removeLayer(layer); }
            });
        });


        // Fly to spot from sidebar
        window.flyToSpot = function(lat, lng, id) {
            map.flyTo([lat, lng], 17, { animate: true, duration: 1.2 });
            setTimeout(() => {
                if (markerMap[id]) markerMap[id].openPopup();
            }, 1300);
        };

        // Auto-submit search on Enter key (form already handles submit naturally)
        document.getElementById('map-search-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('map-search-form').submit();
            }
        });

    })();
    </script>
    @endpush
</x-app-layout>
