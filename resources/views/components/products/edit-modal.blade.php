<!-- Modal Edit Produk -->
<div id="editProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="relative mx-auto w-full max-w-2xl h-[90vh] bg-white rounded-md shadow-lg flex flex-col overflow-hidden">
        
        <!-- Header Modal -->
        <div class="flex justify-between items-center px-5 pt-4">
            <h3 class="text-2xl font-semibold text-gray-900">Edit Produk</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
        </div>

        <!-- Form Content -->
        <form id="editProductForm" method="POST" action="" enctype="multipart/form-data" class="overflow-y-auto px-5 py-4 space-y-4 flex-grow">
            @csrf

            <!-- Nama Produk -->
            <div>
                <label for="edit_nama" class="block text-gray-700 text-sm font-bold mb-1">Nama Produk:</label>
                <input type="text" id="edit_nama" name="nama" required
                    class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="edit_deskripsi" class="block text-gray-700 text-sm font-bold mb-1">Deskripsi:</label>
                <textarea id="edit_deskripsi" name="deskripsi" rows="4"
                    class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <!-- Gambar -->
            <div>
                <label for="edit_gambar" class="block text-gray-700 text-sm font-bold mb-1">Gambar Produk:</label>
                <input type="file" id="edit_gambar" name="gambar" accept="image/*"
                    class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div id="current_gambar_preview" class="mt-2">
                    <img src="" alt="Current Image" class="w-24 h-24 object-cover rounded-md hidden" id="edit_gambar_img">
                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" name="clear_gambar" id="clear_gambar_checkbox"
                            class="form-checkbox h-5 w-5 text-red-600">
                        <span class="ml-2 text-gray-700">Hapus Gambar</span>
                    </label>
                </div>
            </div>

            <!-- Detail Spesifikasi -->
            <div>
                <label for="edit_detail_spesifikasi" class="block text-gray-700 text-sm font-bold mb-1">Detail Spesifikasi:</label>
                <textarea id="edit_detail_spesifikasi" name="detail_spesifikasi" rows="4"
                    class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <!-- Spesifikasi Produk -->
            <div>
                <h4 class="text-xl font-semibold text-gray-800 mb-2">Spesifikasi Produk</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
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
                    @endphp
                    @foreach ($specs as $spec)
                        <div>
                            <label for="edit_spesifikasi_{{ Str::slug($spec, '_') }}"
                                class="block text-gray-700 text-sm font-bold mb-1">{{ $spec }}:</label>
                            <input type="text" id="edit_spesifikasi_{{ Str::slug($spec, '_') }}"
                                name="spesifikasi[{{ $spec }}]"
                                class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Masukkan {{ $spec }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end pt-2">
                <button type="button"
                    class="close-modal bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md mr-2">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
