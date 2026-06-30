<x-app-layout>
    <x-slot name="title">{{ $photographer->full_name }} — Photographer</x-slot>
    @section('page-title', $photographer->full_name)

    @push('head')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Apply Poppins to package section — but NOT to icon spans */
        .packages-section *:not(.material-symbols-outlined) { font-family: 'Poppins', sans-serif; }

        /* Protect Material Symbols from any font override */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined' !important;
            font-weight: normal !important;
            font-style: normal !important;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* ── Card base ── */
        .pkg-card {
            background: #ffffff;
            border: 1.5px solid #e8ede9;
            border-radius: 20px;
            padding: 28px 24px;
            position: relative;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            display: flex;
            flex-direction: column;
        }
        .pkg-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(67,102,76,0.13);
            border-color: #7ba082;
        }

        /* ── Popular badge pulse ── */
        @keyframes pulse-badge {
            0%, 100% { box-shadow: 0 0 0 0 rgba(123,160,130,0.55); }
            50%       { box-shadow: 0 0 0 8px rgba(123,160,130,0); }
        }
        .badge-popular {
            animation: pulse-badge 2.5s ease infinite;
        }

        /* ── Featured card ── */
        .pkg-card.featured {
            background: linear-gradient(145deg, #43664c, #5e8c69);
            border-color: transparent;
            color: #ffffff;
        }
        .pkg-card.featured .pkg-price,
        .pkg-card.featured .pkg-name,
        .pkg-card.featured li { color: #ffffff !important; }
        .pkg-card.featured .pkg-desc { color: rgba(255,255,255,0.78) !important; }
        .pkg-card.featured .pkg-divider { border-color: rgba(255,255,255,0.2) !important; }
        .pkg-card.featured .pkg-tag { background: rgba(255,255,255,0.18); color: #fff; }
        .pkg-card.featured .pkg-check { color: #c5eccb !important; }
        .pkg-card.featured .pkg-cocok { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.9); }

        /* ── Student card accent ── */
        .pkg-card.student {
            border-color: #a8c5ad;
            background: linear-gradient(160deg, #fcfcff, #f0f8f2);
        }
        .pkg-card.student:hover { border-color: #43664c; }

        /* ── Addon card ── */
        .pkg-card.addon {
            background: #f9f9ff;
            border-style: dashed;
            border-color: #c5d0c7;
        }
        .pkg-card.addon:hover { border-style: solid; border-color: #7ba082; }

        /* ── Section header ── */
        .pkg-section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #43664c;
            background: #c5eccb;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 20px;
        }

        /* ── Shine effect on hover ── */
        .pkg-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(255,255,255,0.07) 0%, transparent 60%);
            pointer-events: none;
        }

        /* ── Price ── */
        .pkg-price {
            font-size: 26px;
            font-weight: 800;
            color: #141b2b;
            line-height: 1.1;
        }
        .pkg-price-sub {
            font-size: 12px;
            font-weight: 500;
            color: #727971;
            margin-top: 2px;
        }

        /* ── CTA Button ── */
        .pkg-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 11px 0;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            background: #43664c;
            color: #ffffff;
            text-decoration: none;
            transition: background 0.15s ease, transform 0.15s ease;
            margin-top: auto;
        }
        .pkg-cta:hover { background: #33503b; transform: scale(1.02); }
        .pkg-card.featured .pkg-cta { background: #ffffff; color: #43664c; }
        .pkg-card.featured .pkg-cta:hover { background: #c5eccb; }
        .pkg-card.student .pkg-cta { background: linear-gradient(135deg,#43664c,#7ba082); }

        /* ── Check icon ── */
        .pkg-check { color: #43664c; font-size: 17px !important; flex-shrink: 0; }

        /* ── "Cocok untuk" pill ── */
        .pkg-cocok {
            background: #f1f8f2;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 11.5px;
            color: #43664c;
            line-height: 1.5;
        }

        /* ── Bonus badge ── */
        .pkg-bonus {
            background: linear-gradient(135deg, #c5eccb, #e8f5ea);
            color: #2d5534;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 100px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .pkg-card.featured .pkg-bonus { background: rgba(255,255,255,0.2); color: #fff; }

        /* ── Addon list item ── */
        .addon-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eef0ee;
            gap: 12px;
        }
        .addon-row:last-child { border-bottom: none; }

        /* ── Student badge ── */
        .student-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: linear-gradient(135deg, #43664c, #7ba082);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 100px;
            letter-spacing: 0.06em;
        }

        /* ── Divider ── */
        .pkg-divider { border: none; border-top: 1px solid #eaeee9; margin: 16px 0; }

        /* ── Tag ── */
        .pkg-tag {
            display: inline-block;
            background: #f0f8f2;
            color: #43664c;
            font-size: 10.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 6px;
            margin-right: 4px;
            margin-bottom: 4px;
        }

        /* ── Scroll reveal ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .pkg-card { animation: fadeInUp 0.5s ease both; }
        .pkg-delay-1 { animation-delay: 0.05s; }
        .pkg-delay-2 { animation-delay: 0.12s; }
        .pkg-delay-3 { animation-delay: 0.19s; }
        .pkg-delay-4 { animation-delay: 0.26s; }
    </style>
    @endpush

    <div class="px-4 sm:px-6 py-8 max-w-6xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[13px] text-[#727971]">
            <a href="{{ route('user.photographers') }}" class="hover:text-[#43664c] font-semibold transition-colors">Photographers</a>
            <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
            <span class="text-[#141b2b] font-semibold truncate">{{ $photographer->full_name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Profile Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-[#e5e7eb] rounded-2xl overflow-hidden sticky top-20">
                    {{-- Banner --}}
                    <div class="h-24 w-full" style="background:linear-gradient(135deg,#43664c,#7ba082)"></div>
                    <div class="px-6 pb-6 -mt-12">
                        {{-- Avatar --}}
                        <div class="mb-4">
                            @if($photographer->avatar_url)
                                <img src="{{ $photographer->avatar_url }}" alt="{{ $photographer->full_name }}"
                                     class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                            @else
                                <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-md"
                                     style="background:#7ba082">
                                    {{ strtoupper(substr($photographer->full_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <h1 class="text-xl font-bold text-[#141b2b] leading-tight">{{ $photographer->full_name }}</h1>
                        <p class="text-[12px] text-[#43664c] font-bold uppercase tracking-widest mt-1">{{ $photographer->specialty }}</p>

                        @if($photographer->paid_until && $photographer->paid_until >= now())
                            <span class="inline-block mt-2 text-[10px] font-bold bg-[#c5eccb] text-[#43664c] px-2 py-0.5 rounded-full">⭐ Featured</span>
                        @endif

                        @if($photographer->paid_until)
                        <p class="text-[11px] text-[#727971] mt-2">
                            Featured until: {{ \Carbon\Carbon::parse($photographer->paid_until)->format('d M Y') }}
                        </p>
                        @endif

                        {{-- Contact Buttons --}}
                        <div class="mt-5 space-y-2">
                            @if($photographer->whatsapp_link)
                            <a href="{{ $photographer->whatsapp_link }}" target="_blank" rel="noopener"
                               id="pg-wa-{{ $photographer->id }}"
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-[13px] font-bold text-white"
                               style="background:#25D366">
                                <span class="material-symbols-outlined" style="font-size:18px">chat</span>
                                WhatsApp
                            </a>
                            @endif
                            @if($photographer->instagram_link)
                            <a href="{{ $photographer->instagram_link }}" target="_blank" rel="noopener"
                               id="pg-ig-{{ $photographer->id }}"
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-[13px] font-bold text-white"
                               style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366)">
                                <span class="material-symbols-outlined" style="font-size:18px">photo_camera</span>
                                Instagram
                            </a>
                            @endif
                            @if($photographer->portfolio_url)
                            <a href="{{ $photographer->portfolio_url }}" target="_blank" rel="noopener"
                               id="pg-portfolio-{{ $photographer->id }}"
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-[13px] font-bold text-[#43664c] border border-[#7ba082] hover:bg-[#f1f3ff] transition-colors">
                                <span class="material-symbols-outlined" style="font-size:18px">open_in_new</span>
                                View Portfolio
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Panel --}}
            <div class="lg:col-span-2 space-y-5">

                <div class="bg-white border border-[#e5e7eb] rounded-2xl p-6">
                    <h2 class="text-[15px] font-bold text-[#141b2b] mb-4">About the Photographer</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 py-3 border-b border-[#f1f3ff]">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:20px">camera_enhance</span>
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide font-medium">Specialty</p>
                                <p class="text-[14px] font-semibold text-[#141b2b]">{{ $photographer->specialty }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 py-3 border-b border-[#f1f3ff]">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:20px">verified</span>
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide font-medium">Status</p>
                                <p class="text-[14px] font-semibold text-[#141b2b]">{{ $photographer->is_active ? 'Active & Available' : 'Currently Unavailable' }}</p>
                            </div>
                        </div>
                        @if($photographer->instagram_link)
                        <div class="flex items-center gap-3 py-3">
                            <span class="material-symbols-outlined text-[#43664c]" style="font-size:20px">link</span>
                            <div>
                                <p class="text-[11px] text-[#727971] uppercase tracking-wide font-medium">Instagram</p>
                                <a href="{{ $photographer->instagram_link }}" target="_blank"
                                   class="text-[14px] font-semibold text-[#43664c] hover:underline">View Profile ↗</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- CTA box --}}
                <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#43664c,#7ba082)">
                    <p class="text-white/80 text-[13px] mb-1">Ready to book?</p>
                    <p class="text-white text-xl font-bold mb-4">Connect with {{ $photographer->full_name }}</p>
                    @if($photographer->whatsapp_link)
                    <a href="{{ $photographer->whatsapp_link }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white rounded-xl text-[13px] font-bold text-[#43664c] hover:bg-[#f1f3ff] transition-colors">
                        <span class="material-symbols-outlined" style="font-size:18px">chat</span>
                        Send a Message
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- PRICING & PACKAGES SECTION                              --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <section class="packages-section mt-14" id="packages">

            {{-- Section Header --}}
            <div class="text-center mb-12">
                <span class="inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-[#43664c] bg-[#c5eccb] px-4 py-1.5 rounded-full mb-4">
                    <span class="material-symbols-outlined" style="font-size:14px">sell</span>
                    Transparent Pricing
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#141b2b] leading-tight mt-2">
                    Pricing &amp; Packages
                </h2>
                <p class="text-[#727971] mt-3 text-[15px] max-w-xl mx-auto">
                    Choose a package that fits your moment. All packages include edited files delivered via Google Drive.
                </p>
            </div>

            {{-- ── REGULAR PACKAGES (Dynamic from DB) ── --}}
            @php
                $pkgs = $photographer->pricing_packages ?? [];
                $tierMeta = [
                    'basic'    => ['label' => 'Basic',         'tag' => 'Basic',     'id' => 'pkg-basic',    'class' => 'pkg-delay-1',          'featured' => false],
                    'standard' => ['label' => 'Standard',      'tag' => 'Standard',  'id' => 'pkg-standard', 'class' => 'pkg-delay-2',          'featured' => false],
                    'premium'  => ['label' => 'Premium',       'tag' => 'Premium',   'id' => 'pkg-premium',  'class' => 'pkg-delay-3 featured',  'featured' => true],
                    'fullday'  => ['label' => 'Full Day Event', 'tag' => 'Full Day', 'id' => 'pkg-fullday',  'class' => 'pkg-delay-4',          'featured' => false],
                ];
            @endphp

            <div class="mb-4">
                <span class="pkg-section-label">
                    <span class="material-symbols-outlined" style="font-size:14px">star</span>
                    Regular Packages
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-14">
                @foreach($tierMeta as $key => $meta)
                @php $pkg = $pkgs[$key] ?? null; @endphp
                <div class="pkg-card {{ $meta['class'] }}" id="{{ $meta['id'] }}">
                    @if($meta['featured'])
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 badge-popular">
                        <span style="background:linear-gradient(135deg,#f09433,#e6683c);color:#fff;font-size:10px;font-weight:800;padding:4px 14px;border-radius:100px;letter-spacing:0.08em;display:block">🔥 MOST POPULAR</span>
                    </div>
                    @endif
                    <div class="mb-3 {{ $meta['featured'] ? 'mt-2' : '' }}">
                        <span class="pkg-tag">{{ $meta['tag'] }}</span>
                    </div>
                    @if($pkg)
                        <p class="pkg-price">{{ $pkg['price'] ?? 'Hubungi kami' }}</p>
                        <p class="pkg-price-sub" @if($meta['featured']) style="color:rgba(255,255,255,0.7)" @endif>Per session</p>
                        <hr class="pkg-divider">
                        <p style="font-size:13px;font-weight:700;{{ $meta['featured'] ? '' : 'color:#141b2b;' }}margin-bottom:10px;">Paket {{ $meta['label'] }}</p>
                        @if(!empty($pkg['description']))
                        <p class="flex-1 mb-5" style="font-size:13px;{{ $meta['featured'] ? 'color:rgba(255,255,255,0.85)' : 'color:#424842' }};line-height:1.6">
                            {{ $pkg['description'] }}
                        </p>
                        @elseif(!empty($pkg['features']))
                        <ul class="space-y-2 flex-1 mb-5" style="list-style:none;padding:0;margin:0">
                            @foreach((array)$pkg['features'] as $feature)
                            <li class="flex items-start gap-2 text-[13px] {{ $meta['featured'] ? '' : 'text-[#424842]' }}">
                                <span class="material-symbols-outlined pkg-check">check_circle</span> {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        @if(!empty($pkg['best_for']))
                        <div class="pkg-cocok mb-5 {{ $meta['featured'] ? 'mt-3' : '' }}">
                            <p style="font-size:10.5px;font-weight:700;{{ $meta['featured'] ? '' : 'color:#43664c;' }}margin-bottom:2px">Cocok untuk:</p>
                            <p style="font-size:11.5px">{{ $pkg['best_for'] }}</p>
                        </div>
                        @endif
                    @else
                        {{-- Fallback when no pricing data set --}}
                        <p class="pkg-price" style="font-size:18px">Hubungi kami</p>
                        <p class="pkg-price-sub" @if($meta['featured']) style="color:rgba(255,255,255,0.7)" @endif>Untuk info harga</p>
                        <hr class="pkg-divider">
                        <p class="flex-1 mb-5" style="font-size:13px;color:{{ $meta['featured'] ? 'rgba(255,255,255,0.7)' : '#727971' }}">
                            Paket {{ $meta['label'] }} tersedia. Hubungi fotografer untuk detail harga dan ketersediaan.
                        </p>
                    @endif
                    @if($photographer->whatsapp_link)
                    <a href="{{ $photographer->whatsapp_link }}" target="_blank" class="pkg-cta" id="{{ $meta['id'] }}-cta">
                        <span class="material-symbols-outlined" style="font-size:16px">chat</span> Pesan Sekarang
                    </a>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- ── STUDENT & GRADUATION PACKAGES ── --}}
            <div class="mb-6">
                <span class="pkg-section-label" style="background:linear-gradient(135deg,#c5eccb,#a8d9b2);color:#2d5534">
                    <span class="material-symbols-outlined" style="font-size:14px">school</span>
                    Student &amp; Graduation Packages
                </span>
                <p class="text-[13px] text-[#727971] mt-1 ml-1">Special rates for students &amp; campus moments 🎓</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-14">

                {{-- Solo --}}
                <div class="pkg-card student pkg-delay-1" id="pkg-solo">
                    <span class="student-badge">🎓 Student</span>
                    <div class="mb-3 mt-2">
                        <span class="pkg-tag" style="background:#e8f5ea;color:#2d5534">Solo</span>
                    </div>
                    <p class="pkg-price" style="color:#2d5534">Rp150.000</p>
                    <p class="pkg-price-sub">Per session</p>
                    <hr class="pkg-divider">
                    <ul class="space-y-2 flex-1 mb-5" style="list-style:none;padding:0;margin:0">
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">schedule</span> Durasi 1 jam
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">person</span> 1 orang
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">photo_library</span> 20 premium edit photos
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">location_on</span> 1 lokasi
                        </li>
                    </ul>
                    @if($photographer->whatsapp_link)
                    <a href="{{ $photographer->whatsapp_link }}" target="_blank" class="pkg-cta" id="pkg-solo-cta">
                        <span class="material-symbols-outlined" style="font-size:16px">chat</span> Book Now
                    </a>
                    @endif
                </div>

                {{-- Bestie Package --}}
                <div class="pkg-card student pkg-delay-2" id="pkg-bestie">
                    <span class="student-badge">🎓 Student</span>
                    <div class="mb-3 mt-2">
                        <span class="pkg-tag" style="background:#e8f5ea;color:#2d5534">Bestie Package</span>
                    </div>
                    <p class="pkg-price" style="color:#2d5534">Rp250.000</p>
                    <p class="pkg-price-sub">Per session</p>
                    <hr class="pkg-divider">
                    <ul class="space-y-2 flex-1 mb-5" style="list-style:none;padding:0;margin:0">
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">schedule</span> Durasi 1,5 jam
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">group</span> 2 orang
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">photo_library</span> 40 premium edit photos
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">drive_folder_upload</span> File via Google Drive
                        </li>
                    </ul>
                    @if($photographer->whatsapp_link)
                    <a href="{{ $photographer->whatsapp_link }}" target="_blank" class="pkg-cta" id="pkg-bestie-cta">
                        <span class="material-symbols-outlined" style="font-size:16px">chat</span> Book Now
                    </a>
                    @endif
                </div>

                {{-- Squad Package --}}
                <div class="pkg-card student pkg-delay-3" id="pkg-squad">
                    <span class="student-badge">🎓 Student</span>
                    <div class="mb-3 mt-2">
                        <span class="pkg-tag" style="background:#e8f5ea;color:#2d5534">Squad Package</span>
                    </div>
                    <p class="pkg-price" style="color:#2d5534">Rp500.000</p>
                    <p class="pkg-price-sub">Per session</p>
                    <hr class="pkg-divider">
                    <ul class="space-y-2 flex-1 mb-5" style="list-style:none;padding:0;margin:0">
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">schedule</span> Durasi 2 jam
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">groups</span> 4–6 orang
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">photo_library</span> 80 premium edit photos
                        </li>
                        <li class="flex items-start gap-2 text-[13px] text-[#424842]">
                            <span class="material-symbols-outlined pkg-check">drive_folder_upload</span> File via Google Drive
                        </li>
                    </ul>
                    <span class="pkg-bonus" style="background:linear-gradient(135deg,#c5eccb,#a8d9b2);color:#2d5534">🎬 Free 15-sec Reels Video</span>
                    <div class="mb-5 mt-3"></div>
                    @if($photographer->whatsapp_link)
                    <a href="{{ $photographer->whatsapp_link }}" target="_blank" class="pkg-cta" id="pkg-squad-cta">
                        <span class="material-symbols-outlined" style="font-size:16px">chat</span> Book Now
                    </a>
                    @endif
                </div>
            </div>

            {{-- ── ADD-ONS ── --}}
            <div class="mb-6">
                <span class="pkg-section-label" style="background:#f1f3ff;color:#424870">
                    <span class="material-symbols-outlined" style="font-size:14px">add_circle</span>
                    Add-Ons &amp; Extras
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
                <div class="pkg-card addon" id="pkg-addons-card" style="padding:20px 24px">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[#43664c]" style="font-size:22px">extension</span>
                        <p style="font-size:14px;font-weight:700;color:#141b2b;">Optional Add-Ons</p>
                    </div>
                    <div class="addon-row" id="addon-extra-jam">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:18px">more_time</span>
                            <span style="font-size:13px;color:#424842;font-weight:500">Extra 1 jam sesi</span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#141b2b;white-space:nowrap">Rp100k – 250k</span>
                    </div>
                    <div class="addon-row" id="addon-polaroid">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:18px">photo</span>
                            <span style="font-size:13px;color:#424842;font-weight:500">Cetak Polaroid</span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#141b2b;white-space:nowrap">Rp10k – 20k/lembar</span>
                    </div>
                    <div class="addon-row" id="addon-frame">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:18px">crop_din</span>
                            <span style="font-size:13px;color:#424842;font-weight:500">Frame foto</span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#141b2b;white-space:nowrap">mulai Rp50k</span>
                    </div>
                    <div class="addon-row" id="addon-video">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:18px">videocam</span>
                            <span style="font-size:13px;color:#424842;font-weight:500">Video cinematic pendek</span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#141b2b;white-space:nowrap">mulai Rp300k</span>
                    </div>
                    <div class="addon-row" id="addon-sameday">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:18px">flash_on</span>
                            <span style="font-size:13px;color:#424842;font-weight:500">Same day edit</span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#141b2b;white-space:nowrap">mulai Rp200k</span>
                    </div>
                    <div class="addon-row" id="addon-storage">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#7ba082]" style="font-size:18px">storage</span>
                            <span style="font-size:13px;color:#424842;font-weight:500">Harddisk / Flashdisk</span>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#141b2b;white-space:nowrap">Sesuai media</span>
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="pkg-card" id="pkg-contact-card" style="background:linear-gradient(145deg,#43664c,#5e8c69);border:none">
                    <p style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:rgba(255,255,255,0.65);margin-bottom:8px">Butuh paket custom?</p>
                    <p style="font-size:22px;font-weight:800;color:#fff;line-height:1.2;margin-bottom:8px">Diskusikan kebutuhanmu langsung!</p>
                    <p style="font-size:13px;color:rgba(255,255,255,0.75);margin-bottom:24px;line-height:1.6">Tidak menemukan paket yang cocok? Hubungi fotografer untuk mendapatkan penawaran khusus sesuai kebutuhanmu.</p>
                    <div class="space-y-3 mt-auto">
                        @if($photographer->whatsapp_link)
                        <a href="{{ $photographer->whatsapp_link }}" target="_blank" rel="noopener"
                           id="pkg-contact-wa-btn"
                           style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px 0;border-radius:12px;font-size:13px;font-weight:700;background:#25D366;color:#fff;text-decoration:none">
                            <span class="material-symbols-outlined" style="font-size:18px">chat</span>
                            Chat via WhatsApp
                        </a>
                        @endif
                        @if($photographer->instagram_link)
                        <a href="{{ $photographer->instagram_link }}" target="_blank" rel="noopener"
                           id="pkg-contact-ig-btn"
                           style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px 0;border-radius:12px;font-size:13px;font-weight:700;background:rgba(255,255,255,0.15);color:#fff;text-decoration:none;border:1.5px solid rgba(255,255,255,0.3)">
                            <span class="material-symbols-outlined" style="font-size:18px">photo_camera</span>
                            DM di Instagram
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pricing disclaimer --}}
            <div class="flex items-start gap-3 bg-[#f9f9ff] border border-[#e5e7eb] rounded-2xl px-5 py-4" id="pkg-disclaimer">
                <span class="material-symbols-outlined text-[#7ba082] flex-shrink-0" style="font-size:20px">info</span>
                <p style="font-size:12.5px;color:#727971;line-height:1.6">
                    <strong style="color:#424842">Catatan:</strong> Harga bersifat estimasi dan dapat berubah berdasarkan lokasi, kompleksitas, dan waktu pengerjaan.
                    Semua paket sudah termasuk file foto yang diedit dan dikirim via Google Drive. Hubungi fotografer untuk konfirmasi harga final.
                </p>
            </div>
        </section>

        <div class="mt-10">
            <a href="{{ route('user.photographers') }}" id="pg-back-btn"
               class="inline-flex items-center gap-2 text-[13px] font-semibold text-[#727971] hover:text-[#43664c] transition-colors">
                <span class="material-symbols-outlined" style="font-size:16px">arrow_back</span>
                Back to Directory
            </a>
        </div>
    </div>
</x-app-layout>
