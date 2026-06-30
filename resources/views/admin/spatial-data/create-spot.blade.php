<x-app-layout>
    <x-slot name="title">Add Photo Spot</x-slot>
    @section('page-title', 'Add Photo Spot')

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.16.0/dist/leaflet-geoman.css"/>
    <style>
        #admin-draw-map { height: 360px; border-radius: 12px; overflow: hidden; border: 1.5px solid #c2c8c0; }
        .geoman-layer-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
        }
        .leaflet-pm-toolbar .leaflet-pm-icon-polygon { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpolygon points='12,2 22,18 2,18' fill='none' stroke='%23333' stroke-width='2'/%3E%3C/svg%3E"); }
    </style>
    @endpush

    <div class="px-6 py-8 max-w-4xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('admin.spatial-data') }}" class="hover:text-[#43664c] font-semibold transition-colors">Spatial Data</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold">Add Photo Spot</span>
        </div>

        <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-[#f1f3ff] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#c5eccb">
                    <span class="material-symbols-outlined" style="color:#43664c">add_location_alt</span>
                </div>
                <div>
                    <h1 class="text-[17px] font-bold text-[#141b2b]">Add New Photo Spot</h1>
                    <p class="text-[12px] text-[#727971]">Creates a new map marker. Use the drawing tools to add a zone or walking route.</p>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.spots.store') }}" class="px-6 py-6 space-y-5" id="spot-form" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-[13px] text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined" style="font-size:14px">error</span>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                {{-- Name & Category --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="spot-name" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Spot Name <span class="text-red-400">*</span></label>
                        <input id="spot-name" name="name" type="text" required value="{{ old('name') }}"
                               placeholder="e.g. Mahakam Riverfront"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                    <div>
                        <label for="spot-category" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Category <span class="text-red-400">*</span></label>
                        <select id="spot-category" name="category" required
                                class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none cursor-pointer transition-colors"
                                onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                            <option value="">Select category…</option>
                            @foreach(['Street', 'Portrait', 'Landscape', 'Analog', 'Architecture', 'Night', 'Wildlife', 'Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Coordinates --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="spot-lat" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Latitude <span class="text-red-400">*</span></label>
                        <input id="spot-lat" name="latitude" type="number" step="any" required value="{{ old('latitude') }}"
                               placeholder="-0.5022"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] font-mono outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                    <div>
                        <label for="spot-lng" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Longitude <span class="text-red-400">*</span></label>
                        <input id="spot-lng" name="longitude" type="number" step="any" required value="{{ old('longitude') }}"
                               placeholder="117.1536"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] font-mono outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                </div>

                {{-- Interactive Drawing Map --}}
                <div>
                    <label class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-2">
                        Drawing Map
                        <span class="ml-2 text-[10px] font-normal normal-case text-[#727971]">Click map to set point location, then use toolbar to draw zone/route</span>
                    </label>

                    {{-- Drawing Status Badges --}}
                    <div class="flex gap-2 mb-2 flex-wrap" id="drawing-badges">
                        <span id="badge-polygon" class="geoman-layer-badge hidden" style="background:#e8f5e9;color:#2e7d32">
                            <span class="material-symbols-outlined" style="font-size:13px">pentagon</span> Zone Drawn ✓
                        </span>
                        <span id="badge-route" class="geoman-layer-badge hidden" style="background:#e3f2fd;color:#1565c0">
                            <span class="material-symbols-outlined" style="font-size:13px">route</span> Route Drawn ✓
                        </span>
                    </div>

                    <div id="admin-draw-map"></div>

                    <p class="text-[11px] text-[#727971] mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:13px">info</span>
                        <strong>Polygon</strong> = aesthetic zone &nbsp;|&nbsp; <strong>Line</strong> = walking route.
                        Only one of each is supported per spot.
                    </p>
                </div>

                {{-- Hidden GeoJSON inputs --}}
                <input type="hidden" name="polygon_geojson" id="polygon_geojson">
                <input type="hidden" name="route_geojson" id="route_geojson">

                {{-- Description --}}
                <div>
                    <label for="spot-desc" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Description</label>
                    <textarea id="spot-desc" name="description" rows="3"
                              placeholder="A brief description of this spot and what makes it great for photography…"
                              class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none resize-none transition-colors"
                              onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">{{ old('description') }}</textarea>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label for="spot-img" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Spot Image</label>
                    <div id="img-preview-wrap" class="mb-2 hidden">
                        <img id="img-preview" src="" alt="Preview" class="h-28 w-auto rounded-xl object-cover border border-[#e5e7eb]">
                    </div>
                    <input id="spot-img" name="image" type="file" accept="image/jpeg,image/png,image/webp"
                           class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors cursor-pointer"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'"
                           onchange="previewImage(this)">
                    <p class="text-[11px] text-[#727971] mt-1">JPEG / PNG / WebP, max 4 MB.</p>
                </div>

                {{-- Sponsored toggle --}}
                <div class="flex items-start gap-3 p-4 rounded-xl border border-[#e5e7eb] bg-[#f9f9ff]">
                    <input id="spot-sponsored" name="is_sponsored" type="checkbox" value="1"
                           {{ old('is_sponsored') ? 'checked' : '' }}
                           class="w-4 h-4 mt-0.5 accent-[#43664c] cursor-pointer">
                    <div>
                        <label for="spot-sponsored" class="text-[14px] font-semibold text-[#141b2b] cursor-pointer">Mark as Sponsored Spot</label>
                        <p class="text-[12px] text-[#727971] mt-0.5">Sponsored spots appear with a gold star badge on the map.</p>
                    </div>
                </div>

                {{-- Promo Detail --}}
                <div id="promo-detail-wrapper" class="{{ old('is_sponsored') ? '' : 'hidden' }}">
                    <label for="spot-promo" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Promo Detail</label>
                    <textarea id="spot-promo" name="promo_detail" rows="2"
                              placeholder="e.g. 30% off photography workshops until Dec 2025"
                              class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none resize-none transition-colors"
                              onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">{{ old('promo_detail') }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-[#f1f3ff]">
                    <a href="{{ route('admin.spatial-data') }}" id="spot-form-cancel"
                       class="px-5 py-2.5 rounded-xl text-[13px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="spot-form-submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-all hover:opacity-90"
                            style="background:#43664c">
                        <span class="material-symbols-outlined" style="font-size:18px">add_location_alt</span>
                        Save Spot
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.16.0/dist/leaflet-geoman.min.js"></script>
    <script>
    (function () {
        // ─── 1. Init map centred on Samarinda ───────────────────────────────
        const defaultCenter = [-0.5022, 117.1536];
        const drawMap = L.map('admin-draw-map', { zoomControl: true }).setView(defaultCenter, 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap © CARTO', maxZoom: 19
        }).addTo(drawMap);

        // ─── 2. Spot-point marker (click map to set) ────────────────────────
        let pointMarker = null;
        const latInput  = document.getElementById('spot-lat');
        const lngInput  = document.getElementById('spot-lng');

        function placeMarker(lat, lng) {
            if (pointMarker) drawMap.removeLayer(pointMarker);
            pointMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: '',
                    html: '<div style="width:16px;height:16px;background:#43664c;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.3)"></div>',
                    iconSize: [16, 16], iconAnchor: [8, 8]
                }),
                title: 'Spot Location'
            }).addTo(drawMap);
        }

        // Clicking on the map updates the lat/lng inputs and moves the marker
        drawMap.on('click', function (e) {
            latInput.value = e.latlng.lat.toFixed(6);
            lngInput.value = e.latlng.lng.toFixed(6);
            placeMarker(e.latlng.lat, e.latlng.lng);
        });

        // If lat/lng fields are pre-filled (old() values), place marker & pan
        function syncMapFromInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                placeMarker(lat, lng);
                drawMap.setView([lat, lng], 15);
            }
        }
        syncMapFromInputs();
        latInput.addEventListener('change', syncMapFromInputs);
        lngInput.addEventListener('change', syncMapFromInputs);

        // ─── 3. Leaflet-Geoman toolbar ──────────────────────────────────────
        drawMap.pm.addControls({
            position:          'topleft',
            drawMarker:        false,
            drawCircleMarker:  false,
            drawCircle:        false,
            drawRectangle:     false,
            drawText:          false,
            editMode:          true,
            dragMode:          false,
            cutPolygon:        false,
            removalMode:       true,
            drawPolygon:       true,
            drawPolyline:      true,
            rotateMode:        false,
        });

        // Style all drawn layers with brand colours
        drawMap.pm.setGlobalOptions({
            pathOptions: { color: '#43664c', fillColor: '#7ba082', fillOpacity: 0.18, weight: 2 }
        });

        // ─── 4. Track drawn layers ──────────────────────────────────────────
        let polygonLayer = null;
        let routeLayer   = null;

        const polyInput  = document.getElementById('polygon_geojson');
        const routeInput = document.getElementById('route_geojson');
        const badgePoly  = document.getElementById('badge-polygon');
        const badgeRoute = document.getElementById('badge-route');

        function serializeLayer(layer, field, badge) {
            const gj = layer.toGeoJSON();
            field.value = JSON.stringify(gj);
            badge.classList.remove('hidden');
        }

        function clearLayer(field, badge) {
            field.value = '';
            badge.classList.add('hidden');
        }

        drawMap.on('pm:create', function (e) {
            const type = e.shape; // 'Polygon' | 'Line'

            if (type === 'Polygon') {
                // Remove previous polygon if exists
                if (polygonLayer) { drawMap.removeLayer(polygonLayer); }
                polygonLayer = e.layer;
                e.layer.setStyle({ color: '#43664c', fillColor: '#7ba082', fillOpacity: 0.2, weight: 2.5 });
                serializeLayer(e.layer, polyInput, badgePoly);

                // Listen for edits on this layer
                e.layer.on('pm:edit', function () {
                    serializeLayer(e.layer, polyInput, badgePoly);
                });

            } else if (type === 'Line') {
                if (routeLayer) { drawMap.removeLayer(routeLayer); }
                routeLayer = e.layer;
                e.layer.setStyle({ color: '#60a5fa', weight: 3, opacity: 0.85, dashArray: '8, 5' });
                serializeLayer(e.layer, routeInput, badgeRoute);

                e.layer.on('pm:edit', function () {
                    serializeLayer(e.layer, routeInput, badgeRoute);
                });
            }
        });

        drawMap.on('pm:remove', function (e) {
            if (e.layer === polygonLayer) { polygonLayer = null; clearLayer(polyInput, badgePoly); }
            if (e.layer === routeLayer)   { routeLayer   = null; clearLayer(routeInput, badgeRoute); }
        });

        // ─── 5. Sponsored toggle ────────────────────────────────────────────
        document.getElementById('spot-sponsored').addEventListener('change', function () {
            document.getElementById('promo-detail-wrapper').classList.toggle('hidden', !this.checked);
        });

        // ─── 6. Image preview ───────────────────────────────────────────────
        window.previewImage = function(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('img-preview').src = e.target.result;
                    document.getElementById('img-preview-wrap').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        // ─── 7. Invalidate map size after page layout settles ───────────────
        setTimeout(function () { drawMap.invalidateSize(); }, 300);
    })();
    </script>
    @endpush
</x-app-layout>