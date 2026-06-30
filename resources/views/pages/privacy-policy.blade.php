{{-- resources/views/pages/privacy-policy.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy — Walkture</title>
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
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" id="privacy-back-dashboard" class="font-semibold text-[#43664c] hover:underline">← Back to Dashboard</a>
        @else
            <a href="{{ route('login') }}" id="privacy-login-link" class="font-semibold text-[#43664c] hover:underline">Sign In</a>
        @endauth
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-14">
    <div class="mb-10">
        <span class="text-[11px] font-bold uppercase tracking-widest text-[#43664c]">Legal</span>
        <h1 class="text-4xl font-bold text-[#141b2b] mt-2 tracking-tight">Privacy Policy</h1>
        <p class="text-[#727971] mt-2 text-[15px]">Last updated: {{ date('F j, Y') }}</p>
    </div>

    @foreach([
        ['title'=>'Information We Collect','body'=>'We collect information you provide directly to us, such as when you create an account, use our map features, or contact us. This includes name, email address, and usage data related to GIS features.'],
        ['title'=>'How We Use Your Information','body'=>'We use the information we collect to operate and improve Walkture, send you updates, provide customer support, and comply with legal obligations.'],
        ['title'=>'Data Sharing','body'=>'We do not sell your personal data. We may share data with service providers who assist us in operating Walkture, subject to confidentiality agreements.'],
        ['title'=>'Data Security','body'=>'We implement industry-standard security measures to protect your data. Our platform uses Supabase with row-level security policies.'],
        ['title'=>'Your Rights','body'=>'You have the right to access, correct, or delete your personal data. Contact us at the address below to exercise these rights.'],
        ['title'=>'Contact Us','body'=>'For privacy-related questions, reach us via our Contact Support page or email privacy@walkture.id.'],
    ] as $section)
    <div class="mb-8">
        <h2 class="text-lg font-bold text-[#141b2b] mb-2">{{ $section['title'] }}</h2>
        <p class="text-[#424842] text-[15px] leading-relaxed">{{ $section['body'] }}</p>
    </div>
    @endforeach

    <div class="mt-10 flex gap-4 text-[13px]">
        <a href="{{ route('guest.terms') }}" id="privacy-to-terms" class="text-[#43664c] font-semibold hover:underline">Terms of Service →</a>
        <a href="{{ route('guest.contact') }}" id="privacy-to-contact" class="text-[#727971] hover:text-[#43664c] hover:underline">Contact Support</a>
    </div>
</main>
</body>
</html>
