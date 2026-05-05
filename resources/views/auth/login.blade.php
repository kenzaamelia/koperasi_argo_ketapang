<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Koperasi Argo Ketapang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-green-800 flex items-center justify-center p-4">

    {{-- Background pattern --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-green-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-400/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Logo & Judul --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Koperasi Argo Ketapang</h1>
            <p class="text-blue-200 text-sm mt-1">Sistem Informasi Penyuplai Bahan Baku SPPG</p>
        </div>

        {{-- Card Login --}}
        <div
            x-data="loginForm()"
            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 shadow-2xl"
        >
            <h2 class="text-white font-semibold text-xl mb-6">Masuk ke Akun</h2>

            {{-- Session Error --}}
            @if (session('status'))
                <div class="mb-4 px-4 py-3 bg-green-500/20 border border-green-400/30 rounded-xl text-green-200 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" @submit="onSubmit($event)" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-blue-100 mb-1.5">Email</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            x-model="form.email"
                            placeholder="email@sppg.com"
                            autocomplete="email"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-white/10 border rounded-xl text-white placeholder-blue-300
                                   focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition text-sm
                                   @error('email') border-red-400/50 @else border-white/20 @enderror"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-blue-100 mb-1.5">Password</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        {{-- 
                            BELAJAR JS: Toggle Show/Hide Password
                            :type → binding dinamis untuk type input
                            showPassword ? 'text' : 'password'
                            → jika showPassword true → type text (tampil)
                            → jika false → type password (tersembunyi)
                        --}}
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            x-model="form.password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            class="w-full pl-10 pr-12 py-3 bg-white/10 border border-white/20 rounded-xl text-white
                                   placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-white/30
                                   focus:border-white/40 transition text-sm"
                        />
                        {{-- Tombol toggle lihat password --}}
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white transition"
                        >
                            {{-- Mata TERBUKA → tampil saat password TERLIHAT (showPassword = true) --}}
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>

                            {{-- Mata TERTUTUP → tampil saat password TERSEMBUNYI (showPassword = false) --}}
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-white/30 bg-white/10 text-blue-500
                                   focus:ring-blue-400 focus:ring-offset-0"
                        />
                        <span class="text-sm text-blue-200">Ingat saya</span>
                    </label>
                </div>

                {{-- Tombol Login --}}
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full flex items-center justify-center gap-2 py-3 px-4
                           bg-white text-blue-800 font-semibold text-sm rounded-xl
                           hover:bg-blue-50 transition shadow-lg
                           disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <svg x-show="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span x-text="isLoading ? 'Memproses...' : 'Masuk'"></span>
                </button>

                {{-- Divider --}}
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-3 text-xs text-blue-300 bg-transparent">Belum punya akun?</span>
                    </div>
                </div>

                {{-- Link Register --}}
                <a href="{{ route('register') }}"
                    class="w-full flex items-center justify-center gap-2 py-3 px-4
                           border border-white/20 text-white text-sm font-medium rounded-xl
                           hover:bg-white/10 transition">
                    Daftar sebagai SPPG
                </a>

            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-blue-300/60 text-xs mt-6">
            &copy; {{ date('Y') }} Koperasi Argo Ketapang — Kediri
        </p>
    </div>

    <script>
function loginForm() {
    return {
        form: { email: '', password: '' },
        showPassword: false,
        isLoading: false,

        onSubmit(event) {
            /*
             * BELAJAR JS: Form Submit Handler
             * Kita intercept submit untuk aktifkan loading state,
             * lalu biarkan form submit secara normal ke server.
             */
            if (!this.form.email || !this.form.password) {
                event.preventDefault();
                return;
            }
            this.isLoading = true;
            // Form submit normal ke server (tidak preventDefault)
        }
    }
}
    </script>
    
</body>
</html>

