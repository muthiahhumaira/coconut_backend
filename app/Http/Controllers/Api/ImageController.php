<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response; // Import Response class
use Illuminate\Support\Facades\Log; // Untuk logging

class ImageController extends Controller
{
    /**
     * Array dari direktori tempat gambar mungkin disimpan.
     * Urutan penting: direktori yang lebih spesifik atau lebih sering diakses
     * bisa diletakkan di depan.
     * @var array
     */
    protected $imageDirectories = [
        'public/products',
        'public/artikels',
        // Tambahkan direktori lain di sini jika ada, misalnya 'public/users', 'public/categories'
    ];

    /**
     * Display the specified image from various modules using a single endpoint.
     *
     * @param  string  $filename The filename of the image
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show($filename)
    {
        $foundPath = null;

        // Iterasi melalui setiap direktori untuk menemukan file
        foreach ($this->imageDirectories as $dir) {
            $fullPath = $dir . '/' . $filename;
            if (Storage::exists($fullPath)) {
                $foundPath = $fullPath;
                break; // Hentikan pencarian jika sudah ditemukan
            }
        }

        // Jika gambar tidak ditemukan di direktori manapun
        if (is_null($foundPath)) {
            Log::warning('Image not found in any specified directory: ' . $filename);
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan.'
            ], 404); // Not Found
        }

        // Ambil konten file
        try {
            $file = Storage::get($foundPath);
            // Tentukan tipe MIME dari file
            $type = Storage::mimeType($foundPath);

            // Kembalikan gambar sebagai respons
            return response($file, 200)->header('Content-Type', $type);
        } catch (\Exception $e) {
            Log::error('Error serving image ' . $filename . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat gambar.'
            ], 500); // Internal Server Error
        }
    }
}