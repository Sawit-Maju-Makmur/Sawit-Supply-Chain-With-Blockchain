<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Kata Sandi - SIPS Sawit</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#00603a",
                        "primary-container": "#1f7a4f",
                        "surface": "#e8ffef",
                        "surface-container-low": "#e1f9e9",
                        "surface-container-lowest": "#ffffff",
                        "surface-variant": "#d0e8d8",
                        "outline": "#6f7a71",
                        "outline-variant": "#bec9bf",
                        "on-surface": "#0b1f16",
                        "on-surface-variant": "#3f4942",
                        "tertiary": "#4d5551",
                        "background": "#e8ffef",
                        "on-primary": "#ffffff",
                        "secondary-container": "#c3ecd7",
                        "secondary": "#416656",
                        "error": "#ba1a1a"
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
<body class="bg-surface-container-low text-on-surface antialiased min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-[1200px] min-h-[700px] bg-surface-container-lowest rounded-2xl shadow-lg border border-outline-variant overflow-hidden flex flex-col lg:flex-row">
        <section class="lg:w-1/2 relative bg-inverse-surface text-on-primary p-8 md:p-12 lg:p-16 flex flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full object-cover" height="100%" width="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern height="64" id="palm-pattern" patternunits="userSpaceOnUse" width="64">
                            <path d="M32 0 C20 18 20 46 32 64 C44 46 44 18 32 0 Z" fill="none" stroke="#FFFFFF" stroke-width="1.2"/>
                            <path d="M0 32 C18 20 46 20 64 32 C46 44 18 44 0 32 Z" fill="none" stroke="#FFFFFF" stroke-width="1.2"/>
                            <circle cx="32" cy="32" fill="#FFFFFF" r="3"/>
                        </pattern>
                    </defs>
                    <rect fill="url(#palm-pattern)" height="100%" width="100%"/>
                </svg>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-inverse-surface via-[#12382A] to-[#0A241A] opacity-95 pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
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

                <div class="space-y-3 pt-2">
                    <h1 class="text-3xl lg:text-4xl text-white font-bold leading-tight tracking-tight">Pulihkan akses akun Anda</h1>
                    <p class="text-base text-surface-variant font-normal leading-relaxed max-w-xl">
                        Masukkan email Anda, lalu kami akan mengirimkan tautan untuk menyetel ulang kata sandi secara aman.
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-8 border-t border-white/10 mt-8 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-secondary-container/20 flex items-center justify-center shrink-0 text-primary-fixed">
                    <span class="material-symbols-outlined text-lg" data-icon="shield">shield</span>
                </div>
                <p class="text-xs text-surface-variant font-medium">Akses aman untuk mitra rantai pasok dan tim evaluasi kelapa sawit.</p>
            </div>
        </section>

        <section class="lg:w-1/2 bg-surface-container-lowest p-8 sm:p-12 md:p-14 lg:p-16 flex flex-col justify-between">
            <div class="w-full flex justify-between items-center pb-4">
                <span class="text-xs uppercase tracking-wider text-tertiary font-semibold">Portal Sistem SIPS Sawit</span>
                <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-container hover:text-primary transition-colors" href="{{ route('login') }}">
                    <span class="material-symbols-outlined text-base" data-icon="login">login</span>
                    <span>Masuk</span>
                </a>
            </div>

            <div class="max-w-md w-full mx-auto my-auto py-6 space-y-6">
                <div class="space-y-1.5">
                    <h2 class="text-3xl font-bold text-on-surface tracking-tight">Lupa Kata Sandi</h2>
                    <p class="text-sm text-on-surface-variant">
                        Kami akan mengirim link reset ke email yang terdaftar pada akun Anda.
                    </p>
                </div>

                @if (session('status'))
                    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 text-xl shrink-0">check_circle</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div aria-live="assertive" class="p-4 rounded-xl bg-[#FBE4E4] border border-[#F5C6C6] text-[#B83232] flex items-start gap-3 shadow-sm" role="alert">
                        <span class="material-symbols-outlined text-[#B83232] text-xl shrink-0 mt-0.5" data-icon="error" style="font-variation-settings: 'FILL' 1;">error</span>
                        <div class="flex-1">
                            <h5 class="text-xs font-bold text-[#B83232] uppercase tracking-wider">Permintaan Gagal</h5>
                            <p class="text-xs text-[#B83232] mt-0.5 leading-snug">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-on-surface" for="email">Alamat Email <span class="text-red-500">*</span></label>
                        <input class="w-full h-11 px-3.5 bg-surface-container-lowest border @error('email') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                               id="email"
                               name="email"
                               type="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="nama@perusahaan.co.id"/>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full h-12 bg-primary-container hover:bg-[#186340] active:scale-[0.99] text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary-container focus:ring-offset-2 cursor-pointer">
                            <span>Kirim Link Reset Kata Sandi</span>
                            <span class="material-symbols-outlined text-lg" data-icon="send">send</span>
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2">
                    <p class="text-xs text-tertiary">
                        Ingat kata sandi Anda?
                        <a class="text-primary-container font-semibold hover:underline" href="{{ route('login') }}">Kembali ke masuk</a>
                    </p>
                </div>
            </div>

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
</body>
</html>
