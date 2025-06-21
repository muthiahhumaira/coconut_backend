<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriArtikel;
use App\Http\Resources\KategoriArtikelResource;

class KategoriArtikelController extends Controller
{
    public function index()
    {
        $kategoriArtikels = KategoriArtikel::latest()->paginate(5);
        return new KategoriArtikelResource(true, 'List Data Kategori Artikel', $kategoriArtikels);
    }

    public function listView()
    {
        $kategoriArtikels = KategoriArtikel::latest()->paginate(10);
        return view('kategori_artikels.index', compact('kategoriArtikels'));
    }

    public function create()
    {
        return view('kategori_artikels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kategori_artikels,name',
        ]);

        KategoriArtikel::create([
            'name' => $request->name,
        ]);

        return redirect()->route('kategori-artikels.list')->with('success', 'Kategori artikel berhasil ditambahkan!');
    }

    public function edit(KategoriArtikel $kategoriArtikel)
    {
        return view('kategori_artikels.edit', compact('kategoriArtikel'));
    }

    public function update(Request $request, KategoriArtikel $kategoriArtikel)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:kategori_artikels,name,' . $kategoriArtikel->id,
            ]);

            $kategoriArtikel->update([
                'name' => $request->name,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori artikel berhasil diperbarui!'
                ]);
            }

            return redirect()->route('kategori-artikels.list')->with('success', 'Kategori artikel berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function destroy(Request $request, KategoriArtikel $kategoriArtikel) // Perubahan ada di sini!
    {
        try {
            $kategoriArtikel->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori artikel berhasil dihapus!'
                ]);
            }

            return redirect()->route('kategori-artikels.list')->with('success', 'Kategori artikel berhasil dihapus!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }
}