@extends('layouts.user')

@section('title', 'Profil SPPG')
@section('page-title', 'Profil SPPG')
@section('page-subtitle', 'Lengkapi data profil SPPG kamu')

@section('content')

<div class="max-w-2xl mx-auto">
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 py-5">
        <h2 class="text-white font-semibold text-lg">Data Profil SPPG</h2>
        <p class="text-blue-100 text-sm">Informasi ini akan tampil di panel admin koperasi</p>
    </div>

    <form action="{{ route('user.profile.update') }}" method="POST"
        enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf
        @method('PUT')

        @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama SPPG <span class="text-red-500">*</span></label>
                <input type="text" name="nama_sppg" value="{{ old('nama_sppg', $profile?->nama_sppg) }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kode SPPG <span class="text-red-500">*</span></label>
                <input type="text" name="kode_sppg" value="{{ old('kode_sppg', $profile?->kode_sppg) }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat <span class="text-red-500">*</span></label>
            <textarea name="alamat" rows="2"
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                required>{{ old('alamat', $profile?->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kelurahan</label>
                <input type="text" name="kelurahan" value="{{ old('kelurahan', $profile?->kelurahan) }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan', $profile?->kecamatan) }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $profile?->no_telepon) }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Penanggung Jawab</label>
                <input type="text" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab', $profile?->nama_penanggung_jawab) }}"
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium
                       text-white bg-gradient-to-r from-blue-600 to-green-600 rounded-xl
                       hover:opacity-90 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Profil
            </button>
        </div>
    </form>
</div>
</div>

@endsection