<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the articles (for API).
     */
    public function index()
    {
        // 1. Eager load relasi 'kategori' untuk menghindari N+1 query problem.
        $artikels = Artikel::with('kategori')->latest()->paginate(9);

        // 2. Ubah data artikel menjadi format yang diinginkan
        $formattedArtikels = $artikels->getCollection()->map(function ($artikel) {
            return [
                'id'             => $artikel->id,
                'penulis'        => $artikel->penulis,
                'judul'          => $artikel->judul,
                'deskripsi'      => $artikel->deskripsi,
                // Menggunakan endpoint universal untuk gambar
                'gambar'         => $artikel->gambar ? url('api/images/' . basename($artikel->gambar)) : null,
                'tanggal_terbit' => $artikel->tanggal_terbit,
                'created_at'     => $artikel->created_at->format('Y-m-d H:i:s'),
                'updated_at'     => $artikel->updated_at->format('Y-m-d H:i:s'),
                // Mengambil nama kategori dari relasi yang sudah dimuat
                'kategori_artikel'  => $artikel->kategori ? $artikel->kategori->name : null,
            ];
        });

        // 3. Buat respons JSON manual dengan format pagination yang diminta
        return response()->json([
            'success' => true,
            'message' => 'List Data Artikel',
            'data'    => $formattedArtikels,
            // Sertakan metadata pagination secara manual dengan format yang Anda inginkan
            'pagination' => [
                'total'        => $artikels->total(),
                'count'        => $artikels->count(), // Jumlah item di halaman saat ini
                'per_page'     => $artikels->perPage(),
                'current_page' => $artikels->currentPage(),
                'total_pages'  => $artikels->lastPage(),
            ]
        ]);
    }

    /**
     * Display a listing of the articles (for Blade view).
     */
    public function listView()
    {
        $artikels = Artikel::latest()->paginate(10);
        return view('artikels.index', compact('artikels'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $kategoris = KategoriArtikel::all();
        return view('artikels.create', compact('kategoris'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'kategori_id' => 'required|exists:kategori_artikels,id',
                'penulis' => 'required|string|max:255',
                'judul' => 'required|string|max:255|unique:artikels,judul',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tanggal_terbit' => 'required|date',
            ]);

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                if (!Storage::exists('public/artikels')) {
                    Storage::makeDirectory('public/artikels');
                }
                $gambarPath = $request->file('gambar')->store('public/artikels');
                $gambarPath = str_replace('public/', 'storage/', $gambarPath);
            }

            Artikel::create([
                'kategori_id' => $validatedData['kategori_id'],
                'penulis' => $validatedData['penulis'],
                'judul' => $validatedData['judul'],
                'deskripsi' => $validatedData['deskripsi'],
                'gambar' => $gambarPath,
                'tanggal_terbit' => $validatedData['tanggal_terbit'],
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Artikel berhasil ditambahkan!'
                ], 201);
            }

            return redirect()->route('artikels.list')->with('success', 'Artikel berhasil ditambahkan!');
        } catch (ValidationException $e) {
            Log::error('Validation Error (Store Artikel): ' . $e->getMessage(), ['errors' => $e->errors(), 'request' => $request->all()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Server Error (Store Artikel): ' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan artikel. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Artikel $artikel)
    {
        $kategoris = KategoriArtikel::all();
        return view('artikels.edit', compact('artikel', 'kategoris'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Artikel $artikel)
    {
        try {
            $validatedData = $request->validate([
                'kategori_id' => 'required|exists:kategori_artikels,id',
                'penulis' => 'required|string|max:255',
                'judul' => 'required|string|max:255|unique:artikels,judul,' . $artikel->id,
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tanggal_terbit' => 'required|date',
            ]);

            $gambarPath = $artikel->gambar;
            if ($request->hasFile('gambar')) {
                if ($artikel->gambar) {
                    $oldImagePath = str_replace('storage/', 'public/', $artikel->gambar);
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }
                $gambarPath = $request->file('gambar')->store('public/artikels');
                $gambarPath = str_replace('public/', 'storage/', $gambarPath);
            } elseif ($request->boolean('clear_gambar')) {
                if ($artikel->gambar) {
                    $oldImagePath = str_replace('storage/', 'public/', $artikel->gambar);
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }
                $gambarPath = null;
            }

            $artikel->update([
                'kategori_id' => $validatedData['kategori_id'],
                'penulis' => $validatedData['penulis'],
                'judul' => $validatedData['judul'],
                'deskripsi' => $validatedData['deskripsi'],
                'gambar' => $gambarPath,
                'tanggal_terbit' => $validatedData['tanggal_terbit'],
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Artikel berhasil diperbarui!',
                ]);
            }

            return redirect()->route('artikels.list')->with('success', 'Artikel berhasil diperbarui!');
        } catch (ValidationException $e) {
            Log::error('Validation Error (Update Artikel): ' . $e->getMessage(), ['errors' => $e->errors(), 'request' => $request->all()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Server Error (Update Artikel): ' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request' => $request->all()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui artikel. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Request $request, Artikel $artikel)
    {
        try {
            if ($artikel->gambar) {
                $imagePath = str_replace('storage/', 'public/', $artikel->gambar);
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }

            $artikel->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Artikel berhasil dihapus!'
                ]);
            }

            return redirect()->route('artikels.list')->with('success', 'Artikel berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Server Error (Destroy Artikel): ' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'artikel_id' => $artikel->id]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus artikel. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $articleDetail = Artikel::find($id);

        if (!$articleDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan',
                'data' => null
            ]);
        }

        $formattedArtikel = [
            'id'             => $articleDetail->id,
            'penulis'        => $articleDetail->penulis,
            'judul'          => $articleDetail->judul,
            'deskripsi'      => $articleDetail->deskripsi,
            'gambar'         => $articleDetail->gambar ? url('api/images/' . basename($articleDetail->gambar)) : null,
            'tanggal_terbit' => $articleDetail->tanggal_terbit,
            'created_at'     => $articleDetail->created_at->format('Y-m-d H:i:s'),
            'updated_at'     => $articleDetail->updated_at->format('Y-m-d H:i:s'),
            'kategori_artikel' => $articleDetail->kategori ? $articleDetail->kategori->name : null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail Artikel',
            'data' => $formattedArtikel
        ]);
    }
}
