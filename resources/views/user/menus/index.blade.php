@extends('layouts.user')

@section('title', 'Menu Mingguan Saya')
@section('page-title', 'Menu Mingguan')
@section('page-subtitle', 'Riwayat upload foto menu mingguanmu')

@section('content')

<div x-data="menuManager()">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-slate-500">
            Total <span class="font-semibold text-slate-700">{{ $menus->count() }}</span> menu diupload
        </p>
        <a href="{{ route('user.menus.create') }}"
            class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-green-600
                   text-white text-sm font-medium rounded-xl hover:opacity-90 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload Menu Baru
        </a>
    </div>

    @if($menus->count() > 0)

    {{-- Grid Menu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($menus as $menu)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group">

            {{-- Foto --}}
            <div class="relative h-48 bg-slate-100 overflow-hidden">
                <img
                    src="{{ Storage::url($menu->foto) }}"
                    alt="{{ $menu->judul }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300 cursor-pointer"
                    @click="bukaLightbox('{{ Storage::url($menu->foto) }}', '{{ $menu->judul ?? 'Menu Mingguan' }}')"
                />

                {{-- Tombol hapus di atas foto --}}
                <button
                    @click="konfirmasiHapus({{ $menu->id }}, '{{ $menu->judul ?? 'menu ini' }}')"
                    class="absolute top-3 right-3 w-8 h-8 bg-red-500 text-white rounded-full
                           flex items-center justify-center opacity-0 group-hover:opacity-100
                           transition hover:bg-red-600 shadow"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-semibold text-slate-800 truncate">
                    {{ $menu->judul ?? 'Menu Mingguan' }}
                </h3>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs text-slate-500">
                        {{ $menu->tanggal_mulai->format('d M') }} —
                        {{ $menu->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                @if($menu->keterangan)
                <p class="text-xs text-slate-400 mt-2 line-clamp-2">{{ $menu->keterangan }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @else
    {{-- Empty State --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="font-semibold text-slate-700 mb-1">Belum ada menu diupload</h3>
        <p class="text-sm text-slate-400 mb-5">Upload foto menu mingguanmu sekarang!</p>
        <a href="{{ route('user.menus.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-green-600
                   text-white text-sm font-medium rounded-xl hover:opacity-90 transition">
            Upload Sekarang
        </a>
    </div>
    @endif

    {{-- Lightbox --}}
    <div
        x-show="lightboxOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
        @click.self="lightboxOpen = false"
        @keydown.escape.window="lightboxOpen = false"
    >
        <div class="relative max-w-4xl w-full">
            {{-- Tombol tutup --}}
            <button
                @click="lightboxOpen = false"
                class="absolute -top-12 right-0 text-white/70 hover:text-white transition"
            >
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Gambar --}}
            <img
                :src="lightboxSrc"
                :alt="lightboxCaption"
                class="w-full max-h-[80vh] object-contain rounded-xl"
            />
            <p class="text-white/70 text-sm text-center mt-3" x-text="lightboxCaption"></p>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div
        x-show="hapusOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        @click.self="hapusOpen = false"
    >
        <div
            x-show="hapusOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center"
            @click.stop
        >
            <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Menu?</h3>
            <p class="text-sm text-slate-500 mb-6">
                Kamu akan menghapus
                <span class="font-semibold text-slate-700" x-text="'&quot;' + hapusNama + '&quot;'"></span>.
                Foto akan ikut terhapus permanen.
            </p>
            <div class="flex gap-3">
                <button @click="hapusOpen = false"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                    Batal
                </button>
                <form :action="'/user/menus/' + hapusId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-500 rounded-xl hover:bg-red-600 transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function menuManager() {
    return {
        // Lightbox state
        lightboxOpen:    false,
        lightboxSrc:     '',
        lightboxCaption: '',

        // Hapus state
        hapusOpen: false,
        hapusId:   null,
        hapusNama: '',

        bukaLightbox(src, caption) {
            /*
             * BELAJAR JS: Lightbox sederhana
             * Kita simpan URL gambar dan caption ke state,
             * lalu tampilkan overlay fullscreen dengan gambar tersebut.
             */
            this.lightboxSrc     = src;
            this.lightboxCaption = caption;
            this.lightboxOpen    = true;
        },

        konfirmasiHapus(id, nama) {
            this.hapusId   = id;
            this.hapusNama = nama;
            this.hapusOpen = true;
        },
    }
}
</script>
@endpush