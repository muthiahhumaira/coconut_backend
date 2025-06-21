<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'penulis',
        'judul',
        'deskripsi',
        'gambar',
        'tanggal_terbit',
    ];

    // Relasi ke KategoriArtikel
    public function kategori()
    {
        return $this->belongsTo(KategoriArtikel::class, 'kategori_id');
    }
}