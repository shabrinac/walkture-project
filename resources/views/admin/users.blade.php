<x-app-layout>
    <x-slot name="title">User Management</x-slot>
    @section('page-title', 'User Management')

    <div class="px-6 py-8 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#141b2b] tracking-tight">User Management</h1>
                <p class="text-[#727971] mt-1 text-[15px]">
                    <span class="font-bold text-[#43664c]">{{ $users->total() }}</span> registered platform members.
                </p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            @php
                $admins = $totalAdmins;
                $regularUsers = $totalUsers;
            @endphp
            @foreach([
                ['label'=>'Total Members','value'=>$users->total(),'icon'=>'group','color'=>'#43664c'],
                ['label'=>'Administrators','value'=>$admins,'icon'=>'admin_panel_settings','color'=>'#43664c'],
                ['label'=>'Regular Users','value'=>$regularUsers,'icon'=>'person','color'=>'#5c5f60'],
                ['label'=>'This Month','value'=>'—','icon'=>'person_add','color'=>'#43664c'],
            ] as $s)
            <div class="bg-white border border-[#e5e7eb] rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#c5eccb">
                    <span class="material-symbols-outlined" style="color:{{ $s['color'] }}">{{ $s['icon'] }}</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#141b2b]">{{ $s['value'] }}</p>
                    <p class="text-[12px] text-[#727971]">{{ $s['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Users Table --}}
        <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-[#f1f3ff] flex items-center justify-between flex-wrap gap-4">
                <div class="flex gap-2">
                    <button id="users-filter-all" onclick="filterUsers('all')"
                            class="px-3 py-1.5 rounded-xl text-[12px] font-bold border transition-colors"
                            style="background:#43664c;color:#fff;border-color:#43664c">All</button>
                    <button id="users-filter-user" onclick="filterUsers('user')"
                            class="px-3 py-1.5 rounded-xl text-[12px] font-bold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">Users</button>
                    <button id="users-filter-admin" onclick="filterUsers('admin')"
                            class="px-3 py-1.5 rounded-xl text-[12px] font-bold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">Admins</button>
                </div>
                <input type="text" id="users-search" placeholder="Search by name or email…"
                       class="px-3 py-2 border border-[#c2c8c0] rounded-xl text-[13px] outline-none w-full sm:w-64"
                       onfocus="this.style.borderColor='#7ba082'" onblur="this.style.borderColor='#c2c8c0'"
                       oninput="searchUsers(this.value)">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#f1f3ff]">
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">User</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Email</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Role</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Joined</th>
                            <th class="text-left px-6 py-3 text-[11px] font-bold uppercase tracking-widest text-[#727971]">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        @forelse($users as $user)
                        <tr class="border-b border-[#f1f3ff] hover:bg-[#f9f9ff] transition-colors user-row"
                            id="user-row-{{ $user->id }}"
                            data-role="{{ $user->role }}"
                            data-self="{{ auth()->id() == $user->id ? '1' : '0' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                         style="background:{{ $user->isAdmin() ? '#43664c' : '#7ba082' }}">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[14px] font-semibold text-[#141b2b]">
                                            {{ $user->name }}
                                            @if(auth()->id() == $user->id)
                                                <span class="ml-1 text-[10px] text-[#727971]">(you)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-[13px] text-[#727971]">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-bold px-2 py-1 rounded-full {{ $user->isAdmin() ? 'bg-[#c5eccb] text-[#43664c]' : 'bg-[#f1f3ff] text-[#727971]' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[12px] text-[#727971]">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if(auth()->id() !== $user->id)
                                <div class="flex gap-2">
                                    <button id="user-role-{{ $user->id }}" type="button"
                                            class="px-3 py-1.5 rounded-lg text-[12px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                                        {{ $user->isAdmin() ? '→ User' : '→ Admin' }}
                                    </button>
                                </div>
                                @else
                                <span class="text-[12px] text-[#c2c8c0]">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-[13px] text-[#727971]">No users registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-[#f1f3ff]">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        let currentRole = 'all';
        function filterUsers(role) {
            currentRole = role;
            applyFilters();
        }
        function searchUsers(q) { applyFilters(q); }
        function applyFilters(q = document.getElementById('users-search').value) {
            document.querySelectorAll('.user-row').forEach(row => {
                const roleMatch = currentRole === 'all' || row.dataset.role === currentRole;
                const textMatch = row.textContent.toLowerCase().includes(q.toLowerCase());
                row.style.display = (roleMatch && textMatch) ? '' : 'none';
            });
        }
    </script>
    @endpush
</x-app-layout>
