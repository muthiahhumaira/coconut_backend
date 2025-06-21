<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
        'gambar',
        'detail_spesifikasi',
        'spesifikasi', // Akan disimpan sebagai JSON
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'spesifikasi' => 'array', // Ini akan otomatis mengubah kolom 'spesifikasi' menjadi array/objek PHP
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products'; // Pastikan nama tabel di database adalah 'products'
}
