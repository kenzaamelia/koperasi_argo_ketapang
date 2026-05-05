@extends('layouts.admin')

@section('title', 'Foto Menu SPPG')
@section('page-title', 'Foto Menu SPPG')
@section('page-subtitle', 'Semua foto menu mingguan dari seluruh SPPG')

@section('content')

<div x-data="adminMenuManager()"
     x-init="init()">

    {{-- Filter SPPG --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <select
            x-model="filterSppg"
            @change="filter()"
            class="text-sm border border-slate-200 rounded-xl px-3 py-2.5 bg-white
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="">Semua SPPG</option>
            @foreach($sppgList as $sppg)
                <option value="{{ $sppg->id }}">
                    {{ $sppg->sppgProfile->nama_sppg ?? $sppg->name }}
                </option>
            @endforeach
        </select>

        <p class="text-sm text-slate-500 self-center">
            Menampilkan <span class="font-semibold text-slate-700" x-text="menuTampil.length"></span> foto menu
        </p>
    </div>

    {{-- Grid Menu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="menu in menuTampil" :key="menu.id">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group">

                {{-- Foto --}}
                <div class="relative h-48 bg-slate-100 cursor-pointer overflow-hidden"
                    @click="bukaLightbox('/storage/' + menu.foto, menu.judul || 'Menu Mingguan')">
                    <img
                        :src="'/storage/' + menu.foto"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                        alt=""
                    />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                        <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <p class="font-semibold text-slate-800 truncate" x-text="menu.judul || 'Menu Mingguan'"></p>
                    <p class="text-xs text-blue-600 font-medium mt-1"
                        x-text="menu.user?.sppg_profile?.nama_sppg || menu.user?.name || '-'">
                    </p>
                    <p class="text-xs text-slate-400 mt-1"
                        x-text="formatTanggal(menu.tanggal_mulai) + ' — ' + formatTanggal(menu.tanggal_selesai)">
                    </p>
                </div>
            </div>
        </template>

        {{-- Empty --}}
        <div x-show="menuTampil.length === 0"
            class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-100">
            <p class="text-slate-400">Belum ada foto menu diupload</p>
        </div>
    </div>

    {{-- Lightbox --}}
    <div
        x-show="lightboxOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
        @click.self="lightboxOpen = false"
        @keydown.escape.window="lightboxOpen = false"
    >
        <div class="relative max-w-4xl w-full">
            <button @click="lightboxOpen = false"
                class="absolute -top-12 right-0 text-white/70 hover:text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img :src="lightboxSrc" :alt="lightboxCaption"
                class="w-full max-h-[80vh] object-contain rounded-xl"/>
            <p class="text-white/70 text-sm text-center mt-3" x-text="lightboxCaption"></p>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function adminMenuManager() {
    return {
        semuaMenu:   @json($menus),
        menuTampil:  [],
        filterSppg:  '',
        lightboxOpen:    false,
        lightboxSrc:     '',
        lightboxCaption: '',

        init() { this.menuTampil = this.semuaMenu; },

        filter() {
            this.menuTampil = this.semuaMenu.filter(m =>
                this.filterSppg === '' || m.user_id == this.filterSppg
            );
        },

        bukaLightbox(src, caption) {
            this.lightboxSrc     = src;
            this.lightboxCaption = caption;
            this.lightboxOpen    = true;
        },

        formatTanggal(tgl) {
            /*
             * BELAJAR JS: Intl.DateTimeFormat
             * API internasionalisasi bawaan browser untuk format tanggal.
             * Lebih powerful dari toLocaleDateString() karena bisa dikustomisasi.
             */
            return new Intl.DateTimeFormat('id-ID', {
                day: 'numeric', month: 'short', year: 'numeric'
            }).format(new Date(tgl));
        },
    }
}
</script>
@endpush