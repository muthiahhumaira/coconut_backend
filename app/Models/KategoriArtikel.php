<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriArtikel extends Model
{
    protected $fillable = [
        'name',
    ];

    // Pastikan ini ada dan benar sesuai nama tabel di database Anda
    protected $table = 'kategori_artikels';

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class, 'kategori_id', 'id');
    }
}