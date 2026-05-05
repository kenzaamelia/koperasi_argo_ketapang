<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SPPG — Koperasi Argo Ketapang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-green-800 flex items-center justify-center p-4 py-10">

    {{-- Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-green-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-lg">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Daftar Akun SPPG</h1>
            <p class="text-blue-200 text-sm mt-1">Koperasi Argo Ketapang — Kediri</p>
        </div>

        {{-- Card Register --}}
        <div
            x-data="registerForm()"
            class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 shadow-2xl"
        >
            {{-- Step Indicator --}}
            <div class="flex items-center gap-2 mb-7">
                {{-- 
                    BELAJAR JS: Multi-step Form
                    Kita bagi form menjadi 2 langkah (step).
                    step === 1 → tampilkan form data akun
                    step === 2 → tampilkan form data SPPG
                --}}
                <div class="flex-1 flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 1 ? 'bg-white text-blue-800' : 'bg-white/20 text-white'"
                    >1</div>
                    <div class="flex-1 h-0.5 transition-all duration-300"
                        :class="step >= 2 ? 'bg-white' : 'bg-white/20'"></div>
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 2 ? 'bg-white text-blue-800' : 'bg-white/20 text-white'"
                    >2</div>
                </div>
                <div class="ml-3 text-xs text-blue-200">
                    <span x-text="step === 1 ? 'Data Akun' : 'Data SPPG'"></span>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" @submit="onSubmit($event)">
                @csrf

                {{-- Error dari server --}}
                @if ($errors->any())
                <div class="mb-5 px-4 py-3 bg-red-500/20 border border-red-400/30 rounded-xl">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-200 text-xs flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- ========================= --}}
                {{-- STEP 1: Data Akun --}}
                {{-- ========================= --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="space-y-4"
                >
                    <p class="text-white font-medium mb-4">Data Akun Login</p>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input
                                type="text"
                                name="name"
                                x-model="form.name"
                                placeholder="Nama penanggung jawab"
                                value="{{ old('name') }}"
                                class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl
                                       text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                       focus:ring-white/30 text-sm"
                                required
                            />
                        </div>
                    </div>

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
                                x-model="form.email"
                                placeholder="email@sppg.com"
                                value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl
                                       text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                       focus:ring-white/30 text-sm"
                                required
                            />
                        </div>
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
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                x-model="form.password"
                                @input="cekKekuatanPassword()"
                                placeholder="Min. 8 karakter"
                                class="w-full pl-10 pr-12 py-3 bg-white/10 border border-white/20 rounded-xl
                                       text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                       focus:ring-white/30 text-sm"
                                required
                            />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-blue-300 hover:text-white transition">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>

                        {{-- 
                            BELAJAR JS: Password Strength Indicator
                            Kita hitung kekuatan password berdasarkan kriteria:
                            - Panjang >= 8 karakter
                            - Ada huruf besar
                            - Ada angka
                            - Ada karakter spesial
                            Setiap kriteria terpenuhi → strength bertambah
                        --}}
                        <div x-show="form.password.length > 0" class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div class="flex-1 h-1 rounded-full transition-all duration-300"
                                    :class="passwordStrength >= 1 ? strengthColor : 'bg-white/20'"></div>
                                <div class="flex-1 h-1 rounded-full transition-all duration-300"
                                    :class="passwordStrength >= 2 ? strengthColor : 'bg-white/20'"></div>
                                <div class="flex-1 h-1 rounded-full transition-all duration-300"
                                    :class="passwordStrength >= 3 ? strengthColor : 'bg-white/20'"></div>
                                <div class="flex-1 h-1 rounded-full transition-all duration-300"
                                    :class="passwordStrength >= 4 ? strengthColor : 'bg-white/20'"></div>
                            </div>
                            <p class="text-xs transition-colors duration-300"
                                :class="strengthTextColor"
                                x-text="strengthLabel">
                            </p>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-1.5">Konfirmasi Password</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password_confirmation"
                                x-model="form.password_confirmation"
                                placeholder="Ulangi password"
                                class="w-full pl-10 pr-4 py-3 bg-white/10 border rounded-xl
                                       text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                       focus:ring-white/30 text-sm transition-colors"
                                :class="form.password_confirmation && form.password !== form.password_confirmation
                                    ? 'border-red-400/50'
                                    : form.password_confirmation && form.password === form.password_confirmation
                                        ? 'border-green-400/50'
                                        : 'border-white/20'"
                                required
                            />
                            {{-- Ikon cocok/tidak --}}
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2">
                                <svg x-show="form.password_confirmation && form.password === form.password_confirmation"
                                    class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg x-show="form.password_confirmation && form.password !== form.password_confirmation"
                                    class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </div>
                        <p x-show="form.password_confirmation && form.password !== form.password_confirmation"
                            class="text-red-300 text-xs mt-1">Password tidak cocok</p>
                    </div>

                    {{-- Tombol Lanjut --}}
                    <button
                        type="button"
                        @click="lanjutStep2()"
                        class="w-full py-3 bg-white text-blue-800 font-semibold text-sm rounded-xl
                               hover:bg-blue-50 transition shadow-lg mt-2"
                    >
                        Lanjut →
                    </button>
                </div>

                {{-- ========================= --}}
                {{-- STEP 2: Data SPPG --}}
                {{-- ========================= --}}
                <div x-show="step === 2"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="space-y-4"
                >
                    <p class="text-white font-medium mb-4">Data Informasi SPPG</p>

                    {{-- Nama SPPG --}}
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-1.5">Nama SPPG <span class="text-red-300">*</span></label>
                        <input
                            type="text"
                            name="nama_sppg"
                            x-model="form.nama_sppg"
                            placeholder="Contoh: SPPG Mojoroto"
                            value="{{ old('nama_sppg') }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl
                                   text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                   focus:ring-white/30 text-sm"
                            required
                        />
                    </div>

                    {{-- Kode SPPG --}}
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-1.5">Kode SPPG <span class="text-red-300">*</span></label>
                        <input
                            type="text"
                            name="kode_sppg"
                            x-model="form.kode_sppg"
                            @input="form.kode_sppg = form.kode_sppg.toUpperCase()"
                            placeholder="Contoh: SPPG-001"
                            value="{{ old('kode_sppg') }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl
                                   text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                   focus:ring-white/30 text-sm uppercase"
                            required
                        />
                        <p class="text-blue-300/70 text-xs mt-1">Kode unik SPPG akan otomatis diubah ke huruf kapital</p>
                    </div>

                    {{-- No Telepon --}}
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-1.5">No. Telepon <span class="text-red-300">*</span></label>
                        <input
                            type="text"
                            name="no_telepon"
                            x-model="form.no_telepon"
                            placeholder="08xxxxxxxxxx"
                            value="{{ old('no_telepon') }}"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl
                                   text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                   focus:ring-white/30 text-sm"
                            required
                        />
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-medium text-blue-100 mb-1.5">Alamat <span class="text-red-300">*</span></label>
                        <textarea
                            name="alamat"
                            x-model="form.alamat"
                            rows="2"
                            placeholder="Alamat lengkap SPPG"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl
                                   text-white placeholder-blue-300 focus:outline-none focus:ring-2
                                   focus:ring-white/30 text-sm resize-none"
                            required
                        >{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-3 pt-1">
                        <button
                            type="button"
                            @click="step = 1"
                            class="flex-1 py-3 border border-white/20 text-white text-sm font-medium
                                   rounded-xl hover:bg-white/10 transition"
                        >
                            ← Kembali
                        </button>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="flex-1 flex items-center justify-center gap-2 py-3
                                   bg-white text-blue-800 font-semibold text-sm rounded-xl
                                   hover:bg-blue-50 transition shadow-lg
                                   disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            <svg x-show="isLoading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span x-text="isLoading ? 'Mendaftar...' : 'Daftar Sekarang'"></span>
                        </button>
                    </div>
                </div>

            </form>

            {{-- Link Login --}}
            <p class="text-center text-blue-200 text-sm mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>

        <p class="text-center text-blue-300/60 text-xs mt-6">
            &copy; {{ date('Y') }} Koperasi Argo Ketapang — Kediri
        </p>
    </div>

    <script>
function registerForm() {
    return {
        step: 1,
        showPassword: false,
        isLoading: false,
        passwordStrength: 0,

        form: {
            name:                  '{{ old('name') }}',
            email:                 '{{ old('email') }}',
            password:              '',
            password_confirmation: '',
            nama_sppg:             '{{ old('nama_sppg') }}',
            kode_sppg:             '{{ old('kode_sppg') }}',
            no_telepon:            '{{ old('no_telepon') }}',
            alamat:                '{{ old('alamat') }}',
        },

        // Computed: label kekuatan password
        get strengthLabel() {
            return ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'][this.passwordStrength] || '';
        },

        // Computed: warna bar kekuatan
        get strengthColor() {
            return ['', 'bg-red-400', 'bg-yellow-400', 'bg-blue-400', 'bg-green-400'][this.passwordStrength] || '';
        },

        // Computed: warna teks kekuatan
        get strengthTextColor() {
            return ['', 'text-red-300', 'text-yellow-300', 'text-blue-300', 'text-green-300'][this.passwordStrength] || '';
        },

        // Hitung kekuatan password
        cekKekuatanPassword() {
            /*
             * BELAJAR JS: Regular Expression (RegEx)
             * RegEx adalah pola untuk mencari/mencocokkan karakter dalam string.
             * /[A-Z]/ → cocokkan huruf besar
             * /[0-9]/ → cocokkan angka
             * /[^a-zA-Z0-9]/ → cocokkan karakter spesial (bukan huruf/angka)
             * .test(string) → kembalikan true/false
             */
            const p = this.form.password;
            let score = 0;

            if (p.length >= 8)           score++; // Cukup panjang
            if (/[A-Z]/.test(p))         score++; // Ada huruf besar
            if (/[0-9]/.test(p))         score++; // Ada angka
            if (/[^a-zA-Z0-9]/.test(p)) score++; // Ada karakter spesial

            this.passwordStrength = score;
        },

        // Validasi step 1 sebelum lanjut
        lanjutStep2() {
            /*
             * BELAJAR JS: Client-side Validation
             * Kita validasi di browser sebelum mengirim ke server.
             * Ini untuk UX yang lebih baik — user dapat feedback langsung.
             * Validasi server tetap diperlukan sebagai lapisan keamanan.
             */
            if (!this.form.name.trim()) {
                alert('Nama lengkap wajib diisi!');
                return;
            }
            if (!this.form.email.trim()) {
                alert('Email wajib diisi!');
                return;
            }
            if (this.form.password.length < 8) {
                alert('Password minimal 8 karakter!');
                return;
            }
            if (this.form.password !== this.form.password_confirmation) {
                alert('Konfirmasi password tidak cocok!');
                return;
            }

            // Semua valid → lanjut ke step 2
            this.step = 2;
        },

        onSubmit(event) {
            // Validasi step 2
            if (!this.form.nama_sppg.trim() || !this.form.kode_sppg.trim() || !this.form.alamat.trim()) {
                event.preventDefault();
                alert('Lengkapi semua data SPPG!');
                return;
            }
            this.isLoading = true;
        },
    }
}
    </script>
    
</body>
</html>


