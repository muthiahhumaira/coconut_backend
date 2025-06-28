<x-layout.master title="Tambah Produk Baru">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Produk Baru</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Produk --}}
            <div class="mb-4">
                <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk:</label>
                <input type="text"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                    id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama produk" required>
                @error('nama')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                    id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi produk">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gambar --}}
            <div class="mb-4">
                <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Produk:</label>
                <input type="file"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('gambar') border-red-500 @enderror"
                    id="gambar" name="gambar" accept="image/*">
                @error('gambar')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Detail Spesifikasi --}}
            <div class="mb-4">
                <label for="detail_spesifikasi" class="block text-gray-700 text-sm font-bold mb-2">Detail Spesifikasi:</label>
                <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('detail_spesifikasi') border-red-500 @enderror"
                    id="detail_spesifikasi" name="detail_spesifikasi" rows="4" placeholder="Masukkan detail spesifikasi produk">{{ old('detail_spesifikasi') }}</textarea>
                @error('detail_spesifikasi')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Spesifikasi Produk --}}
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Spesifikasi Produk</h3>
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
                        <div class="mb-2">
                            <label for="spesifikasi_{{ Str::slug($spec, '_') }}"
                                class="block text-gray-700 text-sm font-bold mb-1">{{ $spec }}:</label>
                            <input type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('spesifikasi.' . $spec) border-red-500 @enderror"
                                id="spesifikasi_{{ Str::slug($spec, '_') }}" name="spesifikasi[{{ $spec }}]"
                                value="{{ old('spesifikasi.' . $spec) }}" placeholder="Masukkan {{ $spec }}">
                            @error('spesifikasi.' . $spec)
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end">
                
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline mr-2">
                    Simpan
                </button>
                <a href="{{ route('products.list') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layout.master>
