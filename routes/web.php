<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\KategoriArtikelController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ArtikelController;

// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth bawaan Laravel
Auth::routes();

// Group semua route setelah login
Route::middleware(['auth'])->group(function () {

    // Redirect default setelah login
    Route::get('/home', function () {
        return redirect()->route('kategori-artikels.list');
    })->name('home');

    // Kategori Artikel
    Route::prefix('kategori-artikels')->name('kategori-artikels.')->group(function () {
        Route::get('/', [KategoriArtikelController::class, 'listView'])->name('list');
        Route::get('/create', [KategoriArtikelController::class, 'create'])->name('create.form');
        Route::post('/', [KategoriArtikelController::class, 'store'])->name('store');
        Route::get('/{kategoriArtikel}/edit', [KategoriArtikelController::class, 'edit'])->name('edit');
        Route::put('/{kategoriArtikel}', [KategoriArtikelController::class, 'update'])->name('update');
        Route::delete('/{kategoriArtikel}', [KategoriArtikelController::class, 'destroy'])->name('destroy');
    });

    // Produk
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProdukController::class, 'listView'])->name('list');
        Route::get('/create', [ProdukController::class, 'create'])->name('create');
        Route::post('/', [ProdukController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProdukController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProdukController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProdukController::class, 'destroy'])->name('destroy');
    });

    // Artikel
    Route::prefix('artikels')->name('artikels.')->group(function () {
        Route::get('/', [ArtikelController::class, 'listView'])->name('list');
        Route::get('/create', [ArtikelController::class, 'create'])->name('create');
        Route::post('/', [ArtikelController::class, 'store'])->name('store');
        Route::get('/{artikel}/edit', [ArtikelController::class, 'edit'])->name('edit');
        Route::put('/{artikel}', [ArtikelController::class, 'update'])->name('update');
        Route::delete('/{artikel}', [ArtikelController::class, 'destroy'])->name('destroy');
        Route::get('/image/{filename}', [ArtikelController::class, 'showImage'])->name('image');
    });

    // Kontak
    Route::resource('/kontak', ContactController::class);
    Route::post('/kontak/{contact}/approve', [ContactController::class, 'approve'])->name('kontak.approve');
});