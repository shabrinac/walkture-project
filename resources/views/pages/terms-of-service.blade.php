{{-- resources/views/pages/terms-of-service.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service — Walkture</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700&family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>* { font-family:'Be Vietnam Pro',sans-serif; } .material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;vertical-align:middle;}</style>
</head>
<body style="background:#f9f9ff;color:#141b2b">

<header class="sticky top-0 z-40 flex items-center gap-3 px-6 h-16 border-b border-[#e5e7eb]" style="background:rgba(249,249,255,.92);backdrop-filter:blur(10px)">
    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#43664c">
        <span class="material-symbols-outlined text-white" style="font-size:14px">route</span>
    </div>
    <span class="font-bold text-[15px] text-[#141b2b]">Walkture</span>
    <div class="ml-auto flex gap-4 text-[13px]">
        @auth
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" id="terms-back-dashboard" class="font-semibold text-[#43664c] hover:underline">← Back to Dashboard</a>
        @else
            <a href="{{ route('login') }}" id="terms-login-link" class="font-semibold text-[#43664c] hover:underline">Sign In</a>
        @endauth
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-14">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-widest text-[#43664c]">Legal</span>
        <h1 class="text-4xl font-bold text-[#141b2b] mt-2 tracking-tight">Terms of Service</h1>
        <p class="text-[#727971] mt-2 text-[15px]">Last updated: {{ date('F j, Y') }}</p>
    </div>

    @foreach([
        ['title'=>'Acceptance of Terms','body'=>'By accessing or using Walkture, you agree to be bound by these Terms. If you do not agree, please do not use our platform.'],
        ['title'=>'User Accounts','body'=>'You are responsible for maintaining the confidentiality of your account credentials. You must notify us immediately of any unauthorized use of your account.'],
        ['title'=>'Permitted Use','body'=>'Walkture is intended for personal and educational use in the context of photography and GIS exploration. Commercial redistribution of map data is prohibited without written consent.'],
        ['title'=>'Content & Intellectual Property','body'=>'User-submitted content remains your property. By submitting content, you grant Walkture a license to display it on the platform. We respect intellectual property rights.'],

        ['title'=>'Limitation of Liability','body'=>'Walkture is provided "as is." We are not liable for any indirect, incidental, or consequential damages arising from your use of the platform.'],
        ['title'=>'Changes to Terms','body'=>'We reserve the right to modify these Terms at any time. Continued use of the platform after changes constitutes acceptance of the revised Terms.'],
    ] as $section)
    <div class="mb-8">
        <h2 class="text-lg font-bold text-[#141b2b] mb-2">{{ $section['title'] }}</h2>
        <p class="text-[#424842] text-[15px] leading-relaxed">{{ $section['body'] }}</p>
    </div>
    @endforeach

    <div class="mt-10 flex gap-4 text-[13px]">
        <a href="{{ route('guest.privacy') }}" id="terms-to-privacy" class="text-[#43664c] font-semibold hover:underline">Privacy Policy →</a>
        <a href="{{ route('guest.contact') }}" id="terms-to-contact" class="text-[#727971] hover:text-[#43664c] hover:underline">Contact Support</a>
    </div>
</main>
</body>
</html>
