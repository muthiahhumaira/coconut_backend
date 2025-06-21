<?php

namespace App\Http\Controllers\Api; // Sesuaikan namespace jika Anda menempatkannya di sub-folder
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource; // Sesuaikan path jika Anda menggunakannya
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response; // Tambahkan ini

class ProdukController extends Controller
{
    /**
     * Display a listing of the products (for API).
     */
    public function index()
    {
        $products = Product::orderBy('id', 'asc')->paginate(5);


        $formattedProducts = $products->getCollection()->map(function ($product) {
            return [
                'id' => $product->id,
                'nama' => $product->nama,
                'deskripsi' => $product->deskripsi,
                'spesifikasi' => $product->spesifikasi,
                'detail_spesifikasi' => $product->detail_spesifikasi,
                'gambar' => $product->gambar ? url('/api/images/' . basename($product->gambar)) : null
            ];
        });
        // return new ProductResource(true, 'List Data Kategori Produk', $products);
        return response()->json([
            'success' => true,
            'message' => 'List Data Produk',
            'data'    => $formattedProducts,
            'pagination' => [
                'total'        => $products->total(),
                'count'        => $products->count(),
                'per_page'     => $products->perPage(),
                'current_page' => $products->currentPage(),
                'total_pages'  => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Display a listing of the products (for Blade view).
     */
    public function listView()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|unique:products,nama',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'spesifikasi' => 'nullable|array',
                'detail_spesifikasi' => 'nullable|string',
                'spesifikasi.Detail' => 'nullable|string',
                'spesifikasi.Color' => 'nullable|string',
                'spesifikasi.Odour' => 'nullable|string',
                'spesifikasi.Solubility in Water' => 'nullable|string',
                'spesifikasi.Moisture' => 'nullable|string',
                'spesifikasi.Iodine Value' => 'nullable|string',
                'spesifikasi.Saponification Value' => 'nullable|string',
                'spesifikasi.Free Fatty Acid' => 'nullable|string',
                'spesifikasi.Unsaponifiable Materia' => 'nullable|string',
            ]);

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('public/products');
                $gambarPath = str_replace('public/', 'storage/', $gambarPath);
            }

            $spesifikasi = [];
            if ($request->has('spesifikasi')) {
                foreach ($request->spesifikasi as $key => $value) {
                    if (in_array($key, ['Detail', 'Color', 'Odour', 'Solubility in Water', 'Moisture', 'Iodine Value', 'Saponification Value', 'Free Fatty Acid', 'Unsaponifiable Materia'])) {
                        $spesifikasi[$key] = $value;
                    }
                }
            }

            Product::create([
                'nama' => $validatedData['nama'],
                'deskripsi' => $validatedData['deskripsi'],
                'gambar' => $gambarPath,
                'spesifikasi' => $spesifikasi,
                'detail_spesifikasi' => $validatedData['detail_spesifikasi']
            ]);

            // Jika ini adalah permintaan AJAX, kembalikan JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan!'
                ], 201); // 201 Created
            }

            return redirect()->route('products.list')->with('success', 'Produk berhasil ditambahkan!');
        } catch (ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422); // 422 Unprocessable Entity
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
                ], 500); // 500 Internal Server Error
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan produk: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|unique:products,nama,' . $product->id,
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'spesifikasi' => 'nullable|array',
                'detail_spesifikasi' => 'nullable|string',
                'spesifikasi.Detail' => 'nullable|string',
                'spesifikasi.Color' => 'nullable|string',
                'spesifikasi.Odour' => 'nullable|string',
                'spesifikasi.Solubility in Water' => 'nullable|string',
                'spesifikasi.Moisture' => 'nullable|string',
                'spesifikasi.Iodine Value' => 'nullable|string',
                'spesifikasi.Saponification Value' => 'nullable|string',
                'spesifikasi.Free Fatty Acid' => 'nullable|string',
                'spesifikasi.Unsaponifiable Materia' => 'nullable|string',
            ]);

            $gambarPath = $product->gambar;
            if ($request->hasFile('gambar')) {
                if ($product->gambar && Storage::exists(str_replace('storage/', 'public/', $product->gambar))) {
                    Storage::delete(str_replace('storage/', 'public/', $product->gambar));
                }
                $gambarPath = $request->file('gambar')->store('public/products');
                $gambarPath = str_replace('public/', 'storage/', $gambarPath);
            } elseif ($request->boolean('clear_gambar')) { // Menggunakan boolean() untuk input checkbox
                if ($product->gambar && Storage::exists(str_replace('storage/', 'public/', $product->gambar))) {
                    Storage::delete(str_replace('storage/', 'public/', $product->gambar));
                }
                $gambarPath = null;
            }

            $spesifikasi = [];
            if ($request->has('spesifikasi')) {
                foreach ($request->spesifikasi as $key => $value) {
                    if (in_array($key, ['Detail', 'Color', 'Odour', 'Solubility in Water', 'Moisture', 'Iodine Value', 'Saponification Value', 'Free Fatty Acid', 'Unsaponifiable Materia'])) {
                        $spesifikasi[$key] = $value;
                    }
                }
            } else {
                $spesifikasi = [];
            }

            $product->update([
                'nama' => $validatedData['nama'],
                'deskripsi' => $validatedData['deskripsi'],
                'gambar' => $gambarPath,
                'detail_spesifikasi' => $validatedData['detail_spesifikasi'],
                'spesifikasi' => $spesifikasi,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil diperbarui!',
                    // Opsional: Kirim data produk yang diperbarui jika Anda ingin update DOM tanpa reload
                    // 'product' => new ProductResource(true, '', $product) // Contoh jika pakai resource
                ]);
            }

            return redirect()->route('products.list')->with('success', 'Produk berhasil diperbarui!');
        } catch (ValidationException $e) {
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
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request, Product $product)
    {
        try {
            if ($product->gambar && Storage::exists(str_replace('storage/', 'public/', $product->gambar))) {
                Storage::delete(str_replace('storage/', 'public/', $product->gambar));
            }

            $product->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus!'
                ]);
            }

            return redirect()->route('products.list')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product image.
     */
    public function showImage($filename)
    {
        $path = 'public/products/' . $filename;

        if (!Storage::exists($path)) {
            abort(404); // File not found
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    }


    public function show($id)
    {
        $productDetail = Product::find($id);

        if (!$productDetail) {
            return response()->json([
                'success' => false,
                'message' => "Produk tidak ditemukan",
                'data' => $productDetail
            ]);
        }

        $formattedProducts = [
            'id' => $productDetail->id,
            'nama' => $productDetail->nama,
            'deskripsi' => $productDetail->deskripsi,
            'spesifikasi' => $productDetail->spesifikasi,
            'detail_spesifikasi' => $productDetail->detail_spesifikasi,
            'gambar' => $productDetail->gambar ? url('/api/images/' . basename($productDetail->gambar)) : null
        ];

        return response()->json([
            'succes' => true,
            'message' => 'Detail produk',
            'data' => $formattedProducts
        ]);
    }
}
