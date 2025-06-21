<x-layout.master title="Daftar Produk">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Produk</h1>

        {{-- Alerts --}}
        <x-alerts.session-success />
        <x-alerts.ajax-success />
        <x-alerts.ajax-error />

        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Produk Baru
            </a>
        </div>

        {{-- Tabel Produk --}}
        <x-products.table :products="$products" />

    </div>

    {{-- Modals --}}
    <x-products.edit-modal />
    <x-products.delete-modal />

    {{-- Script JavaScript untuk AJAX (digabung langsung di sini) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show alert messages
            function showAlert(type, message, errors = []) {
                const successAlert = document.getElementById('ajax-success-alert');
                const errorAlert = document.getElementById('ajax-error-alert');
                const successMessage = document.getElementById('ajax-success-message');
                const errorMessage = document.getElementById('ajax-error-message');
                const errorList = document.getElementById('ajax-error-list');

                // Sembunyikan semua alert saat memulai
                if (successAlert) successAlert.classList.add('hidden');
                if (errorAlert) errorAlert.classList.add('hidden');

                if (type === 'success') {
                    if (successAlert && successMessage) {
                        successMessage.textContent = message;
                        successAlert.classList.remove('hidden');
                    }
                } else if (type === 'error') {
                    if (errorAlert && errorMessage && errorList) {
                        errorMessage.textContent = message;
                        errorList.innerHTML = ''; // Kosongkan daftar error sebelumnya
                        errors.forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            errorList.appendChild(li);
                        });
                        errorAlert.classList.remove('hidden');
                    }
                }

                // Sembunyikan alert setelah 5 detik
                setTimeout(() => {
                    if (successAlert) successAlert.classList.add('hidden');
                    if (errorAlert) errorAlert.classList.add('hidden');
                }, 5000);
            }

            // Sembunyikan alert session setelah 5 detik jika ada
            const sessionSuccessAlert = document.getElementById('session-success-alert');
            if (sessionSuccessAlert) {
                setTimeout(() => {
                    sessionSuccessAlert.classList.add('hidden');
                }, 5000);
            }

            // --- Edit Product Modal Logic ---
            const editProductModal = document.getElementById('editProductModal');
            const editProductButtons = document.querySelectorAll('.edit-button');
            const editProductForm = document.getElementById('editProductForm');
            const editNamaInput = document.getElementById('edit_nama');
            const editDeskripsiInput = document.getElementById('edit_deskripsi');
            const editDetailSpesifikasiInput = document.getElementById('edit_detail_spesifikasi');
            const editGambarInput = document.getElementById('edit_gambar');
            const editGambarImg = document.getElementById('edit_gambar_img');
            const clearGambarCheckbox = document.getElementById('clear_gambar_checkbox');
            const editSubmitButton = editProductForm ? editProductForm.querySelector('button[type="submit"]') : null;

            // Fungsi helper untuk men-slugify nama spesifikasi agar cocok dengan ID elemen
            function slugifySpecName(specName) {
                // Konversi ke lowercase, ganti spasi dengan underscore, hapus karakter non-alfanumerik kecuali underscore
                return specName.toLowerCase().replace(/ /g, '_').replace(/[^a-z0-9_]/g, '');
            }

            const specFields = ['Color', 'Odour', 'Solubility in Water', 'Moisture', 'Iodine Value', 'Saponification Value', 'Free Fatty Acid', 'Unsaponifiable Materia'];
            const editSpecInputs = {};
            specFields.forEach(spec => {
                const elementId = `edit_spesifikasi_${slugifySpecName(spec)}`;
                editSpecInputs[spec] = document.getElementById(elementId);
            });

            // Mengisi data modal edit saat tombol edit diklik
            editProductButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Tambahkan di dalam event listener untuk debugging
