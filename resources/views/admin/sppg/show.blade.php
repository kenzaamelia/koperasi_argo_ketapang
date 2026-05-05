@extends('layouts.admin')

@section('title', $sppg->sppgProfile->nama_sppg ?? $sppg->name)
@section('page-title', $sppg->sppgProfile->nama_sppg ?? $sppg->name)
@section('page-subtitle', 'Detail informasi SPPG')

@section('content')

{{-- Back Button --}}
<a href="{{ route('admin.sppg.index') }}"
    class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    Kembali ke Master SPPG
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Kolom Kiri: Info SPPG --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Card Profil --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 p-6 text-center">
                <div class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center mx-auto mb-3">
                    @if($sppg->sppgProfile?->foto_profil)
                        <img src="{{ Storage::url($sppg->sppgProfile->foto_profil) }}"
                            class="w-full h-full object-cover rounded-2xl" />
                    @else
                        <span class="text-white font-bold text-3xl">
                            {{ strtoupper(substr($sppg->sppgProfile->nama_sppg ?? $sppg->name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <h2 class="text-white font-bold text-lg">{{ $sppg->sppgProfile->nama_sppg ?? $sppg->name }}</h2>
                <span class="inline-block mt-1 text-xs bg-white/20 text-white px-3 py-1 rounded-full">
                    {{ $sppg->sppgProfile->kode_sppg ?? '-' }}
                </span>
            </div>

            {{-- Info Detail --}}
            <div class="p-5 space-y-3">
                @php
                    $profile = $sppg->sppgProfile;
                    $infos = [
                        ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Penanggung Jawab', 'value' => $profile?->nama_penanggung_jawab],
                        ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Email', 'value' => $sppg->email],
                        ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => 'Telepon', 'value' => $profile?->no_telepon],
                        ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'label' => 'Alamat', 'value' => $profile?->alamat],
                        ['icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'label' => 'Kecamatan', 'value' => $profile?->kecamatan],
                    ];
                @endphp

                @foreach($infos as $info)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">{{ $info['label'] }}</p>
                        <p class="text-sm text-slate-700 font-medium">{{ $info['value'] ?? '-' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Statistik --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h3 class="font-semibold text-slate-700 mb-3">Statistik</h3>
            <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-bold text-blue-700">{{ $totalMenu }}</p>
                <p class="text-sm text-slate-500 mt-1">Total menu diupload</p>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Foto Menu --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5"
            x-data="{ lightboxOpen: false, src: '', caption: '' }">

            <h3 class="font-semibold text-slate-700 mb-4">
                Foto Menu Mingguan
                <span class="text-sm font-normal text-slate-400 ml-1">({{ $totalMenu }} upload)</span>
            </h3>

            @if($menuTerbaru->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($menuTerbaru as $menu)
                <div
                    class="relative group cursor-pointer rounded-xl overflow-hidden aspect-square bg-slate-100"
                    @click="src = '{{ Storage::url($menu->foto) }}'; caption = '{{ $menu->judul ?? 'Menu Mingguan' }}'; lightboxOpen = true"
                >
                    <img
                        src="{{ Storage::url($menu->foto) }}"
                        alt="{{ $menu->judul }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                    />
                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent
                                opacity-0 group-hover:opacity-100 transition duration-200 flex items-end p-3">
                        <div>
                            <p class="text-white text-xs font-medium truncate">{{ $menu->judul ?? 'Menu Mingguan' }}</p>
                            <p class="text-white/70 text-xs">
                                {{ $menu->tanggal_mulai->format('d M') }} — {{ $menu->tanggal_selesai->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-12 text-center border-2 border-dashed border-slate-200 rounded-xl">
                <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-400 text-sm">SPPG ini belum mengupload menu</p>
            </div>
            @endif

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
                        class="absolute -top-12 right-0 text-white/70 hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <img :src="src" :alt="caption"
                        class="w-full max-h-[80vh] object-contain rounded-xl"/>
                    <p class="text-white/70 text-sm text-center mt-3" x-text="caption"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection