@extends('layouts.user')

@section('title', 'Upload Menu Mingguan')
@section('page-title', 'Upload Menu Mingguan')
@section('page-subtitle', 'Unggah foto menu minggu ini')

@section('content')

<div class="max-w-2xl mx-auto">
<div
    x-data="uploadMenu()"
    class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden"
>

    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 py-5">
        <h2 class="text-white font-semibold text-lg">Upload Foto Menu</h2>
        <p class="text-blue-100 text-sm mt-0.5">Format: JPG, PNG, WEBP — Maks 5MB</p>
    </div>

    <form
        id="formUploadMenu"
        action="{{ route('user.menus.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="p-6 space-y-5"
    >
        @csrf

        {{-- Error dari server --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="text-sm text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Upload Area --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Foto Menu <span class="text-red-500">*</span>
            </label>

            <div
                class="relative border-2 border-dashed rounded-2xl transition-all duration-200 cursor-pointer"
                :class="isDragging
                    ? 'border-blue-400 bg-blue-50 scale-[1.01]'
                    : 'border-slate-200 hover:border-blue-300 hover:bg-slate-50'"
                @click="$refs.inputFoto.click()"
                @dragover.prevent="isDragging = true"
                @dragleave="isDragging = false"
                @drop.prevent="handleDrop($event)"
            >
                {{-- Preview Foto --}}
                <div x-show="previewUrl" class="p-4">
                    <div class="relative group">
                        <img
                            :src="previewUrl"
                            class="w-full max-h-72 object-contain rounded-xl"
                            alt="Preview"
                        />
                        {{-- Overlay hapus --}}
                        <div
                            class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100
                                   transition flex items-center justify-center"
                            @click.stop="hapusFoto()"
                        >
                            <div class="bg-white rounded-full p-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    {{-- Info file --}}
                    <div class="mt-3 flex items-center justify-between px-1">
                        <p class="text-sm text-slate-600 font-medium truncate" x-text="namaFile"></p>
                        <p class="text-xs text-slate-400 flex-shrink-0 ml-2" x-text="ukuranFile"></p>
                    </div>
                </div>

                {{-- Placeholder --}}
                <div x-show="!previewUrl" class="py-12 px-6 text-center">
                    <div
                        class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center transition-colors"
                        :class="isDragging ? 'bg-blue-100' : 'bg-slate-100'"
                    >
                        <svg class="w-8 h-8 transition-colors"
                            :class="isDragging ? 'text-blue-500' : 'text-slate-300'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                    </div>
                    <p class="text-slate-600 font-medium text-sm"
                        x-text="isDragging ? 'Lepaskan file di sini!' : 'Klik atau drag & drop foto'">
                    </p>
                    <p class="text-slate-400 text-xs mt-1">JPG, PNG, WEBP hingga 5MB</p>

                    {{-- Progress bar muncul saat loading --}}
                    <div x-show="isLoading" class="mt-4">
                        <div class="w-48 mx-auto bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full animate-pulse w-3/4"></div>
                        </div>
                        <p class="text-xs text-blue-500 mt-2">Memproses gambar...</p>
                    </div>
                </div>

                {{-- Hidden input --}}
                <input
                    type="file"
                    name="foto"
                    x-ref="inputFoto"
                    class="hidden"
                    accept="image/jpg,image/jpeg,image/png,image/webp"
                    @change="handleFileSelect($event)"
                />
            </div>
        </div>

        {{-- Judul --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Menu</label>
            <input
                type="text"
                name="judul"
                x-model="form.judul"
                placeholder="Contoh: Menu Minggu 1 Januari 2025"
                value="{{ old('judul') }}"
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        {{-- Tanggal --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    name="tanggal_mulai"
                    x-model="form.tanggal_mulai"
                    @change="validasiTanggal()"
                    value="{{ old('tanggal_mulai') }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Tanggal Selesai <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    name="tanggal_selesai"
                    x-model="form.tanggal_selesai"
                    @change="validasiTanggal()"
                    value="{{ old('tanggal_selesai') }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                />
            </div>
        </div>

        {{-- Error tanggal --}}
        <p x-show="errorTanggal" x-text="errorTanggal"
            class="text-xs text-red-500 -mt-3"></p>

        {{-- Rentang hari --}}
        <div
            x-show="form.tanggal_mulai && form.tanggal_selesai && !errorTanggal"
            class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 -mt-2"
        >
            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-blue-700">
                Rentang menu: <span class="font-semibold" x-text="rentangHari + ' hari'"></span>
            </p>
        </div>

        {{-- Keterangan --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan</label>
            <textarea
                name="keterangan"
                x-model="form.keterangan"
                rows="3"
                placeholder="Informasi tambahan tentang menu minggu ini..."
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
            >{{ old('keterangan') }}</textarea>
            {{-- Counter karakter --}}
            <p class="text-xs text-slate-400 text-right mt-1"
                x-text="form.keterangan.length + ' karakter'">
            </p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-3 pt-2">
            <a href="{{ route('user.menus.index') }}"
                class="flex-1 flex items-center justify-center px-4 py-3 text-sm font-medium
                    text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                Batal
            </a>

            <button
                type="button"
                :disabled="isSubmitting"
                @click="submitManual()"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium
                    text-white bg-gradient-to-r from-blue-600 to-green-600 rounded-xl
                    hover:from-blue-700 hover:to-green-700 transition shadow-sm
                    disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <svg x-show="isSubmitting" class="animate-spin w-4 h-4"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span x-text="isSubmitting ? 'Mengupload...' : 'Upload Menu'"></span>
            </button>
        </div>

    </form>
</div>
</div>

@endsection

@push('scripts')
<script>
function uploadMenu() {
    return {
        // State
        previewUrl:   null,
        namaFile:     '',
        ukuranFile:   '',
        isDragging:   false,
        isLoading:    false,
        isSubmitting: false,
        errorTanggal: '',

        form: {
            judul:            '',
            tanggal_mulai:    '',
            tanggal_selesai:  '',
            keterangan:       '',
        },

        init() {
            // Restore old values setelah validation error dari server
            @if(old('judul'))
                this.form.judul = '{{ old('judul') }}';
            @endif
            @if(old('tanggal_mulai'))
                this.form.tanggal_mulai = '{{ old('tanggal_mulai') }}';
            @endif
            @if(old('tanggal_selesai'))
                this.form.tanggal_selesai = '{{ old('tanggal_selesai') }}';
            @endif
            @if(old('keterangan'))
                this.form.keterangan = '{{ old('keterangan') }}';
            @endif

            // Jika ada old value tanggal, jalankan validasi ulang
            if (this.form.tanggal_mulai || this.form.tanggal_selesai) {
                this.validasiTanggal();
            }
        },

        // Hitung rentang hari antara dua tanggal
        get rentangHari() {
            if (!this.form.tanggal_mulai || !this.form.tanggal_selesai) return 0;
            const mulai   = new Date(this.form.tanggal_mulai).getTime();
            const selesai = new Date(this.form.tanggal_selesai).getTime();
            const selisih = selesai - mulai;
            return Math.ceil(selisih / (1000 * 60 * 60 * 24)) + 1;
        },

        // Validasi tanggal — simpan ke errorTanggal (string kosong = tidak ada error)
        validasiTanggal() {
            if (!this.form.tanggal_mulai || !this.form.tanggal_selesai) {
                this.errorTanggal = '';
                return;
            }
            if (new Date(this.form.tanggal_selesai) < new Date(this.form.tanggal_mulai)) {
                this.errorTanggal = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
            } else {
                this.errorTanggal = '';
            }
        },

        // Handle pilih file lewat input
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.prosesFile(file);
        },

        // Handle drag & drop file
        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;

            // Pindahkan file ke input[type=file] agar ikut ter-submit form
            const dt = new DataTransfer();
            dt.items.add(file);
            this.$refs.inputFoto.files = dt.files;

            this.prosesFile(file);
        },

        // Proses file: validasi tipe & ukuran, lalu buat preview
        prosesFile(file) {
            const tipeValid = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!tipeValid.includes(file.type)) {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { pesan: 'Format file tidak didukung! Gunakan JPG, PNG, atau WEBP.', type: 'error' }
                }));
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { pesan: 'Ukuran file terlalu besar! Maksimal 5MB.', type: 'error' }
                }));
                return;
            }

            this.isLoading  = true;
            this.previewUrl = URL.createObjectURL(file);
            this.namaFile   = file.name;
            this.ukuranFile = file.size < 1024 * 1024
                ? (file.size / 1024).toFixed(1) + ' KB'
                : (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            setTimeout(() => { this.isLoading = false; }, 300);

            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { pesan: 'Foto berhasil dipilih!', type: 'success' }
            }));
        },

        // Hapus foto yang sudah dipilih
        hapusFoto() {
            if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
            this.previewUrl = null;
            this.namaFile   = '';
            this.ukuranFile = '';
            this.$refs.inputFoto.value = '';
        },

        // Submit form dengan validasi manual
        submitManual() {
            // 1. Wajib ada foto
            if (!this.previewUrl) {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { pesan: 'Foto menu wajib diupload!', type: 'error' }
                }));
                return;
            }

            // 2. Cek error tanggal (string kosong = tidak ada error)
            if (this.errorTanggal !== '') {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { pesan: this.errorTanggal, type: 'error' }
                }));
                return;
            }

            // 3. Semua valid → submit form
            this.isSubmitting = true;
            document.getElementById('formUploadMenu').submit();
        },
    }
}
</script>
@endpush