<x-app-layout>
    <x-slot name="title">Map</x-slot>

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #walkture-map { height: calc(100vh - 0px); width: 100%; }
        .main-content { padding: 0; overflow: hidden; }
        .leaflet-popup-content-wrapper {
            border-radius: 14px !important;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12) !important;
            font-family: 'Nunito', sans-serif;
        }
        .leaflet-control-zoom { border-radius: 12px !important; overflow: hidden; border: none !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; }
        .leaflet-control-zoom a { color: #374151 !important; }
        .custom-spot-marker { background: #7ba082; border: 3px solid white; border-radius: 50%; width: 14px; height: 14px; box-shadow: 0 2px 8px rgba(123,160,130,0.5); }
        .custom-sponsored-marker { background: #f59e0b; border: 3px solid white; border-radius: 50%; width: 14px; height: 14px; box-shadow: 0 2px 8px rgba(245,158,11,0.5); }

        /* Custom Hover Tooltip styling */
        .walkture-tooltip {
            background: transparent !important;
            border: none !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18) !important;
            padding: 0 !important;
            border-radius: 12px !important;
            overflow: hidden;
        }
        .walkture-tooltip::before {
            border-top-color: white !important;
        }
        .leaflet-tooltip.walkture-tooltip {
            background: white !important;
        }
    </style>
    @endpush


    {{-- Full-screen map --}}
    <div id="walkture-map"></div>

    {{-- Floating right panel --}}
    <div class="fixed top-6 right-6 w-80 z-[1000] space-y-3" id="map-panel">

        {{-- Explore header --}}
        <div class="card !p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-neutral-800">Explore Map</h2>
                <span class="pill-sage text-[10px]">Live</span>
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="map-search" placeholder="Search spots, cafés, zones…"
                       class="input pl-9 text-sm">
            </div>
        </div>

        {{-- Map Layers --}}
        <div class="card !p-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-3">Map Layers</h3>
            <div class="space-y-2.5">
                <label class="flex items-center gap-3 cursor-pointer group" id="layer-spots">
                    <div class="relative">
                        <input type="checkbox" checked class="sr-only peer" id="layer-photo-spots">
                        <div class="w-9 h-5 bg-neutral-200 peer-checked:bg-sage-400 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sage-400 flex-shrink-0"></span>
                        <span class="text-sm font-medium text-neutral-700">Photo Spots</span>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group" id="layer-walks">
                    <div class="relative">
                        <input type="checkbox" checked class="sr-only peer" id="layer-photo-walks">
                        <div class="w-9 h-5 bg-neutral-200 peer-checked:bg-blue-400 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400 flex-shrink-0"></span>
                        <span class="text-sm font-medium text-neutral-700">Photo Walks</span>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group" id="layer-zones">
                    <div class="relative">
                        <input type="checkbox" class="sr-only peer" id="layer-aesthetic-zones">
                        <div class="w-9 h-5 bg-neutral-200 peer-checked:bg-purple-400 rounded-full transition-colors duration-200"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-400 flex-shrink-0"></span>
                        <span class="text-sm font-medium text-neutral-700">Aesthetic Zones</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- Nearby Features --}}
        <div class="card !p-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-3">Nearby Features</h3>
            <div class="space-y-2">
                @foreach($nearbyFeatures as $i => $feature)
                <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-neutral-50 transition-colors cursor-pointer" id="nearby-{{ $i }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                        {{ $feature['type'] === 'sponsored' ? 'bg-amber-50' : ($feature['type'] === 'premium' ? 'bg-sage-50' : 'bg-neutral-100') }}">
                        @if($feature['type'] === 'sponsored')
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                        @elseif($feature['type'] === 'premium')
                            <svg class="w-4 h-4 text-sage-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        @else
                            <svg class="w-4 h-4 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"/><path stroke-linecap="round" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-neutral-800 truncate">{{ $feature['name'] }}</p>
                        <p class="text-xs text-neutral-400">{{ $feature['distance'] }}</p>
                    </div>
                    @if($feature['type'] === 'sponsored')
                        <span class="pill bg-amber-50 text-amber-600 text-[10px]">Sponsored</span>
                    @elseif($feature['locked'])
                        <span class="pill bg-neutral-100 text-neutral-500 text-[10px]">
                            <svg class="w-3 h-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            Locked
                        </span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // ================================================================
        // Inisialisasi peta — terkunci di wilayah Kota Samarinda
        // ================================================================

        // Bounding box Kota Samarinda (SW corner → NE corner)
        const samarindaBounds = L.latLngBounds(
            [-0.7200, 116.9000], // Sudut Barat Daya (SW)
            [-0.2500, 117.4000]  // Sudut Timur Laut (NE)
        );

        const map = L.map('walkture-map', {
            zoomControl:  false,
            center:       [-0.5022, 117.1536], // Pusat Kota Samarinda
            zoom:         13,                   // Default zoom level
            minZoom:      11,                   // Batas zoom-out minimum (tidak keluar dari Samarinda)
            maxZoom:      19,
            maxBounds:    samarindaBounds,      // Kunci panning agar tidak keluar dari Samarinda
            maxBoundsViscosity: 1.0,            // Kekuatan batas (1.0 = tidak bisa dilewati sama sekali)
        });

        // Custom tile layer (CartoDB light)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com/">CARTO</a>',
            maxZoom: 19,
            minZoom: 11,
        }).addTo(map);

        // Zoom control bottom-right
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Custom marker icons
        function makeIcon(color) {
            return L.divIcon({
                className: '',
                html: `<div style="background:${color};border:3px solid white;border-radius:50%;width:14px;height:14px;box-shadow:0 2px 8px rgba(0,0,0,0.2)"></div>`,
                iconSize: [14, 14],
                iconAnchor: [7, 7],
            });
        }

        const spotIcon       = makeIcon('#7ba082');
        const sponsoredIcon  = makeIcon('#f59e0b');
        const premiumIcon    = makeIcon('#8b5cf6');

        // Sample spots data — lokasi di Kota Samarinda dengan thumbnail dan kategori
        const spots = [
            {
                lat: -0.4948, lng: 117.1436,
                name: 'Tepian Mahakam',
                type: 'premium',
                category: 'Riverside View',
                desc: 'Sunset ikonik di tepi Sungai Mahakam — spot favorit fotografer Samarinda.',
                image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=180&q=80',
            },
            {
                lat: -0.5022, lng: 117.1536,
                name: 'Taman Samarendah',
                type: 'spot',
                category: 'City Park',
                desc: 'Taman kota hijau di pusat Samarinda — ideal untuk portrait dan candid.',
                image: 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=180&q=80',
            },
            {
                lat: -0.4820, lng: 117.1620,
                name: 'Jembatan Mahakam',
                type: 'premium',
                category: 'Landmark',
                desc: 'Landmark ikonik Kota Samarinda — angle terbaik dari seberang sungai.',
                image: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=180&q=80',
            },
            {
                lat: -0.5150, lng: 117.1390,
                name: 'Masjid Raya Darussalam',
                type: 'spot',
                category: 'Arsitektur',
                desc: 'Masjid megah arsitektur modern — lighting terbaik saat golden hour.',
                image: 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=180&q=80',
            },
            {
                lat: -0.5280, lng: 117.1480,
                name: 'Kedai Kopi Pagi Pagi',
                type: 'sponsored',
                category: 'Kafe & Kuliner',
                desc: '☕ Sponsored — nikmati diskon 10% untuk pelanggan Walkture!',
                image: 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=180&q=80',
            },
            {
                lat: -0.4700, lng: 117.1300,
                name: 'Pasar Pagi Samarinda',
                type: 'spot',
                category: 'Street Market',
                desc: 'Pasar tradisional penuh warna — street photography terbaik dari pukul 6-9 pagi.',
                image: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=180&q=80',
            },
        ];

        spots.forEach((spot, idx) => {
            const icon = spot.type === 'sponsored' ? sponsoredIcon : spot.type === 'premium' ? premiumIcon : spotIcon;

            // Warna badge kategori berdasarkan tipe
            const badgeColor = spot.type === 'sponsored'
                ? 'background:#f59e0b;color:white'
                : spot.type === 'premium'
                    ? 'background:#8b5cf6;color:white'
                    : 'background:#7ba082;color:white';

            // ── Hover Tooltip: thumbnail + nama + kategori ───────────
            const tooltipHtml = `
                <div style="font-family:Nunito,sans-serif;width:160px;overflow:hidden;border-radius:10px">
                    <div style="width:100%;height:90px;overflow:hidden;border-radius:8px 8px 0 0">
                        <img src="${spot.image}" alt="${spot.name}"
                             style="width:100%;height:100%;object-fit:cover;display:block"
                             onerror="this.style.background='#e5e7eb'">
                    </div>
                    <div style="padding:8px 10px 9px">
                        <p style="font-weight:700;font-size:12px;color:#111827;margin:0 0 4px">${spot.name}</p>
                        <span style="${badgeColor};font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;letter-spacing:0.3px">${spot.category}</span>
                    </div>
                </div>`;

            // ── Click Popup: info lengkap ────────────────────────────
            const popupHtml = `
                <div style="font-family:Nunito,sans-serif;padding:4px;min-width:160px">
                    <img src="${spot.image}" alt="${spot.name}"
                         style="width:100%;height:90px;object-fit:cover;border-radius:8px;margin-bottom:8px;display:block">
                    <strong style="font-size:13px;color:#111827">${spot.name}</strong>
                    <p style="font-size:11px;color:#6b7280;margin:4px 0 0">${spot.desc}</p>
                </div>`;

            const marker = L.marker([spot.lat, spot.lng], { icon })
                .addTo(map)
                .bindTooltip(tooltipHtml, {
                    permanent:   false,
                    sticky:      false,
                    direction:   'top',
                    offset:      [0, -10],
                    opacity:     1,
                    className:   'walkture-tooltip',
                })
                .bindPopup(popupHtml, {
                    maxWidth: 200,
                });
        });

        // Photo Walk polyline — rute jalan foto di area Tepian Mahakam
        const walkCoords = [
            [-0.4948, 117.1436],
            [-0.4980, 117.1480],
            [-0.5010, 117.1520],
            [-0.5022, 117.1536]
        ];
        L.polyline(walkCoords, { color: '#60a5fa', weight: 3, opacity: 0.7, dashArray: '6, 4' }).addTo(map)
            .bindPopup('<strong style="font-family:Nunito">Tepian Mahakam Photo Walk</strong>');

        // Aesthetic Zone polygon — kawasan kreatif Samarinda Kota
        const zoneCoords = [
            [-0.5000, 117.1400],
            [-0.4900, 117.1500],
            [-0.4950, 117.1620],
            [-0.5080, 117.1550]
        ];
        L.polygon(zoneCoords, { color: '#a78bfa', fillColor: '#a78bfa', fillOpacity: 0.1, weight: 2 }).addTo(map)
            .bindPopup('<strong style="font-family:Nunito">Kawasan Kreatif Samarinda</strong>');
    });
    </script>
    @endpush
</x-app-layout>
