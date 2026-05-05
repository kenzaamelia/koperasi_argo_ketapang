@extends('layouts.admin')

@section('title', 'Master SPPG')
@section('page-title', 'Master SPPG')
@section('page-subtitle', 'Daftar seluruh SPPG yang terdaftar')

@section('content')

<div x-data="sppgManager()">

    {{-- Search --}}
    <div class="flex items-center gap-3 mb-5">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input
                type="text"
                placeholder="Cari nama atau kode SPPG..."
                x-model="search"
                @input="filter()"
                class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-white
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
        </div>
        <span class="text-sm text-slate-500">
            Total: <span class="font-semibold text-slate-700">{{ $sppgList->count() }}</span> SPPG
        </span>
    </div>

    {{-- Grid SPPG --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="sppg in sppgTampil" :key="sppg.id">
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5
                       hover:shadow-md hover:border-blue-200 transition cursor-pointer group"
                @click="bukaDetail(sppg.id)"
            >
                {{-- Avatar + Info --}}
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-green-500
                                flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        <span x-text="sppg.sppg_profile?.nama_sppg?.charAt(0) || 'S'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate group-hover:text-blue-700 transition"
                            x-text="sppg.sppg_profile?.nama_sppg || sppg.name">
                        </h3>
                        <span class="inline-block text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full mt-1"
                            x-text="sppg.sppg_profile?.kode_sppg || '-'">
                        </span>
                    </div>
                </div>

                {{-- Detail Info --}}
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span class="truncate" x-text="sppg.sppg_profile?.alamat || 'Belum diisi'"></span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span x-text="sppg.sppg_profile?.no_telepon || 'Belum diisi'"></span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400" x-text="'PJ: ' + (sppg.sppg_profile?.nama_penanggung_jawab || '-')"></span>
                    <span class="text-xs text-blue-600 font-medium group-hover:underline">Lihat detail →</span>
                </div>
            </div>
        </template>

        {{-- Empty State --}}
        <div x-show="sppgTampil.length === 0" class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-100">
            <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-slate-400">Tidak ada SPPG ditemukan</p>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function sppgManager() {
    return {
        semuaSppg:  @json($sppgList),
        sppgTampil: [],
        search: '',

        init() {
            this.sppgTampil = this.semuaSppg;
        },

        filter() {
            const q = this.search.toLowerCase();
            this.sppgTampil = this.semuaSppg.filter(s => {
                const nama = (s.sppg_profile?.nama_sppg || s.name).toLowerCase();
                const kode = (s.sppg_profile?.kode_sppg || '').toLowerCase();
                return nama.includes(q) || kode.includes(q);
            });
        },

        bukaDetail(id) {
            /*
             * BELAJAR JS: window.location.href
             * Cara paling sederhana untuk redirect/navigasi ke halaman lain via JS.
             * Sama seperti user klik link, tapi dipicu dari JavaScript.
             */
            window.location.href = '/admin/sppg/' + id;
        },
    }
}
</script>
@endpush