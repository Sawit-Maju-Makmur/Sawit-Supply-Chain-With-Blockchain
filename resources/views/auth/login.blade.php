<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - SIPS Sawit (Sistem Evaluasi Pemasok Kelapa Sawit Berkelanjutan)</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS CDN with Forms Plugin -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Tailwind Theme Configuration from SIPS Sawit Design System -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary": "#ffffff",
                        "on-surface": "#0b1f16",
                        "primary-fixed": "#9ef5c0",
                        "on-primary": "#ffffff",
                        "surface-container-low": "#e1f9e9",
                        "surface": "#e8ffef",
                        "surface-variant": "#d0e8d8",
                        "on-tertiary-fixed-variant": "#414845",
                        "secondary": "#416656",
                        "surface-tint": "#066c43",
                        "tertiary": "#4d5551",
                        "tertiary-fixed-dim": "#c0c8c4",
                        "secondary-container": "#c3ecd7",
                        "tertiary-fixed": "#dce4df",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed": "#002115",
                        "on-primary-container": "#aeffcc",
                        "inverse-primary": "#82d8a5",
                        "on-error-container": "#93000a",
                        "outline": "#6f7a71",
                        "secondary-fixed": "#c3ecd7",
                        "on-tertiary-fixed": "#161d1a",
                        "error-container": "#ffdad6",
                        "on-primary-fixed-variant": "#005231",
                        "surface-container-high": "#d6eede",
                        "surface-container": "#dcf4e4",
                        "on-primary-fixed": "#002111",
                        "outline-variant": "#bec9bf",
                        "primary-container": "#1f7a4f",
                        "surface-dim": "#c8e0d0",
                        "on-tertiary-container": "#e7efea",
                        "primary-fixed-dim": "#82d8a5",
                        "surface-container-highest": "#d0e8d8",
                        "on-secondary-fixed-variant": "#294e3f",
                        "surface-container-lowest": "#ffffff",
                        "tertiary-container": "#656d69",
                        "surface-bright": "#e8ffef",
                        "inverse-on-surface": "#dff7e6",
                        "primary": "#00603a",
                        "background": "#e8ffef",
                        "on-secondary-container": "#476c5b",
                        "error": "#ba1a1a",
                        "on-surface-variant": "#3f4942",
                        "on-background": "#0b1f16",
                        "on-error": "#ffffff",
                        "inverse-surface": "#20352a",
                        "secondary-fixed-dim": "#a7cfbb"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    fontFamily: {
                        "sans": ["'Plus Jakarta Sans'", "sans-serif"],
                        "headline-md": ["'Plus Jakarta Sans'"],
                        "headline-sm": ["'Plus Jakarta Sans'"],
                        "body-sm": ["'Plus Jakarta Sans'"],
                        "label-lg": ["'Plus Jakarta Sans'"],
                        "label-sm": ["'Plus Jakarta Sans'"],
                        "body-lg": ["'Plus Jakarta Sans'"],
                        "label-md": ["'Plus Jakarta Sans'"],
                        "headline-lg": ["'Plus Jakarta Sans'"],
                        "display-lg": ["'Plus Jakarta Sans'"],
                        "body-md": ["'Plus Jakarta Sans'"]
                    }
                }
            }
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
    </style>
</head>
<body class="bg-surface-container-low text-on-surface antialiased min-h-screen flex flex-col justify-between selection:bg-primary-fixed selection:text-on-primary-fixed font-sans">

