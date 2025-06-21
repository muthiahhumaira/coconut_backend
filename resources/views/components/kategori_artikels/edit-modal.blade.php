<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl leading-6 font-medium text-gray-900">Edit Kategori Artikel</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
        </div>
        <div class="mt-2">
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_name" class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori:</label>
                    <input type="text"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="edit_name"
                           name="name"
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