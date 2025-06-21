<div id="deleteArtikelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-sm shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus Artikel</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
        </div>
        <div class="mt-2">
            <p class="text-sm text-gray-500">
                Apakah Anda yakin ingin menghapus artikel "<strong id="deleteArtikelJudul"></strong>"?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="deleteArtikelForm" method="POST" action="" class="mt-4">
                @csrf
                @method('DELETE') {{-- Tetap gunakan @method('DELETE') untuk form ini karena lebih sederhana --}}
                <div class="flex justify-end pt-2">
                    <button type="button" class="close-modal bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>