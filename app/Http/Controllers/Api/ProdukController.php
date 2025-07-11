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
                'spesifikasi.Ingredients' => 'nullable|string',
                'spesifikasi.Moisture Content' => 'nullable|string',
                'spesifikasi.Oil/Fat Content' => 'nullable|string',
                'spesifikasi.Appearance' => 'nullable|string',
                'spesifikasi.Packaging' => 'nullable|string',
                'spesifikasi.Shelf Life' => 'nullable|string',
                'spesifikasi.Certifications' => 'nullable|string',
                'spesifikasi.Origin' => 'nullable|string',
                'spesifikasi.Use' => 'nullable|string',
            ]);

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('public/products');
                $gambarPath = str_replace('public/', 'storage/', $gambarPath);
            }

            $specs = [
                'Ingredients',
                'Moisture Content',
                'Oil/Fat Content',
                'Appearance',
                'Packaging',
                'Shelf Life',
                'Certifications',
                'Origin',
                'Use'
            ];

            $spesifikasi = [];
            if ($request->has('spesifikasi')) {
                foreach ($request->spesifikasi as $key => $value) {
                    if (in_array($key, $specs)) {
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

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan!'
                ], 201);
            }

            return redirect()->route('products.list')->with('success', 'Produk berhasil ditambahkan!');
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
            'spesifikasi.Ingredients' => 'nullable|string',
            'spesifikasi.Moisture Content' => 'nullable|string',
            'spesifikasi.Oil/Fat Content' => 'nullable|string',
            'spesifikasi.Appearance' => 'nullable|string',
            'spesifikasi.Packaging' => 'nullable|string',
            'spesifikasi.Shelf Life' => 'nullable|string',
            'spesifikasi.Certifications' => 'nullable|string',
            'spesifikasi.Origin' => 'nullable|string',
            'spesifikasi.Use' => 'nullable|string',
        ]);

        $gambarPath = $product->gambar;
        if ($request->hasFile('gambar')) {
            if ($product->gambar && Storage::exists(str_replace('storage/', 'public/', $product->gambar))) {
                Storage::delete(str_replace('storage/', 'public/', $product->gambar));
            }
            $gambarPath = $request->file('gambar')->store('public/products');
            $gambarPath = str_replace('public/', 'storage/', $gambarPath);
        } elseif ($request->boolean('clear_gambar')) {
            if ($product->gambar && Storage::exists(str_replace('storage/', 'public/', $product->gambar))) {
                Storage::delete(str_replace('storage/', 'public/', $product->gambar));
            }
            $gambarPath = null;
        }

        $specs = [
            'Ingredients',
            'Moisture Content',
            'Oil/Fat Content',
            'Appearance',
            'Packaging',
            'Shelf Life',
            'Certifications',
            'Origin',
            'Use'
        ];

        $spesifikasi = [];
        if ($request->has('spesifikasi')) {
            foreach ($request->spesifikasi as $key => $value) {
                if (in_array($key, $specs)) {
                    $spesifikasi[$key] = $value;
                }
            }
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
