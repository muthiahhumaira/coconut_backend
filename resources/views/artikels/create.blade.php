<x-layout.master title="Tambah Artikel Baru">
    <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Tambah Artikel Baru</h1>

        {{-- Session Alerts --}}
        <x-alerts.session-success />
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


        <form action="{{ route('artikels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Kategori ID --}}
            <div class="mb-4">
                <label for="kategori_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('kategori_id') border-red-500 @enderror"
                        id="kategori_id"
                        name="kategori_id"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->name }} {{-- Asumsi kolom nama kategori adalah 'name' --}}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Penulis --}}
            <div class="mb-4">
                <label for="penulis" class="block text-gray-700 text-sm font-bold mb-2">Penulis:</label>
                <input type="text"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('penulis') border-red-500 @enderror"
                       id="penulis"
                       name="penulis"
                       value="{{ old('penulis') }}"
                       required>
                @error('penulis')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul Artikel --}}
            <div class="mb-4">
                <label for="judul" class="block text-gray-700 text-sm font-bold mb-2">Judul Artikel:</label>
                <input type="text"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('judul') border-red-500 @enderror"
                       id="judul"
                       name="judul"
                       value="{{ old('judul') }}"
                       required>
                @error('judul')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi (TinyMCE) --}}
            <div class="mb-4">
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                          id="deskripsi"
                          name="deskripsi"
                          rows="6">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gambar Artikel --}}
            <div class="mb-4">
                <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Artikel:</label>
                <input type="file"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('gambar') border-red-500 @enderror"
                       id="gambar"
                       name="gambar"
                       accept="image/*">
                @error('gambar')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Terbit --}}
            <div class="mb-6">
                <label for="tanggal_terbit" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Terbit:</label>
                <input type="date"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('tanggal_terbit') border-red-500 @enderror"
                       id="tanggal_terbit"
                       name="tanggal_terbit"
                       value="{{ old('tanggal_terbit', \Carbon\Carbon::now()->toDateString()) }}"
                       required>
                @error('tanggal_terbit')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                    Simpan Artikel
                </button>
                <a href="{{ route('artikels.list') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- TinyMCE Script --}}
    <!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/nn9m51p4vb08on0l2t1rts4w65bnmbiklkkv6tvrps3bglti/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>

</x-layout.master>