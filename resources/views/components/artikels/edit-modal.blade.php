<div id="editArtikelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl leading-6 font-medium text-gray-900">Edit Artikel</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
        </div>
        <div class="mt-2">
            <form id="editArtikelForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') tidak digunakan di sini karena AJAX akan menambahkannya ke FormData --}}

                {{-- Kategori ID --}}
                <div class="mb-4">
                    <label for="edit_kategori_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori ID:</label>
                    {{-- Anda mungkin ingin menggantinya dengan dropdown pilihan kategori --}}
                    <input type="number"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="edit_kategori_id"
                           name="kategori_id"
                           required>
                </div>

                {{-- Penulis --}}
                <div class="mb-4">
                    <label for="edit_penulis" class="block text-gray-700 text-sm font-bold mb-2">Penulis:</label>
                    <input type="text"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="edit_penulis"
                           name="penulis"
                           required>
                </div>

                {{-- Judul --}}
                <div class="mb-4">
                    <label for="edit_judul" class="block text-gray-700 text-sm font-bold mb-2">Judul Artikel:</label>
                    <input type="text"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="edit_judul"
                           name="judul"
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
                    <label for="edit_gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Artikel:</label>
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

                {{-- Tanggal Terbit --}}
                <div class="mb-4">
                    <label for="edit_tanggal_terbit" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Terbit:</label>
                    <input type="date"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="edit_tanggal_terbit"
                           name="tanggal_terbit"
                           required>
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