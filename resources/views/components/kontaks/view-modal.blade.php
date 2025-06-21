<div id="viewContactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl font-semibold text-gray-900">Detail Kontak</h3>
            <button type="button" class="close-modal text-gray-400 hover:text-gray-500 text-2xl">&times;</button>
        </div>

        <div class="mt-2 space-y-4">
            <div>
                <p class="text-sm text-gray-600">Nama:</p>
                <p class="text-lg font-medium text-gray-900" id="view_nama"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email:</p>
                <p class="text-lg font-medium text-gray-900" id="view_email"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nomor Telepon:</p>
                <p class="text-lg font-medium text-gray-900" id="view_telepon"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pesan:</p>
                <p class="text-lg text-gray-900 whitespace-pre-wrap" id="view_pesan"></p>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="button" class="close-modal bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md">
                Tutup
            </button>
        </div>
    </div>
</div>
