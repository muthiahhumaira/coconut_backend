<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KategoriArtikelController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\ImageController; // Import ImageController baru
use App\Http\Controllers\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/kategori-artikels', KategoriArtikelController::class);
Route::apiResource('/produks', ProdukController::class);
Route::apiResource('/artikels', ArtikelController::class);
Route::get('/images/{filename}', [ImageController::class, 'show']);
Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
