@extends('layouts.user')

@section('title', 'Katalog Bahan Baku')
@section('page-title', 'Katalog Bahan Baku')
@section('page-subtitle', 'Daftar produk dan harga dari Koperasi Argo Ketapang')

@section('content')

<div x-data="katalogManager()">

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <div class="flex flex-col sm:flex-row gap-3">

            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    type="text"
                    placeholder="Cari bahan baku..."
                    x-model="search"
                    @input="filter()"
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            {{-- Filter Kategori --}}
            <div class="flex gap-2 flex-wrap">
                <button
                    @click="filterKategori = ''; filter()"
                    :class="filterKategori === '' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 text-xs font-medium rounded-xl transition"
                >
                    Semua
                </button>
                @foreach($categories as $cat)
                <button
                    @click="filterKategori = '{{ $cat->id }}'; filter()"
                    :class="filterKategori === '{{ $cat->id }}' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 text-xs font-medium rounded-xl transition"
                >
                    {{ $cat->nama_kategori }}
                </button>
                @endforeach
            </div>

        </div>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="produk in produkTampil" :key="produk.id">
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden
                       hover:shadow-md hover:border-blue-200 transition group"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                {{-- Foto Produk --}}
                <div class="h-44 bg-slate-50 overflow-hidden relative">
                    <img
                        :src="produk.foto ? '/storage/' + produk.foto : ''"
                        :class="produk.foto ? 'w-full h-full object-cover group-hover:scale-105 transition duration-300' : 'hidden'"
                        alt=""
                    />
                    <div
                        :class="produk.foto ? 'hidden' : 'w-full h-full flex flex-col items-center justify-center'"
                    >
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>

                    {{-- Badge Kategori --}}
                    <div class="absolute top-3 left-3">
                        <span class="text-xs bg-white/90 backdrop-blur-sm text-blue-700 px-2.5 py-1 rounded-full font-medium shadow-sm"
                            x-text="produk.category?.nama_kategori || '-'">
                        </span>
                    </div>
                </div>

                {{-- Info Produk --}}
                <div class="p-4">
                    <h3 class="font-semibold text-slate-800 mb-1 truncate" x-text="produk.nama_produk"></h3>
                    <p class="text-xs text-slate-400 mb-3" x-text="produk.deskripsi || 'Tidak ada deskripsi'"></p>

                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-lg font-bold text-green-600" x-text="formatRupiah(produk.harga)"></p>
                            <p class="text-xs text-slate-400" x-text="'per ' + produk.satuan"></p>
                        </div>

                        {{-- Stok --}}
                        <div class="text-right">
                            <span
                                :class="produk.stok > 10 ? 'text-slate-500' : produk.stok > 0 ? 'text-yellow-600' : 'text-red-500'"
                                class="text-xs font-medium"
                                x-text="'Stok: ' + produk.stok + ' ' + produk.satuan"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty State --}}
        <div x-show="produkTampil.length === 0" class="col-span-full py-16 text-center">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-slate-400 font-medium">Produk tidak ditemukan</p>
            <p class="text-slate-300 text-sm mt-1">Coba ubah kata pencarian atau filter kategori</p>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function katalogManager() {
    return {
        semuaProduk:  @json($products),
        produkTampil: [],
        search: '',
        filterKategori: '',

        init() {
            this.produkTampil = this.semuaProduk;
        },

        filter() {
            this.produkTampil = this.semuaProduk.filter(p => {
                const cocokSearch    = p.nama_produk.toLowerCase().includes(this.search.toLowerCase());
                const cocokKategori  = this.filterKategori === '' || p.category_id == this.filterKategori;
                return cocokSearch && cocokKategori;
            });
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
            }).format(angka);
        },
    }
}
</script>
@endpush