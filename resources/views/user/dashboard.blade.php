@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di Portal SPPG')

@section('content')

{{-- Welcome Banner --}}
<div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-2xl p-6 mb-6 text-white shadow">
    <p class="text-blue-100 text-sm mb-1">Selamat datang,</p>
    <h2 class="text-xl font-bold mb-1">{{ auth()->user()->name }}</h2>
    @if(auth()->user()->sppgProfile)
        <p class="text-blue-100 text-sm">{{ auth()->user()->sppgProfile->nama_sppg }} · {{ auth()->user()->sppgProfile->kode_sppg }}</p>
    @endif
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <a href="{{ route('user.catalog.index') }}"
        class="group bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-700 group-hover:text-blue-700 transition">Katalog Bahan Baku</p>
                <p class="text-sm text-slate-400">Lihat produk & harga terbaru</p>
            </div>
        </div>
    </a>

    <a href="{{ route('user.menus.create') }}"
        class="group bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:border-green-200 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center shadow">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-slate-700 group-hover:text-green-700 transition">Upload Menu Mingguan</p>
                <p class="text-sm text-slate-400">Unggah foto menu minggu ini</p>
            </div>
        </div>
    </a>
</div>

{{-- Produk Terbaru --}}
@if($produkTerbaru->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-slate-700">Produk Terbaru</h3>
        <a href="{{ route('user.catalog.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($produkTerbaru as $produk)
        <div class="border border-slate-100 rounded-xl p-3 hover:border-blue-200 transition">
            <p class="text-sm font-medium text-slate-700 truncate">{{ $produk->nama_produk }}</p>
            <p class="text-xs text-green-600 font-semibold mt-1">{{ $produk->harga_formatted }}</p>
            <p class="text-xs text-slate-400">per {{ $produk->satuan }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection