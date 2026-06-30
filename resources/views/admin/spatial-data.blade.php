<x-app-layout>
    <x-slot name="title">Spatial Data Management</x-slot>
    @section('page-title', 'Spatial Data Management')

    <div class="px-6 py-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#141b2b] tracking-tight">Spatial Data Management</h1>
                <p class="text-[#727971] mt-1 text-[15px]">Manage GIS spot data — coordinates, zones, and nearest routes.</p>
            </div>
            <a href="{{ route('admin.spots.create') }}" id="admin-spatial-add-spot"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-bold text-white transition-colors"
               style="background:#43664c">
                <span class="material-symbols-outlined" style="font-size:18px">add_location_alt</span> Add Spot
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-[#f0faf2] border border-[#7ba082] text-[#43664c] rounded-xl px-4 py-3 text-[13px] font-semibold" id="flash-success">
            <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        {{-- Spots Grid --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl">
            <div class="px-6 py-4 border-b border-[#f1f3ff] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#43664c]">location_on</span>
                    <h2 class="text-[15px] font-bold text-[#141b2b]">Photo Spots</h2>
                    <span class="text-[11px] font-bold bg-[#c5eccb] text-[#43664c] px-2 py-0.5 rounded-full">{{ $spots->count() }}</span>
                </div>
                <input type="text" id="spatial-spots-search" placeholder="Search spots…"
                       class="px-3 py-2 border border-[#c2c8c0] rounded-xl text-[13px] outline-none w-48"
                       onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'"
                       oninput="filterSpots(this.value)">
            </div>

            {{-- Card Grid --}}
            <div id="spots-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                @forelse($spots as $spot)
                <div class="spot-card bg-[#f9f9ff] border border-[#e5e7eb] rounded-2xl overflow-hidden hover:border-[#7ba082] hover:shadow-sm transition-all"
                     id="spot-card-{{ $spot->id }}">
                    {{-- Spot image --}}
                    @php $displayImg = $spot->display_image; @endphp
                    @if($displayImg)
                        <img src="{{ $displayImg }}" alt="{{ $spot->name }}"
                             class="w-full h-36 object-cover">
                    @else
                        <div class="w-full h-36 flex items-center justify-center"
                             style="background:linear-gradient(135deg,#e9edff,#c5eccb)">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:40px">photo_camera</span>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background:{{ $spot->is_sponsored ? '#c5eccb' : '#f1f3ff' }}">
                                    <span class="material-symbols-outlined" style="font-size:18px;color:{{ $spot->is_sponsored ? '#43664c' : '#727971' }}">location_on</span>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-[#141b2b] leading-tight spot-name">{{ $spot->name }}</p>
                                    <p class="text-[11px] text-[#727971] uppercase tracking-wide">{{ $spot->category }}</p>
                                </div>
                            </div>
                            @if($spot->is_sponsored)
                                <span class="flex-shrink-0 text-[9px] font-bold bg-[#c5eccb] text-[#43664c] px-1.5 py-0.5 rounded-full">★ Sponsored</span>
                            @endif
                        </div>
                        <div class="text-[11px] text-[#727971] font-mono mb-3">
                            {{ number_format($spot->latitude, 4) }}, {{ number_format($spot->longitude, 4) }}
                        </div>
                        {{-- GeoJSON geometry indicators --}}
                        <div class="flex gap-1.5 mb-3 flex-wrap">
                            @if($spot->polygon_geojson)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#e8f5e9;color:#2e7d32">
                                <span class="material-symbols-outlined" style="font-size:11px">pentagon</span> Zone
                            </span>
                            @endif
                            @if($spot->route_geojson)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#e3f2fd;color:#1565c0">
                                <span class="material-symbols-outlined" style="font-size:11px">route</span> Route
                            </span>
                            @endif
                            @if(!$spot->polygon_geojson && !$spot->route_geojson)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#f5f5f5;color:#9e9e9e">
                                No geometry drawn
                            </span>
                            @endif
                        </div>
                        <div class="flex gap-2 pt-3 border-t border-[#f1f3ff]">
                            <a href="{{ route('admin.spots.show', $spot->id) }}" id="spot-view-{{ $spot->id }}"
                               class="flex-1 text-center py-1.5 rounded-lg text-[12px] font-semibold border border-[#7ba082] text-[#43664c] hover:bg-[#f1f3ff] transition-colors">View</a>
                            <button type="button" id="spot-edit-{{ $spot->id }}"
                                    onclick="openSpotEditModal(
                                        {{ $spot->id }},
                                        '{{ addslashes($spot->name) }}',
                                        '{{ addslashes($spot->category) }}',
                                        '{{ $spot->latitude }}',
                                        '{{ $spot->longitude }}',
                                        '{{ addslashes($spot->description ?? '') }}',
                                        {{ $spot->is_sponsored ? 'true' : 'false' }},
                                        '{{ addslashes($spot->promo_detail ?? '') }}',
                                        '{{ $spot->display_image ?? '' }}',
                                        '{{ $spot->polygon_geojson ? addslashes(is_string($spot->polygon_geojson) ? $spot->polygon_geojson : json_encode($spot->polygon_geojson)) : '' }}',
                                        '{{ $spot->route_geojson ? addslashes(is_string($spot->route_geojson) ? $spot->route_geojson : json_encode($spot->route_geojson)) : '' }}'
                                    )"
                                    class="flex-1 text-center py-1.5 rounded-lg text-[12px] font-bold text-white transition-colors" style="background:#43664c">Edit</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 py-10 text-center text-[13px] text-[#727971]">
                    No spots added yet.
                    <a href="{{ route('admin.spots.create') }}" class="text-[#43664c] font-semibold hover:underline">Add the first one →</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         SPOT EDIT MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div id="spot-edit-modal" class="wt-modal-backdrop" onclick="handleBackdropClick(event,'spot-edit-modal')" aria-hidden="true">
        <div class="wt-modal-box" role="dialog" aria-modal="true" aria-labelledby="spot-edit-title">
            <div class="wt-modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#e9edff">
                        <span class="material-symbols-outlined" style="color:#43664c;font-size:20px">edit_location</span>
                    </div>
                    <div>
                        <h2 id="spot-edit-title" class="text-[16px] font-bold text-[#141b2b]">Edit Spot</h2>
                        <p class="text-[12px] text-[#727971]">Update spot data. Upload a new image to replace the current one.</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('spot-edit-modal')" class="wt-modal-close" aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="spot-edit-form" method="POST" action="" enctype="multipart/form-data" class="wt-modal-body">
                @csrf
                @method('PUT')

                {{-- Name & Category --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-spot-name" class="wt-label">Spot Name <span class="text-red-400">*</span></label>
                        <input id="edit-spot-name" name="name" type="text" required class="wt-input" placeholder="e.g. Mahakam Riverfront">
                    </div>
                    <div>
                        <label for="edit-spot-category" class="wt-label">Category <span class="text-red-400">*</span></label>
                        <select id="edit-spot-category" name="category" required class="wt-input cursor-pointer">
                            <option value="">Select category…</option>
                            @foreach(['Street', 'Portrait', 'Landscape', 'Analog', 'Architecture', 'Night', 'Wildlife', 'Other'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Coordinates --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-spot-lat" class="wt-label">Latitude <span class="text-red-400">*</span></label>
                        <input id="edit-spot-lat" name="latitude" type="number" step="any" required class="wt-input font-mono" placeholder="-0.5022">
                    </div>
                    <div>
                        <label for="edit-spot-lng" class="wt-label">Longitude <span class="text-red-400">*</span></label>
                        <input id="edit-spot-lng" name="longitude" type="number" step="any" required class="wt-input font-mono" placeholder="117.1536">
                    </div>
                </div>

                {{-- Interactive map for pin placement --}}
                <div>
                    <label class="wt-label">Click Map to Reposition Pin</label>
                    <div id="spot-edit-map" style="height:220px;border-radius:12px;overflow:hidden;border:1.5px solid #c2c8c0"></div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="edit-spot-desc" class="wt-label">Description</label>
                    <textarea id="edit-spot-desc" name="description" rows="3" class="wt-input resize-none"
                              placeholder="Describe this photo spot…"></textarea>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="wt-label">Spot Image</label>
                    <div id="edit-img-preview-wrap" class="mb-2 hidden">
                        <img id="edit-img-preview" src="" alt="Current image" class="h-28 w-auto rounded-xl object-cover border border-[#e5e7eb]">
                        <p class="text-[11px] text-[#727971] mt-1">Current image — upload a new file to replace it.</p>
                    </div>
                    <input id="edit-spot-image" name="image" type="file" accept="image/jpeg,image/png,image/webp"
                           class="wt-input py-2 cursor-pointer"
                           onchange="previewEditImage(this)">
                    <p class="text-[11px] text-[#727971] mt-1">JPEG / PNG / WebP, max 4 MB. Leave blank to keep current image.</p>
                </div>

                {{-- Sponsored --}}
                <div class="flex items-start gap-3 p-4 rounded-xl border border-[#e5e7eb] bg-[#f9f9ff]">
                    <input id="edit-spot-sponsored" name="is_sponsored" type="checkbox" value="1"
                           class="w-4 h-4 mt-0.5 accent-[#43664c] cursor-pointer">
                    <div>
                        <label for="edit-spot-sponsored" class="text-[14px] font-semibold text-[#141b2b] cursor-pointer">Mark as Sponsored Spot</label>
                        <p class="text-[12px] text-[#727971] mt-0.5">Sponsored spots appear with a gold star badge on the map.</p>
                    </div>
                </div>

                {{-- Promo Detail (shown when sponsored) --}}
                <div id="edit-promo-wrapper" class="hidden">
                    <label for="edit-spot-promo" class="wt-label">Promo Detail</label>
                    <textarea id="edit-spot-promo" name="promo_detail" rows="2" class="wt-input resize-none"
                              placeholder="e.g. 30% off workshops until Dec 2025"></textarea>
                </div>

                {{-- Hidden GeoJSON fields --}}
                <input type="hidden" name="polygon_geojson" id="edit_polygon_geojson">
                <input type="hidden" name="route_geojson"   id="edit_route_geojson">

                {{-- ── Drawing Tools ──────────────────────────────── --}}
                <div class="border border-[#e5e7eb] rounded-2xl overflow-hidden bg-[#f9f9ff]">
                    <div class="px-4 py-3 border-b border-[#e5e7eb] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:18px">draw</span>
                            <p class="text-[13px] font-bold text-[#141b2b]">Draw Zone &amp; Route</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="clearDrawLayer('polygon')" id="btn-clear-polygon"
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-red-300 hover:text-red-500 transition-colors">
                                ✕ Zone
                            </button>
                            <button type="button" onclick="clearDrawLayer('route')" id="btn-clear-route"
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-red-300 hover:text-red-500 transition-colors">
                                ✕ Route
                            </button>
                        </div>
                    </div>
                    <div id="spot-draw-map" style="height:280px"></div>
                    <div class="px-4 py-2.5 flex gap-4">
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-[#727971]">
                            <span style="display:inline-block;width:12px;height:12px;background:#7ba082;border-radius:2px;opacity:.7"></span>
                            Zone (Polygon)
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-[#727971]">
                            <span style="display:inline-block;width:14px;height:3px;background:#3b82f6;border-radius:2px"></span>
                            Route (Polyline)
                        </span>
                        <span id="draw-status" class="ml-auto text-[11px] text-[#43664c] font-semibold"></span>
                    </div>
                </div>

                <div class="wt-modal-footer">
                    <button type="button" onclick="closeModal('spot-edit-modal')" class="wt-btn-cancel">Cancel</button>
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

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.18.0/dist/leaflet-geoman.css"/>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.18.0/dist/leaflet-geoman.min.js"></script>
    <script>
        /* ── Modal helpers ─────────────────────────────────── */
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

        /* ── Escape closes any open modal ─────────────── */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal('spot-edit-modal');
        });

        /* ── Card search ──────────────────────────────────── */
        function filterSpots(q) {
            document.querySelectorAll('.spot-card').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
            });
        }

        /* ══════════════════════════════════════════════════════
           DRAWING MAP — Leaflet + Geoman
        ══════════════════════════════════════════════════════ */
        let editMap     = null;
        let editMarker  = null;
        let polygonLayer = null;
        let routeLayer   = null;
        let drawMap      = null;   // separate Geoman map
        let pinMarker    = null;

        /* ── Init the position-only pin map (top of modal) ── */
        function initEditMap(lat, lng) {
            if (editMap) { editMap.remove(); editMap = null; editMarker = null; }
            setTimeout(() => {
                editMap = L.map('spot-edit-map').setView([lat || -0.5022, lng || 117.1536], lat ? 15 : 13);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '© OpenStreetMap © CARTO', maxZoom: 19
                }).addTo(editMap);

                const pinIcon = L.divIcon({
                    className: '',
                    html: '<div style="width:16px;height:16px;background:#43664c;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.3)"></div>',
                    iconSize: [16,16], iconAnchor: [8,8]
                });
                if (lat && lng) editMarker = L.marker([lat, lng], { icon: pinIcon }).addTo(editMap);

                editMap.on('click', function(e) {
                    document.getElementById('edit-spot-lat').value = e.latlng.lat.toFixed(6);
                    document.getElementById('edit-spot-lng').value = e.latlng.lng.toFixed(6);
                    if (editMarker) editMap.removeLayer(editMarker);
                    editMarker = L.marker([e.latlng.lat, e.latlng.lng], { icon: pinIcon }).addTo(editMap);
                });
                editMap.invalidateSize();
            }, 200);
        }

        /* ── Init the Geoman drawing map ─────────────────── */
        function initDrawMap(lat, lng, existingPolygon, existingRoute) {
            if (drawMap) { drawMap.remove(); drawMap = null; polygonLayer = null; routeLayer = null; }

            setTimeout(() => {
                drawMap = L.map('spot-draw-map').setView([lat || -0.5022, lng || 117.1536], lat ? 15 : 13);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '© OpenStreetMap © CARTO', maxZoom: 19
                }).addTo(drawMap);

                // Reference marker (non-draggable)
                const pinIcon = L.divIcon({
                    className: '',
                    html: '<div style="width:14px;height:14px;background:#43664c;border:2px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.25)"></div>',
                    iconSize: [14,14], iconAnchor: [7,7]
                });
                if (lat && lng) pinMarker = L.marker([lat, lng], { icon: pinIcon, interactive: false }).addTo(drawMap);

                // ── Enable Geoman controls ────────────────────
                drawMap.pm.addControls({
                    position: 'topleft',
                    drawMarker:    false,
                    drawCircle:    false,
                    drawCircleMarker: false,
                    drawRectangle: true,
                    drawPolygon:   true,
                    drawPolyline:  true,
                    drawText:      false,
                    editMode:      true,
                    dragMode:      true,
                    cutPolygon:    false,
                    removalMode:   true,
                });

                // Style overrides
                drawMap.pm.setGlobalOptions({
                    pathOptions: { color: '#43664c', fillColor: '#7ba082', fillOpacity: 0.25, weight: 2.5 }
                });

                // ── Load existing polygon ─────────────────────
                if (existingPolygon) {
                    try {
                        const geo = typeof existingPolygon === 'string' ? JSON.parse(existingPolygon) : existingPolygon;
                        polygonLayer = L.geoJSON(geo, {
                            style: { color: '#43664c', fillColor: '#7ba082', fillOpacity: 0.25, weight: 2 }
                        }).addTo(drawMap);
                        document.getElementById('edit_polygon_geojson').value = JSON.stringify(geo);
                        updateStatus();
                    } catch(e) { console.warn('Existing polygon load error', e); }
                }

                // ── Load existing route ───────────────────────
                if (existingRoute) {
                    try {
                        const geo = typeof existingRoute === 'string' ? JSON.parse(existingRoute) : existingRoute;
                        routeLayer = L.geoJSON(geo, {
                            style: { color: '#3b82f6', weight: 3, dashArray: '8 5' }
                        }).addTo(drawMap);
                        document.getElementById('edit_route_geojson').value = JSON.stringify(geo);
                        updateStatus();
                    } catch(e) { console.warn('Existing route load error', e); }
                }

                // ── fitBounds on existing layers ─────────────
                const bounds = [];
                if (lat && lng) bounds.push([lat, lng]);
                if (polygonLayer) { try { const b = polygonLayer.getBounds(); bounds.push(b.getNorthEast(), b.getSouthWest()); } catch(e){} }
                if (routeLayer)   { try { const b = routeLayer.getBounds();   bounds.push(b.getNorthEast(), b.getSouthWest()); } catch(e){} }
                if (bounds.length > 1) {
                    try { drawMap.fitBounds(L.latLngBounds(bounds), { padding: [20, 20], maxZoom: 17 }); } catch(e){}
                }

                drawMap.invalidateSize();

                // ── Geoman events ─────────────────────────────
                // After creating any shape
                drawMap.on('pm:create', function(e) {
                    const layer = e.layer;
                    const type  = e.shape;

                    if (type === 'Polygon' || type === 'Rectangle') {
                        // Remove old polygon if exists
                        if (polygonLayer) drawMap.removeLayer(polygonLayer);
                        layer.setStyle({ color:'#43664c', fillColor:'#7ba082', fillOpacity:0.25, weight:2 });
                        polygonLayer = layer;
                        document.getElementById('edit_polygon_geojson').value = JSON.stringify(layer.toGeoJSON());

                    } else if (type === 'Line' || type === 'Polyline') {
                        if (routeLayer) drawMap.removeLayer(routeLayer);
                        layer.setStyle({ color:'#3b82f6', weight:3, dashArray:'8 5' });
                        routeLayer = layer;
                        document.getElementById('edit_route_geojson').value = JSON.stringify(layer.toGeoJSON());
                    }
                    updateStatus();
                });

                // After editing existing shape
                drawMap.on('pm:edit', function(e) {
                    const layer = e.layer;
                    if (!layer || !layer.toGeoJSON) return;
                    const gj = layer.toGeoJSON();
                    if (layer === polygonLayer) {
                        document.getElementById('edit_polygon_geojson').value = JSON.stringify(gj);
                    } else if (layer === routeLayer) {
                        document.getElementById('edit_route_geojson').value = JSON.stringify(gj);
                    }
                    updateStatus();
                });

                // After removing a shape
                drawMap.on('pm:remove', function(e) {
                    const layer = e.layer;
                    if (layer === polygonLayer) {
                        polygonLayer = null;
                        document.getElementById('edit_polygon_geojson').value = '';
                    } else if (layer === routeLayer) {
                        routeLayer = null;
                        document.getElementById('edit_route_geojson').value = '';
                    }
                    updateStatus();
                });

            }, 250);
        }

        /* ── Status label update ─────────────────────────── */
        function updateStatus() {
            const parts = [];
            if (document.getElementById('edit_polygon_geojson').value) parts.push('Zone ✓');
            if (document.getElementById('edit_route_geojson').value)   parts.push('Route ✓');
            document.getElementById('draw-status').textContent = parts.join('  ');
        }

        /* ── Clear a specific geometry layer ─────────────── */
        function clearDrawLayer(type) {
            if (type === 'polygon' && polygonLayer) {
                if (drawMap) drawMap.removeLayer(polygonLayer);
                polygonLayer = null;
                document.getElementById('edit_polygon_geojson').value = '';
            } else if (type === 'route' && routeLayer) {
                if (drawMap) drawMap.removeLayer(routeLayer);
                routeLayer = null;
                document.getElementById('edit_route_geojson').value = '';
            }
            updateStatus();
        }

        /* ── Open Edit Modal ──────────────────────────────── */
        function openSpotEditModal(id, name, category, lat, lng, description, isSponsored, promoDetail, imageUrl, polygonGeo, routeGeo) {
            const baseUrl = '{{ url("admin/spatial-data/spot") }}/' + id;
            document.getElementById('spot-edit-form').action = baseUrl;

            document.getElementById('edit-spot-name').value        = name;
            document.getElementById('edit-spot-lat').value         = lat;
            document.getElementById('edit-spot-lng').value         = lng;
            document.getElementById('edit-spot-desc').value        = description || '';
            document.getElementById('edit-spot-sponsored').checked = isSponsored;
            document.getElementById('edit-spot-promo').value       = promoDetail || '';
            document.getElementById('edit-promo-wrapper').classList.toggle('hidden', !isSponsored);

            // Pre-fill hidden GeoJSON fields
            document.getElementById('edit_polygon_geojson').value = polygonGeo || '';
            document.getElementById('edit_route_geojson').value   = routeGeo   || '';
            updateStatus();

            // Category
            const sel = document.getElementById('edit-spot-category');
            for (let opt of sel.options) { opt.selected = (opt.value === category); }

            // Image preview
            const previewWrap = document.getElementById('edit-img-preview-wrap');
            const previewImg  = document.getElementById('edit-img-preview');
            if (imageUrl) { previewImg.src = imageUrl; previewWrap.classList.remove('hidden'); }
            else          { previewWrap.classList.add('hidden'); }
            document.getElementById('edit-spot-image').value = '';

            openModal('spot-edit-modal');
            initEditMap(parseFloat(lat), parseFloat(lng));

            // Parse GeoJSON for drawing map
            let parsedPolygon = null, parsedRoute = null;
            try { if (polygonGeo) parsedPolygon = JSON.parse(polygonGeo); } catch(e){}
            try { if (routeGeo)   parsedRoute   = JSON.parse(routeGeo);   } catch(e){}
            initDrawMap(parseFloat(lat), parseFloat(lng), parsedPolygon, parsedRoute);
        }

        /* ── Sponsored toggle in edit modal ─────────────── */
        document.getElementById('edit-spot-sponsored').addEventListener('change', function() {
            document.getElementById('edit-promo-wrapper').classList.toggle('hidden', !this.checked);
        });

        /* ── Image preview for edit modal ───────────────── */
        function previewEditImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('edit-img-preview').src = e.target.result;
                    document.getElementById('edit-img-preview-wrap').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-app-layout>

