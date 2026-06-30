<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — Walkture</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS Reset Presisi */
        * { font-family: 'Be Vietnam Pro', sans-serif; box-sizing: border-box; padding: 0; margin: 0; }
        
        body {
            background-color: #fcfcff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Kontainer Utama penahan tengah */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            width: 100%;
        }

        .register-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 36px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.03);
            border: 1px solid #f3f4f6;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-label {
            display: block; 
            font-size: 11px; 
            font-weight: 700; 
            color: #4b5563; 
            margin-bottom: 8px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }

        .auth-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
            font-size: 13px;
            color: #374151;
            transition: all 0.2s;
            outline: none;
        }
        
        .auth-input:focus {
            border-color: #7ba082;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(123,160,130,0.1);
        }
        
        .auth-input::placeholder { color: #9ca3af; }
        
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #43664c;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            margin-top: 8px;
            /* Tambahan untuk mengunci teks persis di tengah */
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .btn-primary:hover { background: #2c4e35; }
        .btn-primary:active { transform: scale(0.99); }

        /* Footer menempel di bawah otomatis */
        .footer-nav {
            width: 100%;
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .footer-nav {
                flex-direction: column;
                gap: 16px;
                padding: 24px;
            }
            .register-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>

    <main class="main-container">
        {{-- Judul --}}
        <h1 style="font-size: 26px; font-weight: 700; color: #43664c; margin-bottom: 6px; letter-spacing: -0.5px;">Walkture</h1>
        <p style="color: #6b7280; font-size: 13px; margin-bottom: 32px;">Step into a world of curated tranquility.</p>

        {{-- Form Card --}}
        <div class="register-card">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="input-group">
                    <label for="name" class="input-label">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="auth-input @error('name') border-red-400 @enderror" placeholder="Evelyn Thorne">
                    @error('name')<p style="margin-top: 6px; font-size: 12px; color: #ef4444;">{{ $message }}</p>@enderror
                </div>

                <div class="input-group">
                    <label for="email" class="input-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="auth-input @error('email') border-red-400 @enderror" placeholder="evelyn@aesthetic.com">
                    @error('email')<p style="margin-top: 6px; font-size: 12px; color: #ef4444;">{{ $message }}</p>@enderror
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="auth-input @error('password') border-red-400 @enderror" placeholder="••••••••">
                    @error('password')<p style="margin-top: 6px; font-size: 12px; color: #ef4444;">{{ $message }}</p>@enderror
                </div>

                <div class="input-group">
                    <label for="password_confirmation" class="input-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="auth-input" placeholder="••••••••">
                    @error('password_confirmation')<p style="margin-top: 6px; font-size: 12px; color: #ef4444;">{{ $message }}</p>@enderror
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-start; margin-bottom: 24px; margin-top: 8px;">
                    <input id="agree_terms" type="checkbox" required style="margin-top: 2px; width: 14px; height: 14px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #43664c;">
                    <label for="agree_terms" style="font-size: 12px; color: #6b7280; line-height: 1.5; cursor: pointer;">
                        I agree to the Terms of Service and Privacy Policy regarding my urban exploration.
                    </label>
                </div>

                <button type="submit" class="btn-primary">
                    Create Account
                </button>

                <p style="text-align: center; font-size: 13px; color: #6b7280; margin-top: 24px;">
                    Already have an account? 
                    <a href="{{ route('login') }}" style="font-weight: 700; color: #43664c; text-decoration: none;">Sign In</a>
                </p>
            </form>
        </div>

        {{-- Aesthetic Quote --}}
        <p style="text-align: center; font-size: 12px; font-style: italic; color: #9ca3af; margin-top: 24px;">
            "Observation is a form of gratitude."
        </p>
    </main>

    {{-- Footer --}}
    <footer class="footer-nav">
        <div>
            <span style="font-weight: 600; color: #6b7280;">Walkture</span> <span style="margin: 0 4px;">|</span> &copy; 2026 Walkture. Curated with serenity.
        </div>
        <div style="display: flex; gap: 24px; font-weight: 500;">
            <a href="{{ route('guest.privacy') ?? '#' }}" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#43664c'" onmouseout="this.style.color='inherit'">Privacy Policy</a>
            <a href="{{ route('guest.terms') ?? '#' }}" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#43664c'" onmouseout="this.style.color='inherit'">Terms of Service</a>
            <a href="{{ route('guest.contact') ?? '#' }}" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#43664c'" onmouseout="this.style.color='inherit'">Contact</a>
            <a href="#" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#43664c'" onmouseout="this.style.color='inherit'">FAQ</a>
        </div>
    </footer>

</body>
</html>