console.log('All dataset:', this.dataset);
console.log('Detail Spesifikasi raw:', this.dataset.detail_spesifikasi);
console.log('Detail Spesifikasi camelCase:', this.dataset.detailSpesifikasi);
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const deskripsi = this.dataset.deskripsi;
                    // const detailSpesifikasi = this.dataset.detail_spesifikasi
                    const detailSpesifikasi = this.dataset.detail_spesifikasi || this.dataset.detailSpesifikasi ;
                    const gambar = this.dataset.gambar;
                    let spesifikasi = {};
                    try {
                        spesifikasi = JSON.parse(this.dataset.spesifikasi || '{}');
                    } catch (e) {
                        console.error('Error parsing spesifikasi JSON:', e);
                        spesifikasi = {};
                    }
                    const updateUrl = this.dataset.updateUrl;

                    if (editNamaInput) editNamaInput.value = nama;
                    if (editDeskripsiInput) editDeskripsiInput.value = deskripsi;
                        if (editDetailSpesifikasiInput) {
            editDetailSpesifikasiInput.value = detailSpesifikasi;
            console.log('Detail Spesifikasi:', detailSpesifikasi); // Debug log
        }
                    if (editProductForm) editProductForm.action = updateUrl;
                    if (editGambarInput) editGambarInput.value = ''; // Reset input file
                    if (clearGambarCheckbox) clearGambarCheckbox.checked = false; // Reset checkbox hapus gambar

                    // Tampilkan gambar pratinjau jika ada
                    if (editGambarImg) {
                        if (gambar && gambar !== '{{ asset('/') }}' && !gambar.includes('undefined')) {
                            editGambarImg.src = gambar;
                            editGambarImg.classList.remove('hidden');
                        } else {
                            editGambarImg.src = '';
                            editGambarImg.classList.add('hidden');
                        }
                    }

                    // Isi input spesifikasi
                    specFields.forEach(spec => {
                        if (editSpecInputs[spec]) {
                            const value = spesifikasi[spec] || '';
                            editSpecInputs[spec].value = value;
                        }
                    });

                    if (editProductModal) editProductModal.classList.remove('hidden'); // Tampilkan modal
                });
            });

            // Submit form edit produk via AJAX
            if (editProductForm) {
                editProductForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Mencegah submit form tradisional

                    // Tampilkan indikator loading dan nonaktifkan tombol
                    if (editSubmitButton) {
                        editSubmitButton.textContent = 'Memperbarui...';
                        editSubmitButton.disabled = true;
                        editSubmitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }

                    const formData = new FormData(editProductForm);
                    formData.append('_method', 'PUT'); // Tambahkan _method=PUT secara manual

                    // Debugging FormData content (opsional, hapus saat sudah fix)
                    // for (let pair of formData.entries()) {
                    //     console.log(pair[0]+ ': ' + pair[1]);
                    // }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfToken) {
                        console.error('CSRF token meta tag not found.');
                        showAlert('error', 'CSRF token not found. Please refresh the page.');
                        if (editSubmitButton) {
                            editSubmitButton.textContent = 'Perbarui';
                            editSubmitButton.disabled = false;
                            editSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                        return; // Stop execution
                    }

                    fetch(editProductForm.action, {
                        method: 'POST', // Gunakan POST karena FormData akan menyertakan _method=PUT
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content, // **Perbaikan di sini**
                            'X-Requested-With': 'XMLHttpRequest', // Memberi tahu server ini adalah permintaan AJAX
                            'Accept': 'application/json' // Memberi tahu server kita mengharapkan JSON
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Jika respon tidak OK (misalnya 422, 500), baca error dari JSON
                            return response.json().then(err => {
                                throw err; // Lempar error untuk ditangkap di blok catch
                            });
                        }
                        return response.json(); // Jika respon OK, baca data JSON
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            if (editProductModal) editProductModal.classList.add('hidden');
                            window.location.reload(); // Reload halaman untuk update visual lengkap
                        } else {
                            // Tampilkan pesan error dan validasi
                            showAlert('error', data.message || 'Terjadi kesalahan saat memperbarui.', data.errors ? Object.values(data.errors).flat() : []);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error caught:', error);
                        // Tangani error jaringan atau error dari server yang dilempar
                        if (error.errors) {
                            showAlert('error', 'Validasi gagal', Object.values(error.errors).flat());
                        } else if (error.message) {
                            showAlert('error', error.message);
                        } else {
                            showAlert('error', 'Terjadi kesalahan jaringan atau server.');
                        }
                    })
                    .finally(() => {
                        // Selalu kembalikan tombol ke kondisi semula setelah permintaan selesai
                        if (editSubmitButton) {
                            editSubmitButton.textContent = 'Perbarui';
                            editSubmitButton.disabled = false;
                            editSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                });
            }


            // --- Delete Product Modal Logic (sama seperti sebelumnya) ---
            const deleteProductModal = document.getElementById('deleteProductModal');
            const deleteProductButtons = document.querySelectorAll('.delete-button');
            const deleteProductForm = document.getElementById('deleteProductForm');
            const deleteProductName = document.getElementById('deleteProductName');
            const deleteSubmitButton = deleteProductForm ? deleteProductForm.querySelector('button[type="submit"]') : null;

            deleteProductButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const destroyUrl = this.dataset.destroyUrl;

                    if (deleteProductName) deleteProductName.textContent = nama;
                    if (deleteProductForm) deleteProductForm.action = destroyUrl;

                    if (deleteProductModal) deleteProductModal.classList.remove('hidden');
                });
            });

            if (deleteProductForm) {
                deleteProductForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Tampilkan indikator loading dan nonaktifkan tombol
                    if (deleteSubmitButton) {
                        deleteSubmitButton.textContent = 'Menghapus...';
                        deleteSubmitButton.disabled = true;
                        deleteSubmitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }

                    const productId = deleteProductForm.action.split('/').pop();

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token meta tag not found.');
                        showAlert('error', 'CSRF token not found. Please refresh the page.');
                        if (deleteSubmitButton) {
                            deleteSubmitButton.textContent = 'Hapus';
                            deleteSubmitButton.disabled = false;
                            deleteSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                        return; // Stop execution
                    }

                    fetch(deleteProductForm.action, {
                        method: 'POST', // Menggunakan POST untuk mengirimkan _method=DELETE
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content, // **Perbaikan di sini**
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(deleteProductForm) // FormData secara otomatis menyertakan _method
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            if (deleteProductModal) deleteProductModal.classList.add('hidden');

                            const rowToRemove = document.getElementById(`row-${productId}`);
                            if (rowToRemove) {
                                rowToRemove.remove(); // Hapus baris dari DOM secara langsung
                            }
                        } else {
                            showAlert('error', data.message || 'Terjadi kesalahan saat menghapus.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (error.errors) {
                            showAlert('error', error.message || 'Validasi gagal', Object.values(error.errors).flat());
                        } else if (error.message) {
                            showAlert('error', error.message);
                        } else {
                            showAlert('error', 'Terjadi kesalahan jaringan atau server.');
                        }
                    })
                    .finally(() => {
                        // Selalu kembalikan tombol ke kondisi semula
                        if (deleteSubmitButton) {
                            deleteSubmitButton.textContent = 'Hapus';
                            deleteSubmitButton.disabled = false;
                            deleteSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                });
            }

            // --- Close Modals Logic (untuk kedua modal produk) ---
            document.querySelectorAll('.close-modal').forEach(button => {
                button.addEventListener('click', function() {
                    if (editProductModal) editProductModal.classList.add('hidden');
                    if (deleteProductModal) deleteProductModal.classList.add('hidden');
                });
            });

            // Tutup modal jika mengklik di luar area konten modal
            if (editProductModal) {
                editProductModal.addEventListener('click', function(e) {
                    if (e.target === editProductModal) {
                        editProductModal.classList.add('hidden');
                    }
                });
            }
            if (deleteProductModal) {
                deleteProductModal.addEventListener('click', function(e) {
                    if (e.target === deleteProductModal) {
                        deleteProductModal.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-layout.master>

{{-- Pastikan ini ada di file `x-products.edit-modal.blade.php` atau di mana pun Anda mendefinisikan komponen tersebut --}}
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