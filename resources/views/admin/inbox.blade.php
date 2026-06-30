<x-app-layout>
    <x-slot name="title">Admin Inbox — Contact Messages</x-slot>
    @section('page-title', 'Admin Inbox')

    <div class="px-6 py-8 max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#c5eccb">
                        <span class="material-symbols-outlined text-[#43664c]" style="font-size:16px">mail</span>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#43664c]">Admin Inbox</span>
                </div>
                <h1 class="text-3xl font-bold text-[#141b2b] tracking-tight">Contact Messages</h1>
                <p class="text-[#727971] mt-1 text-[15px]">
                    <span class="font-bold text-[#43664c]">{{ $messages->count() }}</span> total message{{ $messages->count() !== 1 ? 's' : '' }}
                    @if($unreadCount > 0)
                        <span class="ml-2 text-[12px] font-bold bg-[#43664c] text-white px-2 py-0.5 rounded-full">{{ $unreadCount }} new</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" id="inbox-back-btn"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[13px] font-semibold border border-[#e5e7eb] text-[#424842] hover:border-[#7ba082] hover:text-[#43664c] transition-colors">
                <span class="material-symbols-outlined" style="font-size:18px">dashboard</span>
                Dashboard
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-[#f0faf2] border border-[#7ba082] text-[#43664c] rounded-xl px-4 py-3 text-[13px] font-semibold">
            <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        @if($messages->isEmpty())
            <div class="bg-white border border-[#e5e7eb] rounded-2xl p-16 text-center">
                <span class="material-symbols-outlined text-[#c2c8c0]" style="font-size:56px">mail_outline</span>
                <h2 class="text-xl font-bold text-[#141b2b] mt-4">No messages yet</h2>
                <p class="text-[#727971] text-sm mt-2">When users submit the contact form, their messages will appear here.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($messages as $message)
                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6 hover:border-[#7ba082] transition-all"
                     id="inbox-msg-{{ $message->id }}">
                    <div class="flex items-start justify-between gap-4">
                        {{-- Sender Info --}}
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg font-bold flex-shrink-0"
                                 style="background:#7ba082">
                                {{ strtoupper(substr($message->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-[15px] font-bold text-[#141b2b]">{{ $message->name }}</p>
                                    <a href="mailto:{{ $message->email }}"
                                       class="text-[12px] text-[#43664c] hover:underline">{{ $message->email }}</a>
                                </div>
                                <p class="text-[11px] text-[#727971] mt-0.5">
                                    {{ $message->created_at->format('d M Y, H:i') }} ·
                                    {{ $message->created_at->diffForHumans() }}
                                </p>
                                <p class="text-[14px] text-[#424842] mt-3 leading-relaxed">{{ $message->message }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <a href="mailto:{{ $message->email }}" id="inbox-reply-{{ $message->id }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-semibold border border-[#7ba082] text-[#43664c] hover:bg-[#f1f3ff] transition-colors">
                                <span class="material-symbols-outlined" style="font-size:14px">reply</span>
                                Reply
                            </a>
                            <form method="POST" action="{{ route('admin.inbox.destroy', $message->id) }}" id="inbox-delete-{{ $message->id }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this message?')"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-semibold border border-red-100 text-red-500 hover:bg-red-50 transition-colors w-full">
                                    <span class="material-symbols-outlined" style="font-size:14px">delete</span>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
