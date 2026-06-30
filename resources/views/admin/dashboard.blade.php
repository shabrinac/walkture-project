<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    @section('page-title', 'Admin Dashboard')

    <div class="px-6 py-8 max-w-7xl mx-auto">

        {{-- ── Admin Header ────────────────────────────────────────── --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#43664c">
                    <span class="material-symbols-outlined text-white" style="font-size:16px">admin_panel_settings</span>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#43664c]">Walkture Admin</span>
            </div>
            <h1 class="text-3xl font-bold text-[#141b2b] tracking-tight">Control Center</h1>
            <p class="text-[#727971] text-sm mt-1">Platform overview and management tools for Walkture GIS.</p>
        </div>

        {{-- ── Metric Cards ─────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-10">
            @php
                $metrics = [
                    ['icon'=>'location_on', 'label'=>'Photo Spots', 'value'=>$stats['total_spots'], 'sub'=>'Map markers', 'color'=>'#43664c', 'bg'=>'#c5eccb', 'route'=>'admin.spatial-data'],
                    ['icon'=>'star', 'label'=>'Sponsored Spots', 'value'=>$stats['sponsored_spots'], 'sub'=>'Paid listings', 'color'=>'#b45309', 'bg'=>'#fef3c7', 'route'=>'admin.spatial-data'],
                    ['icon'=>'layers', 'label'=>'Spatial Areas', 'value'=>$stats['spatial_areas'], 'sub'=>'Routes & zones', 'color'=>'#43664c', 'bg'=>'#c5eccb', 'route'=>'admin.spatial-data'],
                    ['icon'=>'photo_camera', 'label'=>'Photographers', 'value'=>$stats['photographers'], 'sub'=>'Active listings', 'color'=>'#5c5f60', 'bg'=>'#e1e3e4', 'route'=>'admin.directory'],
                    ['icon'=>'group', 'label'=>'Registered Users', 'value'=>$stats['total_users'], 'sub'=>'Platform members', 'color'=>'#43664c', 'bg'=>'#c5eccb', 'route'=>'admin.users'],
                ];
            @endphp

            @foreach($metrics as $m)
            <a href="{{ route($m['route']) }}" id="metric-card-{{ $loop->index }}"
               class="bg-white border border-[#e5e7eb] rounded-2xl p-5 hover:shadow-md hover:border-[#7ba082] transition-all group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background:{{ $m['bg'] }}">
                    <span class="material-symbols-outlined" style="color:{{ $m['color'] }}">{{ $m['icon'] }}</span>
                </div>
                <p class="text-3xl font-bold text-[#141b2b]">{{ $m['value'] }}</p>
                <p class="text-[13px] font-semibold text-[#141b2b] mt-1">{{ $m['label'] }}</p>
                <p class="text-[11px] text-[#727971] mt-0.5">{{ $m['sub'] }}</p>
            </a>
            @endforeach
        </div>

        {{-- ── Quick Action Buttons ─────────────────────────────────── --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6 mb-8">
            <h2 class="text-[15px] font-bold text-[#141b2b] mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @php
                    $actions = [
                        ['icon'=> 'add_location_alt', 'label'=> 'Add Spot',         'route'=> 'admin.spots.create'],
                        ['icon'=> 'layers',            'label'=> 'Manage GIS',       'route'=> 'admin.spatial-data'],
                        ['icon'=> 'person_add',        'label'=> 'Add Photographer', 'route'=> 'admin.directory.create'],
                        ['icon'=> 'manage_accounts',   'label'=> 'Users',            'route'=> 'admin.users'],
                    ];
                @endphp
                @foreach($actions as $action)
                <a href="{{ route($action['route']) }}" id="quick-action-{{ $loop->index }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border border-[#e5e7eb] hover:bg-[#f1f3ff] hover:border-[#7ba082] transition-all text-center group">
                    <span class="material-symbols-outlined text-[#43664c]" style="font-size:24px">{{ $action['icon'] }}</span>
                    <span class="text-[12px] font-semibold text-[#424842] group-hover:text-[#43664c]">{{ $action['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- ── Two Column: Recent Activity ─────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Recent Spots --}}
            <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-[15px] font-bold text-[#141b2b]">Recent Spots</h2>
                    <a href="{{ route('admin.spatial-data') }}" id="admin-view-all-spots"
                       class="text-[12px] font-semibold text-[#43664c] hover:underline">Manage all →</a>
                </div>
                @forelse($recentSpots as $spot)
                <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-[#f1f3ff]' : '' }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:{{ $spot->is_sponsored ? '#c5eccb' : '#f1f3ff' }}">
                        <span class="material-symbols-outlined" style="font-size:18px;color:{{ $spot->is_sponsored ? '#43664c' : '#727971' }}">location_on</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-[#141b2b] truncate">{{ $spot->name }}</p>
                        <p class="text-[11px] text-[#727971]">{{ $spot->category }}</p>
                    </div>
                    @if($spot->is_sponsored)
                        <span class="text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-2 py-0.5 rounded-full flex-shrink-0">Sponsored</span>
                    @endif
                </div>
                @empty
                <p class="text-sm text-[#727971] py-4 text-center">No spots added yet.</p>
                @endforelse
            </div>

            {{-- Recent Photographers --}}
            <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-[15px] font-bold text-[#141b2b]">Recent Photographers</h2>
                    <a href="{{ route('admin.directory') }}" id="admin-view-all-photographers"
                       class="text-[12px] font-semibold text-[#43664c] hover:underline">Manage all →</a>
                </div>
                @forelse($recentPhotographers as $photographer)
                <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-[#f1f3ff]' : '' }}">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-white text-sm font-bold"
                         style="background:#7ba082">
                        {{ strtoupper(substr($photographer->full_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-[#141b2b] truncate">{{ $photographer->full_name }}</p>
                        <p class="text-[11px] text-[#727971]">{{ $photographer->specialty }}</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0"
                          style="{{ $photographer->is_active ? 'background:#c5eccb;color:#43664c' : 'background:#f1f3ff;color:#727971' }}">
                        {{ $photographer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-[#727971] py-4 text-center">No photographers listed yet.</p>
                @endforelse
            </div>

        </div>

        {{-- ── Management Links Grid ────────────────────────────────── --}}
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $managementLinks = [
                    ['icon'=>'layers',      'title'=>'Spatial Data Management',   'desc'=>'Manage GIS routes, zones and photo spot markers.','route'=>'admin.spatial-data', 'badge'=>'GIS'],
                    ['icon'=>'photo_camera','title'=>'Photographer Directory',    'desc'=>'Manage active photographer listings.',            'route'=>'admin.directory',     'badge'=>null],
                    ['icon'=>'group',       'title'=>'User Management',           'desc'=>'View and manage registered platform users.',      'route'=>'admin.users',         'badge'=>null],
                    ['icon'=>'mail',        'title'=>'Contact Inbox',             'desc'=>'Read and manage contact support messages.',       'route'=>'admin.inbox',         'badge'=>null],
                ];
            @endphp
            @foreach($managementLinks as $link)
            <a href="{{ route($link['route']) }}" id="mgmt-link-{{ $loop->index }}"
               class="bg-white border border-[#e5e7eb] rounded-2xl p-5 hover:shadow-md hover:border-[#7ba082] transition-all group flex gap-4 items-start">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#f1f3ff">
                    <span class="material-symbols-outlined text-[#43664c]">{{ $link['icon'] }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-[14px] font-bold text-[#141b2b] group-hover:text-[#43664c]">{{ $link['title'] }}</p>
                        @if($link['badge'])
                            <span class="text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-1.5 py-0.5 rounded-full">{{ $link['badge'] }}</span>
                        @endif
                    </div>
                    <p class="text-[12px] text-[#727971] mt-0.5 leading-relaxed">{{ $link['desc'] }}</p>
                </div>
                <span class="material-symbols-outlined text-[#c2c8c0] group-hover:text-[#43664c] transition-colors flex-shrink-0" style="font-size:18px">arrow_forward</span>
            </a>
            @endforeach
        </div>

    </div>
</x-app-layout>
