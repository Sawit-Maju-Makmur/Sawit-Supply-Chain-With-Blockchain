<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email - SIPS Sawit</title>
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
                        "tertiary": "#4d5551"
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
    <div class="w-full max-w-xl bg-surface-container-lowest rounded-2xl shadow-lg border border-outline-variant overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-primary-container p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shadow-inner">
                    <span class="material-symbols-outlined text-2xl" data-icon="mark_email_read">mark_email_read</span>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold opacity-80">SIPS Sawit</p>
                    <h1 class="text-2xl font-bold tracking-tight">Verifikasi Email</h1>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-5">
            <p class="text-sm text-on-surface-variant leading-relaxed">
                Terima kasih sudah mendaftar. Sebelum melanjutkan, silakan verifikasi alamat email Anda dengan tautan yang sudah kami kirimkan.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-600 text-xl shrink-0">check_circle</span>
                    <span>Tautan verifikasi baru telah dikirim ke email Anda.</span>
                </div>
            @endif

            <div class="rounded-xl bg-[#F4FBF6] border border-[#CFEAD8] p-4 text-sm text-[#184C35]">
                Jika email belum sampai, Anda bisa mengirim ulang tautan verifikasi dengan menekan tombol di bawah ini.
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full h-12 bg-primary-container hover:bg-[#186340] text-white font-semibold text-sm rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all duration-150">
                        <span>Kirim Ulang Email Verifikasi</span>
                        <span class="material-symbols-outlined text-lg" data-icon="forward_to_inbox">forward_to_inbox</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full h-12 border border-outline-variant bg-surface-container-low hover:bg-surface-container-lowest text-on-surface font-semibold text-sm rounded-xl transition-all duration-150">
                        Keluar
                    </button>
                </form>
            </div>

            <div class="pt-2 text-center">
                <a class="text-sm font-semibold text-primary-container hover:underline" href="{{ route('login') }}">Kembali ke halaman masuk</a>
            </div>
        </div>
    </div>
</body>
</html>
