<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriArtikelController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ArtikelController;

Route::get('/', [ProdukController::class, 'listView']);

Route::prefix('kategori-artikels')->name('kategori-artikels.')->group(function () {
    // Rute yang sudah ada
    Route::get('/', [KategoriArtikelController::class, 'listView'])->name('list');
    Route::get('/create', [KategoriArtikelController::class, 'create'])->name('create.form');
    Route::post('/', [KategoriArtikelController::class, 'store'])->name('store');

    // Rute yang perlu ditambahkan:
    // Menampilkan form edit
    Route::get('/{kategoriArtikel}/edit', [KategoriArtikelController::class, 'edit'])->name('edit'); // Ini rute untuk tampilan form edit, meskipun kita menggunakan modal. Diperlukan jika Anda ingin bisa mengaksesnya langsung via URL.

    // Mengupdate data kategori (menggunakan metode PUT)
    Route::put('/{kategoriArtikel}', [KategoriArtikelController::class, 'update'])->name('update');

    // Menghapus data kategori (menggunakan metode DELETE)
    Route::delete('/{kategoriArtikel}', [KategoriArtikelController::class, 'destroy'])->name('destroy');
});

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProdukController::class, 'listView'])->name('list');
    Route::get('/create', [ProdukController::class, 'create'])->name('create');
    Route::post('/', [ProdukController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [ProdukController::class, 'edit'])->name('edit'); // Ini untuk form edit biasa
    Route::put('/{product}', [ProdukController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProdukController::class, 'destroy'])->name('destroy');
});
Route::prefix('artikels')->name('artikels.')->group(function () {
    Route::get('/', [ArtikelController::class, 'listView'])->name('list');
    Route::get('/create', [ArtikelController::class, 'create'])->name('create');
    Route::post('/', [ArtikelController::class, 'store'])->name('store');
    Route::get('/{artikel}/edit', [ArtikelController::class, 'edit'])->name('edit');
    Route::put('/{artikel}', [ArtikelController::class, 'update'])->name('update');
    Route::delete('/{artikel}', [ArtikelController::class, 'destroy'])->name('destroy');
    // Route untuk menampilkan gambar artikel dari storage (jika ingin diakses langsung dari web)
    Route::get('/image/{filename}', [ArtikelController::class, 'showImage'])->name('image');
});
Route::resource('/kontak', ContactController::class);
