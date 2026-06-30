<x-app-layout>
    <x-slot name="title">Add Route / Zone</x-slot>
    @section('page-title', 'Add Route / Zone')

    <div class="px-6 py-8 max-w-3xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('admin.spatial-data') }}" class="hover:text-[#43664c] font-semibold transition-colors">Spatial Data</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold">Add Route / Zone</span>
        </div>

        <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-[#f1f3ff] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#c5eccb">
                    <span class="material-symbols-outlined" style="color:#43664c">layers</span>
                </div>
                <div>
                    <h1 class="text-[17px] font-bold text-[#141b2b]">Add Route or Zone</h1>
                    <p class="text-[12px] text-[#727971]">Creates a GIS polygon or polyline overlay on the map.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.areas.store') }}" class="px-6 py-6 space-y-5">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-[13px] text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined" style="font-size:14px">error</span>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                {{-- Name & Type --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="area-name" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Name <span class="text-red-400">*</span></label>
                        <input id="area-name" name="name" type="text" required value="{{ old('name') }}"
                               placeholder="e.g. Samarinda Heritage Walk"
                               class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                               onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    </div>
                    <div>
                        <label for="area-type" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Type <span class="text-red-400">*</span></label>
                        <select id="area-type" name="type" required
                                class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none cursor-pointer transition-colors"
                                onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                            <option value="">Select type…</option>
                            <option value="route" {{ old('type') === 'route' ? 'selected' : '' }}>Route (Polyline)</option>
                            <option value="zone"  {{ old('type') === 'zone'  ? 'selected' : '' }}>Zone (Polygon)</option>
                        </select>
                    </div>
                </div>

                {{-- Distance or Area --}}
                <div>
                    <label for="area-distance" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">Distance / Area (km)</label>
                    <input id="area-distance" name="distance_or_area" type="number" step="0.01" min="0" value="{{ old('distance_or_area') }}"
                           placeholder="e.g. 2.5"
                           class="w-full sm:w-48 px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[14px] outline-none transition-colors"
                           onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">
                    <p class="text-[11px] text-[#727971] mt-1">Leave blank if unknown.</p>
                </div>

                {{-- GeoJSON --}}
                <div>
                    <label for="area-geo" class="block text-[12px] font-bold text-[#424842] uppercase tracking-widest mb-1.5">GeoJSON Data</label>
                    <textarea id="area-geo" name="geo_data" rows="8"
                              placeholder='{"type":"Feature","geometry":{"type":"LineString","coordinates":[[117.14,−0.50],[117.16,−0.50]]}}'
                              class="w-full px-3 py-2.5 border border-[#c2c8c0] rounded-xl text-[13px] font-mono outline-none resize-y transition-colors"
                              onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'">{{ old('geo_data') }}</textarea>
                    <p class="text-[11px] text-[#727971] mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined" style="font-size:13px">info</span>
                        Paste a valid GeoJSON Feature or FeatureCollection. Use
                        <a href="https://geojson.io" target="_blank" class="text-[#43664c] underline ml-0.5">geojson.io</a> to create geometry visually.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-[#f1f3ff]">
                    <a href="{{ route('admin.spatial-data') }}" id="area-form-cancel"
                       class="px-5 py-2.5 rounded-xl text-[13px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="area-form-submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[13px] font-bold text-white transition-all hover:opacity-90"
                            style="background:#43664c">
                        <span class="material-symbols-outlined" style="font-size:18px">layers</span>
                        Save Route / Zone
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
