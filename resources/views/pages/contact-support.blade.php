{{-- resources/views/pages/contact-support.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Support — Walkture</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Walkture Logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700&family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>* { font-family:'Be Vietnam Pro',sans-serif; } .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;vertical-align:middle;} .auth-input{width:100%;padding:11px 14px;border:1px solid #c2c8c0;border-radius:10px;background:#fff;font-size:14px;color:#141b2b;outline:none;transition:border-color .15s,box-shadow .15s;} .auth-input:focus{border-color:#7ba082;box-shadow:0 0 0 3px rgba(123,160,130,.18);}</style>
</head>
<body style="background:#f9f9ff;color:#141b2b">

<header class="sticky top-0 z-40 flex items-center gap-3 px-6 h-16 border-b border-[#e5e7eb]" style="background:rgba(249,249,255,.92);backdrop-filter:blur(10px)">
    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#43664c">
        <span class="material-symbols-outlined text-white" style="font-size:14px">route</span>
    </div>
    <span class="font-bold text-[15px] text-[#141b2b]">Walkture</span>
    <div class="ml-auto flex gap-4 text-[13px]">
        @auth
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" id="contact-back-dashboard" class="font-semibold text-[#43664c] hover:underline">← Dashboard</a>
        @else
            <a href="{{ route('login') }}" id="contact-login-link" class="font-semibold text-[#43664c] hover:underline">Sign In</a>
        @endauth
    </div>
</header>

<main class="max-w-5xl mx-auto px-6 py-14">
    <div class="mb-10 text-center">
        <span class="text-[11px] font-bold uppercase tracking-widest text-[#43664c]">Help & Support</span>
        <h1 class="text-4xl font-bold text-[#141b2b] mt-2 tracking-tight">Contact Support</h1>
        <p class="text-[#727971] mt-2 text-[15px] max-w-md mx-auto">Have a question or issue? We're here to help. Reach out and we'll respond within 24 hours.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Contact channels --}}
        <div class="space-y-4">
            @foreach([
                ['icon'=>'email','title'=>'Email Us','detail'=>'hello@walkture.id','sub'=>'Response within 24h'],
                ['icon'=>'chat','title'=>'WhatsApp','detail'=>'+62 811-5555-888','sub'=>'Mon–Sat, 9am–5pm WITA'],
                ['icon'=>'location_on','title'=>'Office','detail'=>'Samarinda, Kalimantan Timur','sub'=>'Indonesia'],
            ] as $ch)
            <div class="bg-white border border-[#e5e7eb] rounded-2xl p-5 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#c5eccb">
                    <span class="material-symbols-outlined" style="color:#43664c">{{ $ch['icon'] }}</span>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-[#141b2b]">{{ $ch['title'] }}</p>
                    <p class="text-[13px] text-[#43664c] font-semibold mt-0.5">{{ $ch['detail'] }}</p>
                    <p class="text-[11px] text-[#727971] mt-0.5">{{ $ch['sub'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Contact form --}}
        <div class="lg:col-span-2 bg-white border border-[#e5e7eb] rounded-2xl p-8">
            <h2 class="text-[17px] font-bold text-[#141b2b] mb-6">Send a Message</h2>

            {{-- Success flash --}}
            @if(session('success'))
                <div class="mb-5 px-4 py-3 bg-[#c5eccb] border border-[#a9d0b0] rounded-xl text-sm text-[#14361f] flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:18px;color:#43664c">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error flash --}}
            @if(session('error'))
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:18px;color:#ba1a1a">error</span>
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('guest.contact.store') }}" id="contact-form">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Full Name</label>
                        <input type="text" name="name" id="contact-name" class="auth-input" placeholder="Your name" required value="{{ old('name', auth()->user()->name ?? '') }}">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Email Address</label>
                        <input type="email" name="email" id="contact-email" class="auth-input" placeholder="you@example.com" required value="{{ old('email', auth()->user()->email ?? '') }}">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Subject</label>
                    <input type="text" name="subject" id="contact-subject" class="auth-input" placeholder="What is your issue about?" required>
                </div>
                <div class="mb-6">
                    <label class="block text-[13px] font-semibold text-[#141b2b] mb-1.5">Message</label>
                    <textarea name="message" id="contact-message" rows="5" class="auth-input" placeholder="Describe your question or issue in detail..." required style="resize:vertical"></textarea>
                </div>
                <button type="submit" id="contact-submit-btn"
                        class="px-8 py-3 rounded-xl text-sm font-bold text-white transition-colors"
                        style="background:#43664c"
                        onmouseover="this.style.background='#2c4e35'" onmouseout="this.style.background='#43664c'">
                    Send Message
                </button>
            </form>
        </div>
    </div>

    <div class="flex justify-center gap-6 mt-12 text-[13px] text-[#727971]">
        <a href="{{ route('guest.privacy') }}" id="contact-privacy-link" class="hover:text-[#43664c]">Privacy Policy</a>
        <a href="{{ route('guest.terms') }}" id="contact-terms-link" class="hover:text-[#43664c]">Terms of Service</a>
    </div>
</main>
</body>
</html>
