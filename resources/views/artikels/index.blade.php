<x-layout.master title="Daftar Artikel">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Artikel</h1>

        {{-- Alerts --}}
        <x-alerts.session-success />
        <x-alerts.ajax-success />
        <x-alerts.ajax-error />

        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('artikels.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Artikel Baru
            </a>
        </div>

        {{-- Tabel Artikel --}}
        <x-artikels.table :artikels="$artikels" />

    </div>

    {{-- Modals --}}
    <x-artikels.edit-modal />
    <x-artikels.delete-modal />

    {{-- Script JavaScript untuk AJAX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show alert messages (Same as before, can be moved to a shared JS file)
            function showAlert(type, message, errors = []) {
                const successAlert = document.getElementById('ajax-success-alert');
                const errorAlert = document.getElementById('ajax-error-alert');
                const successMessage = document.getElementById('ajax-success-message');
                const errorMessage = document.getElementById('ajax-error-message');
                const errorList = document.getElementById('ajax-error-list');

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
                        errorList.innerHTML = '';
                        errors.forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            errorList.appendChild(li);
                        });
                        errorAlert.classList.remove('hidden');
                    }
                }

                setTimeout(() => {
                    if (successAlert) successAlert.classList.add('hidden');
                    if (errorAlert) errorAlert.classList.add('hidden');
                }, 5000);
            }

            const sessionSuccessAlert = document.getElementById('session-success-alert');
            if (sessionSuccessAlert) {
                setTimeout(() => {
                    sessionSuccessAlert.classList.add('hidden');
                }, 5000);
            }


            // --- Edit Artikel Modal Logic ---
            const editArtikelModal = document.getElementById('editArtikelModal');
            const editArtikelButtons = document.querySelectorAll('.edit-button');
            const editArtikelForm = document.getElementById('editArtikelForm');
            const editKategoriIdInput = document.getElementById('edit_kategori_id');
            const editPenulisInput = document.getElementById('edit_penulis');
            const editJudulInput = document.getElementById('edit_judul');
            const editDeskripsiInput = document.getElementById('edit_deskripsi');
            const editGambarInput = document.getElementById('edit_gambar');
            const editGambarImg = document.getElementById('edit_gambar_img');
            const clearGambarCheckbox = document.getElementById('clear_gambar_checkbox');
            const editTanggalTerbitInput = document.getElementById('edit_tanggal_terbit');
            const editSubmitButton = editArtikelForm ? editArtikelForm.querySelector('button[type="submit"]') : null;


            editArtikelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const kategoriId = this.dataset.kategoriId;
                    const penulis = this.dataset.penulis;
                    const judul = this.dataset.judul;
                    const deskripsi = this.dataset.deskripsi;
                    const gambar = this.dataset.gambar;
                    const tanggalTerbit = this.dataset.tanggalTerbit;
                    const updateUrl = this.dataset.updateUrl;

                    if (editKategoriIdInput) editKategoriIdInput.value = kategoriId;
                    if (editPenulisInput) editPenulisInput.value = penulis;
                    if (editJudulInput) editJudulInput.value = judul;
                    if (editDeskripsiInput) editDeskripsiInput.value = deskripsi;
                    if (editTanggalTerbitInput) editTanggalTerbitInput.value = tanggalTerbit;
                    if (editArtikelForm) editArtikelForm.action = updateUrl;

                    if (editGambarInput) editGambarInput.value = '';
                    if (clearGambarCheckbox) clearGambarCheckbox.checked = false;

                    if (editGambarImg) {
                        if (gambar && gambar !== '{{ asset('/') }}' && !gambar.includes('undefined') && gambar.includes('/storage/')) {
                            editGambarImg.src = gambar;
                            editGambarImg.classList.remove('hidden');
                        } else {
                            editGambarImg.src = '';
                            editGambarImg.classList.add('hidden');
                        }
                    }

                    if (editArtikelModal) editArtikelModal.classList.remove('hidden');
                });
            });

            if (editArtikelForm) {
                editArtikelForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (editSubmitButton) {
                        editSubmitButton.textContent = 'Memperbarui...';
                        editSubmitButton.disabled = true;
                        editSubmitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }

                    const formData = new FormData(editArtikelForm);
                    formData.append('_method', 'PUT');

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfToken) {
                        console.error('CSRF token meta tag not found.');
                        showAlert('error', 'CSRF token not found. Please refresh the page.');
                        if (editSubmitButton) {
                            editSubmitButton.textContent = 'Perbarui';
                            editSubmitButton.disabled = false;
                            editSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                        return;
                    }

                    fetch(editArtikelForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            if (editArtikelModal) editArtikelModal.classList.add('hidden');
                            window.location.reload();
                        } else {
                            showAlert('error', data.message || 'Terjadi kesalahan saat memperbarui.', data.errors ? Object.values(data.errors).flat() : []);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error caught:', error);
                        if (error.errors) {
                            showAlert('error', 'Validasi gagal', Object.values(error.errors).flat());
                        } else if (error.message) {
                            showAlert('error', error.message);
                        } else {
                            showAlert('error', 'Terjadi kesalahan jaringan atau server.');
                        }
                    })
                    .finally(() => {
                        if (editSubmitButton) {
                            editSubmitButton.textContent = 'Perbarui';
                            editSubmitButton.disabled = false;
                            editSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                });
            }

            // --- Delete Artikel Modal Logic ---
            const deleteArtikelModal = document.getElementById('deleteArtikelModal');
            const deleteArtikelButtons = document.querySelectorAll('.delete-button');
            const deleteArtikelForm = document.getElementById('deleteArtikelForm');
            const deleteArtikelJudul = document.getElementById('deleteArtikelJudul');
            const deleteSubmitButton = deleteArtikelForm ? deleteArtikelForm.querySelector('button[type="submit"]') : null;

            deleteArtikelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const judul = this.dataset.judul;
                    const destroyUrl = this.dataset.destroyUrl;

                    if (deleteArtikelJudul) deleteArtikelJudul.textContent = judul;
                    if (deleteArtikelForm) deleteArtikelForm.action = destroyUrl;

                    if (deleteArtikelModal) deleteArtikelModal.classList.remove('hidden');
                });
            });

            if (deleteArtikelForm) {
                deleteArtikelForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (deleteSubmitButton) {
                        deleteSubmitButton.textContent = 'Menghapus...';
                        deleteSubmitButton.disabled = true;
                        deleteSubmitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }

                    const artikelId = deleteArtikelForm.action.split('/').pop();
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');

                    if (!csrfToken) {
                        console.error('CSRF token meta tag not found.');
                        showAlert('error', 'CSRF token not found. Please refresh the page.');
                        if (deleteSubmitButton) {
                            deleteSubmitButton.textContent = 'Hapus';
                            deleteSubmitButton.disabled = false;
                            deleteSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                        return;
                    }

                    fetch(deleteArtikelForm.action, {
                        method: 'POST', // Menggunakan POST untuk mengirimkan _method=DELETE
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(deleteArtikelForm) // FormData otomatis menyertakan _method
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
                            if (deleteArtikelModal) deleteArtikelModal.classList.add('hidden');

                            const rowToRemove = document.getElementById(`artikel-row-${artikelId}`);
                            if (rowToRemove) {
                                rowToRemove.remove();
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
                        if (deleteSubmitButton) {
                            deleteSubmitButton.textContent = 'Hapus';
                            deleteSubmitButton.disabled = false;
                            deleteSubmitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                });
            }

            // --- Close Modals Logic ---
            document.querySelectorAll('.close-modal').forEach(button => {
                button.addEventListener('click', function() {
                    if (editArtikelModal) editArtikelModal.classList.add('hidden');
                    if (deleteArtikelModal) deleteArtikelModal.classList.add('hidden');
                });
            });

            if (editArtikelModal) {
                editArtikelModal.addEventListener('click', function(e) {
                    if (e.target === editArtikelModal) {
                        editArtikelModal.classList.add('hidden');
                    }
                });
            }
            if (deleteArtikelModal) {
                deleteArtikelModal.addEventListener('click', function(e) {
                    if (e.target === deleteArtikelModal) {
                        deleteArtikelModal.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-layout.master>