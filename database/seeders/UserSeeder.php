<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SppgProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Koperasi
        User::create([
            'name'     => 'Admin Koperasi Argo Ketapang',
            'email'    => 'admin@koperasi.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // User SPPG contoh
        $user = User::create([
            'name'     => 'SPPG Contoh',
            'email'    => 'sppg@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        SppgProfile::create([
            'user_id'               => $user->id,
            'nama_sppg'             => 'SPPG Mojoroto',
            'kode_sppg'             => 'SPPG-001',
            'alamat'                => 'Jl. Mojoroto No. 1, Kediri',
            'kelurahan'             => 'Mojoroto',
            'kecamatan'             => 'Mojoroto',
            'no_telepon'            => '081234567890',
            'nama_penanggung_jawab' => 'Budi Santoso',
        ]);
    }
}