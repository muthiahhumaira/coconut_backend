@props(['artikels'])

<div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
        <thead>
            <tr class="text-left">
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm">ID</th>
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm">Gambar</th>
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm">Kategori ID</th>
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm">Penulis</th>
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm">Judul</th>
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm">Tanggal Terbit</th>
                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100 font-bold text-gray-600 text-sm text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($artikels as $artikel)
                <tr class="hover:bg-gray-50" id="artikel-row-{{ $artikel->id }}">
                    <td class="border-dashed border-t border-gray-200 py-2 px-3 text-sm">{{ $artikel->id }}</td>
                    <td class="border-dashed border-t border-gray-200 py-2 px-3">
                        @if ($artikel->gambar)
                            <img src="{{ asset($artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-16 h-16 object-cover rounded-md">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" alt="No Image" class="w-16 h-16 object-cover rounded-md">
                        @endif
                    </td>
                    <td class="border-dashed border-t border-gray-200 py-2 px-3 text-sm">{{ $artikel->kategori_id }}</td>
                    <td class="border-dashed border-t border-gray-200 py-2 px-3 text-sm">{{ $artikel->penulis }}</td>
                    <td class="border-dashed border-t border-gray-200 py-2 px-3 text-sm artikel-judul">{{ $artikel->judul }}</td>
                    <td class="border-dashed border-t border-gray-200 py-2 px-3 text-sm">{{ \Carbon\Carbon::parse($artikel->tanggal_terbit)->format('d M Y') }}</td>
                    <td class="border-dashed border-t border-gray-200 py-2 px-3 text-center">
                        <button
                            type="button"
                            class="edit-button bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded-md text-xs inline-flex items-center"
                            data-id="{{ $artikel->id }}"
                            data-kategori-id="{{ $artikel->kategori_id }}"
                            data-penulis="{{ $artikel->penulis }}"
                            data-judul="{{ $artikel->judul }}"
                            data-deskripsi="{{ $artikel->deskripsi }}"
                            data-gambar="{{ asset($artikel->gambar) }}"
                            data-tanggal-terbit="{{ $artikel->tanggal_terbit }}"
                            data-update-url="{{ route('artikels.update', $artikel->id) }}"
                        >
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button
                            type="button"
                            class="delete-button bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-md text-xs inline-flex items-center ml-2"
                            data-id="{{ $artikel->id }}"
                            data-judul="{{ $artikel->judul }}"
                            data-destroy-url="{{ route('artikels.destroy', $artikel->id) }}"
                        >
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada artikel yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $artikels->links() }}
    </div>
</div>