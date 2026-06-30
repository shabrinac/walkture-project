<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — Walkture</title>
    <meta name="description" content="Sign in to Walkture to discover aesthetic photo spots and connect with photographers in Samarinda.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Be Vietnam Pro', sans-serif; box-sizing: border-box; }
        
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #fcfcff;
            position: relative;
        }

        .login-card {
            width: 100%;
            max-width: 420px; /* Kunci ukurannya di sini biar tidak melar */
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            border: 1px solid #f3f4f6;
            z-index: 10;
            margin: 20px;
        }

        .auth-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: #f4f6fa;
            font-size: 13px;
            color: #4b5563;
            transition: all 0.2s;
            outline: none;
            margin-top: 8px;
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

        .footer-nav {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 32px 48px;
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
                position: static;
                margin-top: 40px;
            }
        }
    </style>
</head>
<body>
    
    {{-- Main Login Card --}}
    <div class="login-card">
        
        <h1 style="font-size: 24px; font-weight: 700; color: #43664c; text-align: center; margin: 0 0 8px 0; letter-spacing: -0.5px;">Walkture</h1>
        <p style="color: #6b7280; font-size: 13px; text-align: center; margin: 0 0 32px 0;">Step into a world of curated tranquility.</p>

        {{-- Session status --}}
        @if (session('status'))
            <div style="margin-bottom: 16px; padding: 12px 16px; background: #c5eccb; border: 1px solid #a9d0b0; border-radius: 8px; font-size: 13px; color: #14361f;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            {{-- Email --}}
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; font-size: 12px; font-weight: 700; color: #1f2937;">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       class="auth-input @error('email') border-red-400 @enderror"
                       placeholder="curator@walkture.com">
                @error('email')
                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; font-size: 12px; font-weight: 700; color: #1f2937;">Password</label>
                <input id="password" type="password" name="password"
                       required autocomplete="current-password"
                       class="auth-input @error('password') border-red-400 @enderror"
                       placeholder="••••••••">
                @error('password')
                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me & Forgot password --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input id="remember_me" type="checkbox" name="remember"
                           style="width: 14px; height: 14px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #43664c;">
                    <span style="font-size: 12px; font-weight: 500; color: #4b5563;">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 12px; font-weight: 500; color: #6b7280; text-decoration: none;">Forgot Password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" id="login-submit-btn" class="btn-primary">
                Sign In
            </button>

            {{-- Register link --}}
            <p style="text-align: center; font-size: 12px; color: #6b7280; margin-top: 24px;">
                New here? 
                <a href="{{ route('register') }}" style="font-weight: 700; color: #43664c; text-decoration: none;">Create an account.</a>
            </p>
        </form>
    </div>

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