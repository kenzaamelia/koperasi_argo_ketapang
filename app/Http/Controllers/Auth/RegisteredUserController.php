<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SppgProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'nama_sppg'     => ['required', 'string', 'max:255'],
            'kode_sppg'     => ['required', 'string', 'max:50', 'unique:sppg_profiles,kode_sppg'],
            'no_telepon'    => ['required', 'string', 'max:20'],
            'alamat'        => ['required', 'string'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'nama_sppg.required'  => 'Nama SPPG wajib diisi.',
            'kode_sppg.required'  => 'Kode SPPG wajib diisi.',
            'kode_sppg.unique'    => 'Kode SPPG sudah digunakan.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required'     => 'Alamat wajib diisi.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        // Buat user dengan role 'user' (SPPG)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Otomatis buat profil SPPG
        SppgProfile::create([
            'user_id'    => $user->id,
            'nama_sppg'  => $request->nama_sppg,
            'kode_sppg'  => strtoupper($request->kode_sppg),
            'no_telepon' => $request->no_telepon,
            'alamat'     => $request->alamat,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Selamat datang! Akun SPPG kamu berhasil dibuat.');
    }
}