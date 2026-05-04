@extends('layouts.admin')

@section('title', 'Produk & Bahan Baku')
@section('page-title', 'Produk & Bahan Baku')
@section('page-subtitle', 'Kelola data produk dan bahan baku koperasi')

@section('content')

{{-- 
    BELAJAR ALPINE.JS:
    x-data      = mendefinisikan reactive data (seperti state di React)
    x-show      = menampilkan/menyembunyikan elemen berdasarkan kondisi
    x-model     = two-way binding antara input dan data
    x-on / @    = event listener
    x-transition = animasi saat elemen muncul/hilang
    $dispatch   = mengirim custom event ke komponen Alpine lain
--}}
<div
    x-data="produkManager()"
    x-init="init()"
>

    {{-- Header + Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        
        {{-- Search Box dengan debounce --}}
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            {{-- 
                x-model="search" → nilai input tersimpan di variabel 'search'
                @input="filterProduk()" → panggil fungsi setiap kali user mengetik
            --}}
            <input
                type="text"
                placeholder="Cari produk..."
                x-model="search"
                @input="filterProduk()"
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
            />
        </div>

        {{-- Filter Kategori --}}
        <select
            x-model="filterKategori"
            @change="filterProduk()"
            class="text-sm border border-slate-200 rounded-xl px-3 py-2.5 bg-white
                   focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
            @endforeach
        </select>

        {{-- Tombol Tambah → buka modal --}}
        <button
            @click="bukaModalTambah()"
            class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700
                   text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-blue-800
                   transition shadow-sm shadow-blue-200"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </button>
    </div>

    {{-- Tabel Produk --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Counter hasil filter --}}
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <p class="text-sm text-slate-500">
                Menampilkan <span class="font-semibold text-slate-700" x-text="produkTampil.length"></span> produk
            </p>
            {{-- Badge total --}}
            <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium">
                Total: {{ $products->count() }} produk
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Produk</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3 hidden sm:table-cell">Kategori</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Harga</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3 hidden md:table-cell">Stok</th>
                        <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3 hidden md:table-cell">Status</th>
                        <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    {{-- 
                        Template iteration Alpine.js
                        x-for = loop seperti @foreach tapi di sisi JavaScript (client-side)
                        :key  = identifier unik tiap item
                    --}}
                    <template x-for="produk in produkTampil" :key="produk.id">
                        <tr class="hover:bg-slate-50 transition">

                            {{-- Foto + Nama --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Foto produk atau placeholder --}}
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                                        <img
                                            :src="produk.foto ? '/storage/' + produk.foto : ''"
                                            :class="produk.foto ? 'w-full h-full object-cover' : 'hidden'"
                                            alt=""
                                        />
                                        {{-- Placeholder jika tidak ada foto --}}
                                        <div :class="produk.foto ? 'hidden' : 'w-full h-full flex items-center justify-center'">
                                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-800" x-text="produk.nama_produk"></p>
                                        <p class="text-xs text-slate-400" x-text="'per ' + produk.satuan"></p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-5 py-4 hidden sm:table-cell">
                                <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full"
                                    x-text="produk.category ? produk.category.nama_kategori : '-'">
                                </span>
                            </td>

                            {{-- Harga --}}
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-green-600"
                                    x-text="formatRupiah(produk.harga)">
                                </p>
                            </td>

                            {{-- Stok --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span
                                    :class="produk.stok > 10
                                        ? 'text-slate-700'
                                        : produk.stok > 0
                                            ? 'text-yellow-600'
                                            : 'text-red-500'"
                                    class="text-sm font-medium"
                                    x-text="produk.stok + ' ' + produk.satuan"
                                ></span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span
                                    :class="produk.is_available
                                        ? 'bg-green-50 text-green-700'
                                        : 'bg-red-50 text-red-600'"
                                    class="text-xs font-medium px-2.5 py-1 rounded-full"
                                    x-text="produk.is_available ? 'Tersedia' : 'Habis'"
                                ></span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Tombol Edit --}}
                                    <button
                                        @click="bukaModalEdit(produk)"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button
                                        @click="hapusProduk(produk)"
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty state --}}
                    <tr x-show="produkTampil.length === 0">
                        <td colspan="6" class="px-5 py-12 text-center">
                            <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-slate-400 text-sm">Tidak ada produk ditemukan</p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL TAMBAH / EDIT PRODUK --}}
    {{-- ============================================================ --}}
    <div
        x-show="modalOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="tutupModal()"
    >
        <div
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800" x-text="isEdit ? 'Edit Produk' : 'Tambah Produk Baru'"></h3>
                <button @click="tutupModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body — Form --}}
            <form
                :action="isEdit ? '/admin/products/' + produkEdit.id : '/admin/products'"
                method="POST"
                enctype="multipart/form-data"
                @submit="submitForm($event)"
                class="px-6 py-5 space-y-4"
            >
                @csrf
                {{-- Method spoofing untuk PUT (edit) --}}
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                {{-- Nama Produk --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_produk"
                        x-model="form.nama_produk"
                        placeholder="Contoh: Beras Premium"
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                    {{-- Error validasi --}}
                    <p x-show="errors.nama_produk" x-text="errors.nama_produk"
                        class="text-xs text-red-500 mt-1"></p>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="category_id"
                        x-model="form.category_id"
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga & Satuan --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="harga"
                            x-model="form.harga"
                            placeholder="15000"
                            min="0"
                            class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        />
                        {{-- Preview harga format Rupiah secara real-time --}}
                        <p x-show="form.harga > 0"
                            x-text="'= ' + formatRupiah(form.harga)"
                            class="text-xs text-green-600 mt-1">
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="satuan"
                            x-model="form.satuan"
                            class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih</option>
                            <option value="kg">kg</option>
                            <option value="gram">gram</option>
                            <option value="liter">liter</option>
                            <option value="ml">ml</option>
                            <option value="pcs">pcs</option>
                            <option value="ikat">ikat</option>
                            <option value="lusin">lusin</option>
                            <option value="karung">karung</option>
                        </select>
                    </div>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="stok"
                        x-model="form.stok"
                        placeholder="0"
                        min="0"
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        x-model="form.deskripsi"
                        rows="3"
                        placeholder="Deskripsi singkat produk..."
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    ></textarea>
                </div>

                {{-- Upload Foto --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Foto Produk</label>

                    {{-- 
                        BELAJAR JS: File Upload dengan Preview
                        @change → event saat file dipilih
                        $event.target.files[0] → file yang dipilih user
                        URL.createObjectURL() → membuat URL sementara untuk preview
                    --}}
                    <div
                        class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center
                               hover:border-blue-300 transition cursor-pointer"
                        @click="$refs.fotoInput.click()"
                        @dragover.prevent
                        @drop.prevent="handleDrop($event)"
                    >
                        {{-- Preview gambar --}}
                        <div x-show="previewUrl">
                            <img :src="previewUrl" class="w-32 h-32 object-cover rounded-xl mx-auto mb-2" />
                            <p class="text-xs text-slate-500" x-text="namaFile"></p>
                        </div>

                        {{-- Placeholder --}}
                        <div x-show="!previewUrl">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-slate-400">Klik atau drag & drop gambar di sini</p>
                            <p class="text-xs text-slate-300 mt-1">JPG, PNG, WEBP — Maks 2MB</p>
                        </div>
                    </div>

                    {{-- Input file tersembunyi --}}
                    <input
                        type="file"
                        name="foto"
                        x-ref="fotoInput"
                        class="hidden"
                        accept="image/*"
                        @change="handleFileSelect($event)"
                    />
                </div>

                {{-- Toggle Status Tersedia --}}
                <div class="flex items-center justify-between py-2">
                    <div>
                        <p class="text-sm font-medium text-slate-700">Status Tersedia</p>
                        <p class="text-xs text-slate-400">Produk tampil di katalog user</p>
                    </div>
                    {{-- 
                        Toggle switch dengan Alpine
                        @click → toggle nilai boolean
                        :class → class berubah sesuai kondisi
                    --}}
                    <button
                        type="button"
                        @click="form.is_available = !form.is_available"
                        :class="form.is_available ? 'bg-green-500' : 'bg-slate-200'"
                        class="relative w-12 h-6 rounded-full transition-colors duration-200"
                    >
                        <span
                            :class="form.is_available ? 'translate-x-6' : 'translate-x-1'"
                            class="absolute top-1 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                        ></span>
                    </button>
                    {{-- Hidden input untuk mengirim nilai ke server --}}
                    <input type="hidden" name="is_available" :value="form.is_available ? '1' : '0'">
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-3 pt-2">
                    <button
                        type="button"
                        @click="tutupModal()"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100
                               rounded-xl hover:bg-slate-200 transition"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white
                               bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl
                               hover:from-blue-700 hover:to-blue-800 transition
                               disabled:opacity-50 disabled:cursor-not-allowed
                               flex items-center justify-center gap-2"
                    >
                        {{-- Loading spinner --}}
                        <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="loading ? 'Menyimpan...' : (isEdit ? 'Update Produk' : 'Simpan Produk')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div
        x-show="modalHapusOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        @click.self="modalHapusOpen = false"
    >
        <div
            x-show="modalHapusOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center"
            @click.stop
        >
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Hapus Produk?</h3>
            <p class="text-sm text-slate-500 mb-6">
                Kamu akan menghapus <span class="font-semibold text-slate-700" x-text="'&quot;' + produkHapus?.nama_produk + '&quot;'"></span>.
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-3">
                <button
                    @click="modalHapusOpen = false"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition"
                >
                    Batal
                </button>
                <form :action="'/admin/products/' + produkHapus?.id" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-500 rounded-xl hover:bg-red-600 transition"
                    >
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
/*
 * BELAJAR ALPINE.JS — Component produkManager
 * 
 * Ini adalah "komponen" Alpine yang mengelola semua state dan logika
 * di halaman produk. Konsepnya mirip dengan component di Vue/React.
 *
 * x-data="produkManager()" → memanggil fungsi ini dan hasilnya
 * menjadi reactive state untuk seluruh elemen di dalam div tersebut.
 */
function produkManager() {
    return {

        // ============================================================
        // STATE (Data reaktif)
        // Setiap perubahan nilai ini otomatis update tampilan
        // ============================================================
        
        // Semua produk dari server (akan diisi saat init)
        semuaProduk: @json($products),

        // Produk yang sedang ditampilkan (bisa difilter)
        produkTampil: [],

        // Nilai search dan filter
        search: '',
        filterKategori: '',

        // State modal
        modalOpen: false,
        modalHapusOpen: false,
        isEdit: false,
        loading: false,

        // Produk yang sedang diedit / akan dihapus
        produkEdit: null,
        produkHapus: null,

        // Preview upload foto
        previewUrl: null,
        namaFile: '',

        // Error validasi client-side
        errors: {},

        // Form data
        form: {
            nama_produk: '',
            category_id: '',
            harga: '',
            satuan: '',
            stok: 0,
            deskripsi: '',
            is_available: true,
        },

        // ============================================================
        // INIT — Dipanggil otomatis oleh x-init="init()"
        // ============================================================
        init() {
            // Tampilkan semua produk saat pertama load
            this.produkTampil = this.semuaProduk;

            // Cek apakah ada flash message dari server
            // dan tampilkan sebagai toast
            @if(session('success'))
                this.$nextTick(() => {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { pesan: '{{ session('success') }}', type: 'success' }
                    }));
                });
            @endif
        },

        // ============================================================
        // FILTER PRODUK — Live search + filter kategori
        // ============================================================
        filterProduk() {
            /*
             * BELAJAR JS: Array.filter()
             * Membuat array baru yang hanya berisi elemen yang memenuhi kondisi.
             * Setiap elemen di-check satu per satu oleh fungsi callback.
             */
            this.produkTampil = this.semuaProduk.filter(produk => {

                // Cek apakah nama produk mengandung kata yang dicari
                // toLowerCase() → agar pencarian tidak case-sensitive
                // includes() → mengecek apakah string mengandung substring
                const cocokSearch = produk.nama_produk
                    .toLowerCase()
                    .includes(this.search.toLowerCase());

                // Cek filter kategori
                // Jika filterKategori kosong → tampilkan semua kategori
                const cocokKategori = this.filterKategori === ''
                    || produk.category_id == this.filterKategori;

                // Produk tampil hanya jika KEDUA kondisi terpenuhi
                return cocokSearch && cocokKategori;
            });
        },

        // ============================================================
        // BUKA MODAL TAMBAH
        // ============================================================
        bukaModalTambah() {
            this.isEdit   = false;
            this.errors   = {};
            this.previewUrl = null;
            this.namaFile   = '';

            // Reset form ke nilai awal
            this.form = {
                nama_produk: '',
                category_id: '',
                harga: '',
                satuan: '',
                stok: 0,
                deskripsi: '',
                is_available: true,
            };

            this.modalOpen = true;
        },

        // ============================================================
        // BUKA MODAL EDIT — Isi form dengan data produk yang dipilih
        // ============================================================
        bukaModalEdit(produk) {
            this.isEdit     = true;
            this.produkEdit = produk;
            this.errors     = {};

            // Isi form dengan data produk yang ada
            // Spread operator (...) → copy semua property dari objek produk
            this.form = {
                nama_produk:  produk.nama_produk,
                category_id:  produk.category_id,
                harga:        produk.harga,
                satuan:       produk.satuan,
                stok:         produk.stok,
                deskripsi:    produk.deskripsi || '',
                is_available: produk.is_available == 1,
            };

            // Tampilkan foto yang sudah ada
            this.previewUrl = produk.foto ? '/storage/' + produk.foto : null;
            this.namaFile   = produk.foto ? produk.foto.split('/').pop() : '';

            this.modalOpen = true;
        },

        // ============================================================
        // TUTUP MODAL
        // ============================================================
        tutupModal() {
            this.modalOpen = false;
            this.loading   = false;
        },

        // ============================================================
        // HANDLE PILIH FILE — Preview sebelum upload
        // ============================================================
        handleFileSelect(event) {
            /*
             * BELAJAR JS: File API & URL.createObjectURL()
             * 
             * event.target.files → FileList object (array-like)
             * files[0] → file pertama yang dipilih
             * URL.createObjectURL() → membuat URL sementara di memory browser
             * untuk ditampilkan sebagai preview tanpa upload ke server
             */
            const file = event.target.files[0];
            if (!file) return;

            // Validasi tipe file
            if (!file.type.startsWith('image/')) {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { pesan: 'File harus berupa gambar!', type: 'error' }
                }));
                return;
            }

            // Validasi ukuran file (maks 2MB)
            // file.size dalam bytes → 2 * 1024 * 1024 = 2MB
            if (file.size > 2 * 1024 * 1024) {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { pesan: 'Ukuran file maksimal 2MB!', type: 'error' }
                }));
                return;
            }

            // Buat URL preview sementara
            this.previewUrl = URL.createObjectURL(file);
            this.namaFile   = file.name;
        },

        // ============================================================
        // HANDLE DRAG & DROP
        // ============================================================
        handleDrop(event) {
            /*
             * BELAJAR JS: Drag and Drop API
             * event.dataTransfer.files → file yang di-drop user
             * Kita assign ke input[file] agar bisa di-submit bersama form
             */
            const file = event.dataTransfer.files[0];
            if (!file) return;

            // Assign file ke input tersembunyi
            const input    = this.$refs.fotoInput;
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;

            // Trigger event change agar handleFileSelect dipanggil
            input.dispatchEvent(new Event('change'));
        },

        // ============================================================
        // SUBMIT FORM — Validasi client-side sebelum kirim
        // ============================================================
        submitForm(event) {
            /*
             * BELAJAR JS: Form Validation
             * event.preventDefault() → mencegah form submit otomatis
             * Kita validasi dulu, baru submit manual jika valid
             */
            this.errors = {};

            // Validasi sederhana
            if (!this.form.nama_produk.trim()) {
                this.errors.nama_produk = 'Nama produk wajib diisi.';
            }

            // Jika ada error → stop, jangan submit
            if (Object.keys(this.errors).length > 0) {
                event.preventDefault();
                return;
            }

            // Aktifkan loading state
            this.loading = true;

            // Biarkan form submit secara normal ke server
            // (tidak preventDefault → form lanjut submit)
        },

        // ============================================================
        // HAPUS PRODUK — Buka modal konfirmasi dulu
        // ============================================================
        hapusProduk(produk) {
            this.produkHapus    = produk;
            this.modalHapusOpen = true;
        },

        // ============================================================
        // FORMAT RUPIAH — Wrapper untuk dipakai di template
        // ============================================================
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