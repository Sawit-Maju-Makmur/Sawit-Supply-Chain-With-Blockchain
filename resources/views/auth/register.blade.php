<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - SIPS Sawit</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
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
                        "on-secondary": "#ffffff",
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
<body class="bg-surface-container-low text-on-surface antialiased min-h-screen flex flex-col justify-between selection:bg-primary selection:text-white font-sans">
    <main class="w-full flex-grow flex items-center justify-center p-3 sm:p-6 md:p-8 lg:p-10">
        <div class="w-full max-w-[1400px] min-h-[750px] bg-surface-container-lowest rounded-2xl shadow-lg border border-outline-variant overflow-hidden flex flex-col lg:flex-row">
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

                <div class="relative z-10 space-y-8">
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
                        <h1 class="text-3xl lg:text-4xl text-white font-bold leading-tight tracking-tight">
                            Daftar dan mulai kelola kepatuhan sawit Anda.
                        </h1>
                        <p class="text-base text-surface-variant font-normal leading-relaxed max-w-xl">
                            Buat akun pemasok Anda untuk memantau data lahan, dokumen, dan status evaluasi dengan lebih terintegrasi.
                        </p>
                    </div>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-primary-container/30 border border-primary-fixed-dim/40 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary-fixed" data-icon="location_on">location_on</span>
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Pemetaan lahan yang akurat</h2>
                                <p class="text-xs text-outline-variant leading-relaxed">Pantau koordinat kebun dan batas lahan Anda dengan lebih transparan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                            <div class="w-10 h-10 rounded-lg bg-primary-container/30 border border-primary-fixed-dim/40 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary-fixed" data-icon="fact_check">fact_check</span>
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-white">Audit dokumen status</h2>
                                <p class="text-xs text-outline-variant leading-relaxed">Kelola dokumen dan status compliance tanpa proses manual berulang.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-8 border-t border-white/10 mt-8 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-secondary-container/20 flex items-center justify-center shrink-0 text-primary-fixed">
                        <span class="material-symbols-outlined text-lg" data-icon="verified">verified</span>
                    </div>
                    <p class="text-xs text-surface-variant font-medium">Platform yang dirancang untuk ketertelusuran sawit dan mitra rantai pasok.</p>
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
                        <h2 class="text-3xl font-bold text-on-surface tracking-tight">Daftar</h2>
                        <p class="text-sm text-on-surface-variant">
                            Buat akun baru sebagai pemasok untuk mengakses sistem evaluasi dan dokumentasi lahan.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div aria-live="assertive" class="p-4 rounded-xl bg-[#FBE4E4] border border-[#F5C6C6] text-[#B83232] flex items-start gap-3 shadow-sm" role="alert">
                            <span class="material-symbols-outlined text-[#B83232] text-xl shrink-0 mt-0.5" data-icon="error" style="font-variation-settings: 'FILL' 1;">error</span>
                            <div class="flex-1">
                                <h5 class="text-xs font-bold text-[#B83232] uppercase tracking-wider">Pendaftaran Gagal</h5>
                                <p class="text-xs text-[#B83232] mt-0.5 leading-snug">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-on-surface" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input class="w-full h-11 px-3.5 bg-surface-container-lowest border @error('name') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                   id="name"
                                   name="name"
                                   type="text"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   autocomplete="name"
                                   placeholder="Nama lengkap perusahaan atau pemilik"/>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-on-surface" for="email">Alamat Email <span class="text-red-500">*</span></label>
                            <input class="w-full h-11 px-3.5 bg-surface-container-lowest border @error('email') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                   id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="username"
                                   placeholder="nama@perusahaan.co.id"/>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-on-surface" for="phone">Nomor Telepon</label>
                                <input class="w-full h-11 px-3.5 bg-surface-container-lowest border @error('phone') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                       id="phone"
                                       name="phone"
                                       type="text"
                                       value="{{ old('phone') }}"
                                       autocomplete="tel"
                                       placeholder="0812xxxxxx"/>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-on-surface" for="address">Alamat</label>
                                <input class="w-full h-11 px-3.5 bg-surface-container-lowest border @error('address') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                       id="address"
                                       name="address"
                                       type="text"
                                       value="{{ old('address') }}"
                                       placeholder="Jl. ..."/>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-on-surface" for="password">Kata Sandi <span class="text-red-500">*</span></label>
                            <div class="relative rounded-xl shadow-sm">
                                <input class="w-full h-11 px-3.5 pr-11 bg-surface-container-lowest border @error('password') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                       id="password"
                                       name="password"
                                       type="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Minimal 8 karakter"/>
                                <button aria-label="Tampilkan atau sembunyikan kata sandi" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer transition-colors" id="togglePassword" type="button" tabindex="-1">
                                    <span class="material-symbols-outlined text-xl" data-icon="visibility" id="passwordIcon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-on-surface" for="password_confirmation">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                            <div class="relative rounded-xl shadow-sm">
                                <input class="w-full h-11 px-3.5 pr-11 bg-surface-container-lowest border @error('password_confirmation') border-[#B83232] ring-1 ring-[#B83232] @else border-outline-variant @enderror rounded-xl text-on-surface text-sm placeholder:text-outline focus:outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20 transition-all duration-150"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       type="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Ulangi kata sandi"/>
                                <button aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi" class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer transition-colors" id="toggleConfirmPassword" type="button" tabindex="-1">
                                    <span class="material-symbols-outlined text-xl" data-icon="visibility" id="confirmPasswordIcon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button class="w-full h-12 bg-primary-container hover:bg-[#186340] active:scale-[0.99] text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary-container focus:ring-offset-2 cursor-pointer" type="submit">
                                <span>Daftar Sekarang</span>
                                <span class="material-symbols-outlined text-lg" data-icon="arrow_forward">arrow_forward</span>
                            </button>
                        </div>
                    </form>

                    <div class="text-center pt-2">
                        <p class="text-xs text-tertiary">
                            Sudah punya akun?
                            <a class="text-primary-container font-semibold hover:underline" href="{{ route('login') }}">Masuk di sini</a>
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
    </main>

    <script>
        function togglePasswordVisibility(button, inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.textContent = isPassword ? 'visibility_off' : 'visibility';
        }

        document.getElementById('togglePassword')?.addEventListener('click', function () {
            togglePasswordVisibility(this, 'password', 'passwordIcon');
        });

        document.getElementById('toggleConfirmPassword')?.addEventListener('click', function () {
            togglePasswordVisibility(this, 'password_confirmation', 'confirmPasswordIcon');
        });
    </script>
</body>
</html>
