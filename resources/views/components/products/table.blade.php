@props(['products'])

@if ($products->isEmpty())
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
        Belum ada produk.
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-md" id="product-table">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Nama</th>
                    <th class="py-3 px-6 text-left">Deskripsi</th>
                    <th class="py-3 px-6 text-left">Gambar</th>
                    <th class="py-3 px-6 text-left">Detail Spesifikasi</th>
                    <th class="py-3 px-6 text-left">Spesifikasi</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($products as $product)
                    <tr id="row-{{ $product->id }}" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $product->id }}</td>
                        <td class="py-3 px-6 text-left" id="product-name-{{ $product->id }}">{{ $product->nama }}</td>
                        <td class="py-3 px-6 text-left">{{ Str::limit($product->deskripsi, 50) }}</td>
                        <td class="py-3 px-6 text-left">
                            @if ($product->gambar)
                                <img src="{{ asset($product->gambar) }}" alt="{{ $product->nama }}" class="w-16 h-16 object-cover rounded-md">
                            @else
                                <span class="text-gray-500">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-left">{{ Str::limit($product->detail_spesifikasi, 50) }}</td>

                        <td class="py-3 px-6 text-left text-xs">
                            @if ($product->spesifikasi)
                                @foreach ($product->spesifikasi as $key => $value)
                                    <p><strong>{{ $key }}:</strong> {{ $value }}</p>
                                @endforeach
                            @else
                                <span class="text-gray-500">Tidak ada spesifikasi</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                <button type="button"
                                        class="edit-button w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center text-white text-sm"
                                        title="Edit"
                                        data-id="{{ $product->id }}"
                                        data-nama="{{ $product->nama }}"
                                        data-deskripsi="{{ $product->deskripsi }}"
                                        data-gambar="{{ asset($product->gambar) }}"
                                        data-spesifikasi="{{ json_encode($product->spesifikasi) }}"
                                                                      data-detail_spesifikasi="{{ $product->detail_spesifikasi }}"

                                        data-update-url="{{ route('products.update', $product->id) }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button"
                                        class="delete-button w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center text-white text-sm"
                                        title="Hapus"
                                        data-id="{{ $product->id }}"
                                        data-nama="{{ $product->nama }}"
                                        data-destroy-url="{{ route('products.destroy', $product->id) }}">
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
        {{ $products->links('pagination::tailwind') }}
    </div>
@endif