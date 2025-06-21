@props(['kategoriArtikels'])

@if ($kategoriArtikels->isEmpty())
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
        Belum ada kategori artikel.
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-md" id="kategori-table">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Nama Kategori</th>
                    <th class="py-3 px-6 text-left">Dibuat Pada</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($kategoriArtikels as $kategori)
                    <tr id="row-{{ $kategori->id }}" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $kategori->id }}</td>
                        <td class="py-3 px-6 text-left" id="category-name-{{ $kategori->id }}">{{ $kategori->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $kategori->created_at}}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                <button type="button"
                                        class="edit-button w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center text-white text-sm"
                                        title="Edit"
                                        data-id="{{ $kategori->id }}"
                                        data-name="{{ $kategori->name }}"
                                        data-update-url="{{ route('kategori-artikels.update', $kategori->id) }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button"
                                        class="delete-button w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center text-white text-sm"
                                        title="Hapus"
                                        data-id="{{ $kategori->id }}"
                                        data-name="{{ $kategori->name }}"
                                        data-destroy-url="{{ route('kategori-artikels.destroy', $kategori->id) }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $kategoriArtikels->links('pagination::tailwind') }}
    </div>
@endif