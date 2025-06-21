<div id="editProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl leading-6 font-medium text-gray-900">Edit Produk</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
        </div>
        <div class="mt-2">
            <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                {{-- Hapus @method('PUT') dari sini. Kita akan menambahkannya secara manual ke FormData di JS. --}}

                {{-- Nama Produk --}}
                <div class="mb-4">
                    <label for="edit_nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk:</label>
                    <input type="text"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="edit_nama"
                           name="nama"
                           required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label for="edit_deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                              id="edit_deskripsi"
                              name="deskripsi"
                              rows="4"></textarea>
                </div>

                {{-- Gambar --}}
                <div class="mb-4">
                    <label for="edit_gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Produk:</label>
                    <input type="file"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus::ring-indigo-500 focus:border-transparent"
                           id="edit_gambar"
                           name="gambar"
                           accept="image/*">
                    <div id="current_gambar_preview" class="mt-2">
                        <img src="" alt="Current Image" class="w-24 h-24 object-cover rounded-md hidden" id="edit_gambar_img">
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox" name="clear_gambar" id="clear_gambar_checkbox" class="form-checkbox h-5 w-5 text-red-600">
                            <span class="ml-2 text-gray-700">Hapus Gambar</span>
                        </label>
                    </div>
                </div>
          <div class="mb-4">
                    <label for="edit_detail_spesifikasi" class="block text-gray-700 text-sm font-bold mb-2">Detail Spesifikasi:</label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                              id="edit_detail_spesifikasi"
                              name="detail_spesifikasi"
                              rows="4"></textarea>
                </div>
                {{-- Spesifikasi --}}
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Spesifikasi Produk</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $specs = ['Color', 'Odour', 'Solubility in Water', 'Moisture', 'Iodine Value', 'Saponification Value', 'Free Fatty Acid', 'Unsaponifiable Materia'];
                        @endphp
                        @foreach ($specs as $spec)
                            <div class="mb-2">
                                {{-- Gunakan Str::slug untuk menghasilkan ID yang konsisten dengan JavaScript --}}
                                <label for="edit_spesifikasi_{{ Str::slug($spec, '_') }}" class="block text-gray-700 text-sm font-bold mb-1">{{ $spec }}:</label>
                                <input type="text"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       id="edit_spesifikasi_{{ Str::slug($spec, '_') }}"
                                       name="spesifikasi[{{ $spec }}]"
                                       placeholder="Masukkan {{ $spec }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" class="close-modal bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>