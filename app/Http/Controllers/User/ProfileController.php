<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SppgProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->sppgProfile;
        return view('user.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_sppg'             => 'required|string|max:255',
            'kode_sppg'             => 'required|string|max:50',
            'alamat'                => 'required|string',
            'kelurahan'             => 'nullable|string|max:100',
            'kecamatan'             => 'nullable|string|max:100',
            'no_telepon'            => 'nullable|string|max:20',
            'nama_penanggung_jawab' => 'nullable|string|max:255',
            'foto_profil'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto_profil')) {
            $profile = auth()->user()->sppgProfile;
            if ($profile?->foto_profil) {
                \Storage::disk('public')->delete($profile->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')
                ->store('profiles', 'public');
        }

        SppgProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return redirect()->route('user.profile.edit')
            ->with('success', 'Profil SPPG berhasil diperbarui!');
    }
}