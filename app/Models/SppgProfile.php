<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppgProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_sppg',
        'kode_sppg',
        'alamat',
        'kelurahan',
        'kecamatan',
        'no_telepon',
        'nama_penanggung_jawab',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}