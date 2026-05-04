<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Beras & Serealia',   'deskripsi' => 'Beras, jagung, dan serealia lainnya'],
            ['nama_kategori' => 'Minyak & Lemak',      'deskripsi' => 'Minyak goreng, margarin, dll'],
            ['nama_kategori' => 'Protein Hewani',      'deskripsi' => 'Daging, telur, ikan'],
            ['nama_kategori' => 'Sayur & Buah',        'deskripsi' => 'Aneka sayuran dan buah segar'],
            ['nama_kategori' => 'Bumbu & Rempah',      'deskripsi' => 'Bawang, cabai, rempah-rempah'],
            ['nama_kategori' => 'Produk Olahan',       'deskripsi' => 'Tahu, tempe, dan produk olahan'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'nama_kategori' => $cat['nama_kategori'],
                'slug'          => Str::slug($cat['nama_kategori']),
                'deskripsi'     => $cat['deskripsi'],
            ]);
        }
    }
}