<!-- Main Canvas: Split Layout (1440px max desktop frame) -->
<main class="w-full flex-grow flex items-center justify-center p-3 sm:p-6 md:p-8 lg:p-10">
    <div class="w-full max-w-[1400px] min-h-[750px] bg-surface-container-lowest rounded-2xl shadow-lg border border-outline-variant overflow-hidden flex flex-col lg:flex-row">

        <!-- LEFT PANEL: Institutional Deep Palm Forest Motif & Identity -->
        <section class="lg:w-1/2 relative bg-inverse-surface text-on-primary p-8 md:p-12 lg:p-16 flex flex-col justify-between overflow-hidden">
            <!-- Decorative Geometric/Organic Background Grid -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full object-cover" height="100%" width="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern height="64" id="palm-pattern" patternunits="userSpaceOnUse" width="64">
                            <path d="M32 0 C20 18 20 46 32 64 C44 46 44 18 32 0 Z" fill="none" stroke="#FFFFFF" stroke-width="1.2"></path>
                            <path d="M0 32 C18 20 46 20 64 32 C46 44 18 44 0 32 Z" fill="none" stroke="#FFFFFF" stroke-width="1.2"></path>
                            <circle cx="32" cy="32" fill="#FFFFFF" r="3"></circle>
                        </pattern>
                    </defs>
                    <rect fill="url(#palm-pattern)" height="100%" width="100%"></rect>
                </svg>
            </div>

            <!-- Ambient Gradient Layer -->
            <div class="absolute inset-0 bg-gradient-to-br from-inverse-surface via-[#12382A] to-[#0A241A] opacity-95 pointer-events-none"></div>

            <!-- Left Top Content: Brand and Value Propositions -->
            <div class="relative z-10 space-y-8">
                <!-- Brand Badge -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-white text-2xl" data-icon="eco">eco</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-white tracking-tight">SIPS Sawit</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-semibold">NDPE v2.4</span>
                        </div>
                        <p class="text-xs text-outline-variant">Sistem Evaluasi Pemasok Kelapa Sawit</p>
                    </div>
                </div>

                <!-- Hero Headline & Subtitle -->
                <div class="space-y-3 pt-2">
                    <h1 class="text-3xl lg:text-4xl text-white font-bold leading-tight tracking-tight">
                        Rantai pasok sawit yang transparan dan berkelanjutan
                    </h1>
                    <p class="text-base text-surface-variant font-normal leading-relaxed max-w-xl">
                        Platform verifikasi kepatuhan NDPE (No Deforestation, Peat, Exploitation) dan ketertelusuran TBS terpadu.
                    </p>
                </div>

                <!-- 3 Value Proposition Bullets -->
                <div class="space-y-4 pt-2">
                    <!-- Item 1 -->
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm transition-all duration-200 hover:bg-white/10">
                        <div class="w-10 h-10 rounded-lg bg-primary-container/30 border border-primary-fixed-dim/40 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary-fixed" data-icon="satellite_alt">satellite_alt</span>
                        </div>
                        <div class="space-y-0.5">
                            <h2 class="text-sm font-semibold text-white">Penilaian sustainability otomatis</h2>
                            <p class="text-xs text-outline-variant leading-relaxed">
                                Scoring matriks kepatuhan NDPE real-time &amp; analitik satelit untuk deteksi tutupan lahan dan batas konsesi.
                            </p>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm transition-all duration-200 hover:bg-white/10">
                        <div class="w-10 h-10 rounded-lg bg-primary-container/30 border border-primary-fixed-dim/40 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary-fixed" data-icon="qr_code_scanner">qr_code_scanner</span>
                        </div>
                        <div class="space-y-0.5">
                            <h2 class="text-sm font-semibold text-white">QR Code ketertelusuran TBS</h2>
                            <p class="text-xs text-outline-variant leading-relaxed">
                                Validasi surat pengantar buah (e-DO) dan timbangan terintegrasi hingga titik koordinat kebun pemasok.
                            </p>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm transition-all duration-200 hover:bg-white/10">
                        <div class="w-10 h-10 rounded-lg bg-primary-container/30 border border-primary-fixed-dim/40 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary-fixed" data-icon="security">security</span>
                        </div>
                        <div class="space-y-0.5">
                            <h2 class="text-headline-sm text-sm font-semibold text-white">Data aman di ledger blockchain</h2>
                            <p class="text-xs text-outline-variant leading-relaxed">
                                Catatan audit tamper-proof dengan enkripsi standar enterprise untuk kepatuhan EUDR dan ISPO/RSPO.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Bottom Trust Badge -->
            <div class="relative z-10 pt-8 border-t border-white/10 mt-8 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-secondary-container/20 flex items-center justify-center shrink-0 text-primary-fixed">
                    <span class="material-symbols-outlined text-lg" data-icon="verified">verified</span>
                </div>
                <p class="text-xs text-surface-variant font-medium">
                    Telah dipercaya oleh <strong class="text-white font-semibold">140+ pabrik kelapa sawit</strong> dan <strong class="text-white font-semibold">25.000+ pekebun</strong> terdaftar di Indonesia.
                </p>
            </div>
        </section>

        <!-- RIGHT PANEL: Clean Form Canvas -->
        <section class="lg:w-1/2 bg-surface-container-lowest p-8 sm:p-12 md:p-14 lg:p-16 flex flex-col justify-between">
            <!-- Top Utility Links -->
            <div class="w-full flex justify-between items-center pb-4">
                <span class="text-xs uppercase tracking-wider text-tertiary font-semibold">Portal Sistem SIPS Sawit</span>
                <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-container hover:text-primary transition-colors" href="{{ url('/assessments') }}">
                    <span class="material-symbols-outlined text-base" data-icon="assignment">assignment</span>
                    <span>Self-Assessment</span>
                </a>
            </div>

            <!-- Centered Authentication Form Group -->
            <div class="max-w-md w-full mx-auto my-auto py-6 space-y-6">
                <!-- Header Texts -->
                <div class="space-y-1.5">
                    <h2 class="text-3xl font-bold text-on-surface tracking-tight">Masuk</h2>
                    <p class="text-sm text-on-surface-variant">
                        Silakan masukkan kredensial akun Anda untuk mengakses sistem evaluasi pemasok.
                    </p>
                </div>

                <!-- Session Status (Misal: setelah reset password) -->
                @if (session('status'))
                    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 text-xl shrink-0">check_circle</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Alert Banners -->
                @if ($errors->any())
                    @php
                        $errorMessage = $errors->first('email') ?: ($errors->first('password') ?: $errors->first());
                        $isThrottle = str_contains(strtolower($errorMessage), 'too many')
                            || str_contains(strtolower($errorMessage), 'terlalu banyak')
                            || str_contains(strtolower($errorMessage), 'seconds')
                            || str_contains(strtolower($errorMessage), 'detik');

                        $seconds = 60;
                        if ($isThrottle && preg_match('/(\d+)\s*(seconds?|detik)/i', $errorMessage, $matches)) {
                            $seconds = (int) $matches[1];
                        }
                    @endphp

                    @if ($isThrottle)
                        <!-- AMBER RATE LIMIT ALERT BANNER (Real-time Countdown) -->
                        <div class="p-4 rounded-xl bg-[#FCEFD3] border border-[#F3D9A5] text-[#A8670A] flex items-start gap-3 shadow-sm transition-all" role="alert">
                            <div class="w-8 h-8 rounded-lg bg-[#A8670A]/10 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[#A8670A] text-xl" style="font-variation-settings: 'FILL' 1;">hourglass_top</span>
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs uppercase tracking-wider font-bold text-[#A8670A]">Batas Percobaan Terlampaui</span>
                                    <span class="px-2 py-0.5 rounded-full bg-[#A8670A]/15 text-[#A8670A] text-[11px] font-bold" id="countdown-badge">
                                        <span id="countdown-number">{{ $seconds }}</span> detik tersisa
                                    </span>
                                </div>
                                <p class="text-xs font-medium text-[#A8670A] mt-1 leading-relaxed">
                                    Terlalu banyak percobaan login. Coba lagi dalam <span id="countdown-sec" class="font-bold">{{ $seconds }}</span> detik demi keamanan akun Anda.
                                </p>
                                <div class="w-full bg-[#F3D9A5] rounded-full h-1.5 mt-3 overflow-hidden">
                                    <div class="bg-[#A8670A] h-1.5 rounded-full transition-all duration-1000" id="rate-progress" style="width: {{ min(100, round(($seconds / 60) * 100)) }}%;"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- RED ALERT BANNER (Kredensial Salah) -->
                        <div aria-live="assertive" class="p-4 rounded-xl bg-[#FBE4E4] border border-[#F5C6C6] text-[#B83232] flex items-start gap-3 shadow-sm" role="alert">
                            <span class="material-symbols-outlined text-[#B83232] text-xl shrink-0 mt-0.5" data-icon="error" style="font-variation-settings: 'FILL' 1;">error</span>
                            <div class="flex-1">
                                <h5 class="text-xs font-bold text-[#B83232] uppercase tracking-wider">Otentikasi Gagal</h5>
                                <p class="text-xs text-[#B83232] mt-0.5 leading-snug">
                                    {{ $errorMessage }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-semibold text-on-surface" for="email">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            @error('email')
                                <span class="text-xs text-[#B83232] flex items-center gap-1 font-medium">
                                    <span class="material-symbols-outlined text-xs">cancel</span> Tidak cocok
                                </span>
                            @enderror
                        </div>
                        <div class="relative rounded-xl shadow-sm">
                            <input class="w-full h-11 px-3.5 pr-11 bg-surface-container-lowest border @error('email') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                   id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   placeholder="nama@perusahaan.co.id"
                                   required
                                   autofocus
                                   autocomplete="username"/>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-lg" data-icon="mail">mail</span>
                            </div>
                        </div>
                    </div>

                    <!-- Password Input with Toggle Eye Button -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-semibold text-on-surface" for="password">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-primary-container hover:underline hover:text-primary transition-colors" href="{{ route('password.request') }}">
                                    Lupa kata sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative rounded-xl shadow-sm">
                            <input class="w-full h-11 px-3.5 pr-11 bg-surface-container-lowest border @error('password') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                   id="password"
                                   name="password"
                                   type="password"
                                   placeholder="Masukkan kata sandi Anda"
                                   required
                                   autocomplete="current-password"/>

                            <!-- Interactive Eye Toggle Button -->
                            <button aria-label="Tampilkan atau sembunyikan kata sandi"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer transition-colors"
                                    id="togglePassword"
                                    type="button"
                                    tabindex="-1">
                                <span class="material-symbols-outlined text-xl" data-icon="visibility" id="passwordIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Row -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input class="w-4 h-4 rounded border-outline-variant text-primary-container focus:ring-primary-container/30 focus:ring-offset-0 transition-colors cursor-pointer"
                                   id="remember"
                                   name="remember"
                                   type="checkbox"/>
                            <span class="text-xs text-on-surface-variant font-medium">Ingat saya di perangkat ini</span>
                        </label>
                        <span class="inline-flex items-center gap-1 text-[11px] text-tertiary">
                            <span class="material-symbols-outlined text-sm text-[#1F7A4F]">shield</span>
                            TLS 1.3 Terenkripsi
                        </span>
                    </div>

                    <!-- Primary Action Button (SIPS Green #1F7A4F) -->
                    <div class="pt-2">
                        <button id="submitBtn"
                                class="w-full h-12 bg-primary-container hover:bg-[#186340] active:scale-[0.99] text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary-container focus:ring-offset-2 cursor-pointer"
                                type="submit">
                            <span id="btn-text">Masuk</span>
                            <span class="material-symbols-outlined text-lg" data-icon="arrow_forward">arrow_forward</span>
                        </button>
                    </div>

                    <!-- Registration Footer Prompt -->
                    <div class="text-center pt-2">
                        <p class="text-xs text-tertiary">
                            Belum punya akun?
                            @if (Route::has('register'))
                                <a class="text-primary-container font-semibold hover:underline" href="{{ route('register') }}">
                                    Daftar sebagai pemasok
                                </a>
                            @endif
                        </p>
                    </div>
                </form>

                <!-- Security Badges / Certification Assurance -->
                <div class="pt-4 border-t border-outline-variant/60 flex flex-wrap items-center justify-center gap-3 text-outline text-[11px]">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" data-icon="lock">lock</span> 256-Bit SSL
                    </span>
                    <span class="w-1 h-1 rounded-full bg-outline-variant hidden sm:inline-block"></span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" data-icon="verified_user">verified_user</span> ISO 27001
                    </span>
                    <span class="w-1 h-1 rounded-full bg-outline-variant hidden sm:inline-block"></span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" data-icon="nature">nature</span> ISPO / RSPO Compliant
                    </span>
                </div>
            </div>

            <!-- Right Side Footer Notice -->
            <div class="w-full text-center lg:text-left pt-6 border-t border-outline-variant/40 flex flex-col sm:flex-row justify-between items-center text-xs text-tertiary gap-2">
                <span>&copy; {{ date('Y') }} SIPS Sawit. Hak Cipta Dilindungi.</span>
                <div class="flex items-center gap-4">
                    <a class="hover:text-primary-container transition-colors" href="#">Kebijakan Privasi</a>
                    <span>•</span>
                    <a class="hover:text-primary-container transition-colors" href="#">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Interactive Script for Password Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');

        if (togglePasswordBtn && passwordInput && passwordIcon) {
            togglePasswordBtn.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                if (isPassword) {
                    passwordInput.setAttribute('type', 'text');
                    passwordIcon.textContent = 'visibility_off';
                } else {
                    passwordInput.setAttribute('type', 'password');
                    passwordIcon.textContent = 'visibility';
                }
            });
        }
    });
</script>

@if (isset($isThrottle) && $isThrottle)
<!-- Real-time Countdown Timer Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let secondsLeft = parseInt("{{ $seconds }}", 10) || 60;
        const totalSeconds = 60;
        const badge = document.getElementById('countdown-badge');
        const badgeNum = document.getElementById('countdown-number');
        const countSec = document.getElementById('countdown-sec');
        const progressBar = document.getElementById('rate-progress');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btn-text');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        }

        const timer = setInterval(() => {
            secondsLeft--;
            if (secondsLeft > 0) {
                if (badgeNum) badgeNum.textContent = secondsLeft;
                if (countSec) countSec.textContent = secondsLeft;
                if (btnText) btnText.textContent = `Tunggu (${secondsLeft}d)`;
                if (progressBar) {
                    const percent = Math.max(0, (secondsLeft / totalSeconds) * 100);
                    progressBar.style.width = percent + '%';
                }
            } else {
                clearInterval(timer);
                if (badge) badge.textContent = 'Silakan coba lagi';
                if (countSec) countSec.textContent = '0';
                if (progressBar) progressBar.style.width = '0%';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                }
                if (btnText) btnText.textContent = 'Masuk';
            }
        }, 1000);
    });
</script>
@endif
</body>
</html>
