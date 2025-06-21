<x-layout.master title="Daftar Kategori Artikel">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Kategori Artikel</h1>

        {{-- Alerts --}}
        <x-alerts.session-success />
        <x-alerts.ajax-success />
        <x-alerts.ajax-error />

        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('kategori-artikels.create.form') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori Baru
            </a>
        </div>

        {{-- Table - Make sure this component receives $kategoriArtikels prop --}}
        <x-kategori_artikels.table :kategoriArtikels="$kategoriArtikels" />

    </div>

    {{-- Modals --}}
    <x-kategori_artikels.edit-modal />
    <x-kategori_artikels.delete-modal />

    {{-- Script untuk AJAX, digabung langsung di sini --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show alert messages
            function showAlert(type, message, errors = []) {
                const successAlert = document.getElementById('ajax-success-alert');
                const errorAlert = document.getElementById('ajax-error-alert');
                const successMessage = document.getElementById('ajax-success-message');
                const errorMessage = document.getElementById('ajax-error-message');
                const errorList = document.getElementById('ajax-error-list');

                // Hide previous alerts
                successAlert.classList.add('hidden');
                errorAlert.classList.add('hidden');

                if (type === 'success') {
                    successMessage.textContent = message;
                    successAlert.classList.remove('hidden');
                } else if (type === 'error') {
                    errorMessage.textContent = message;
                    errorList.innerHTML = ''; // Clear previous errors
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        errorList.appendChild(li);
                    });
                    errorAlert.classList.remove('hidden');
                }

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    successAlert.classList.add('hidden');
                    errorAlert.classList.add('hidden');
                }, 5000);
            }

            // Hide the initial success alert from session flash data after a few seconds
            const sessionSuccessAlert = document.getElementById('session-success-alert');
            if (sessionSuccessAlert) {
                setTimeout(() => {
                    sessionSuccessAlert.classList.add('hidden');
                }, 5000);
            }

            // Variable to store the currently active edit button
            let currentEditButton = null; // Ini adalah baris baru yang penting

            // Edit Modal Logic
            const editModal = document.getElementById('editModal');
            const editButtons = document.querySelectorAll('.edit-button');
            const editForm = document.getElementById('editForm');
            const editNameInput = document.getElementById('edit_name');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const updateUrl = this.dataset.updateUrl; // Ambil URL dari atribut data-update-url

                    editNameInput.value = name;
                    editForm.action = updateUrl; // Set form action dengan URL dari atribut data

                    currentEditButton = this; // Simpan referensi tombol yang diklik

                    editModal.classList.remove('hidden');
                });
            });

            // Handle Edit Form Submission via AJAX
            editForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah form melakukan submit tradisional

                const formData = new FormData(editForm);
                const categoryId = editForm.action.split('/').pop(); // Mengambil ID dari URL form action

                fetch(editForm.action, {
                    method: 'POST', // Metode tetap POST karena @method('PUT') akan menanganinya di Laravel
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '',
                        'X-Requested-With': 'XMLHttpRequest', // Memberi tahu Laravel bahwa ini adalah request AJAX
                        'Accept': 'application/json' // Minta respons dalam format JSON
                    },
                    body: formData
                })
                .then(response => response.json()) // Parse respons sebagai JSON
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        editModal.classList.add('hidden'); // Tutup modal

                        // Perbarui nama kategori di tabel secara dinamis
                        const categoryNameElement = document.getElementById(`category-name-${categoryId}`);
                        if (categoryNameElement) {
                            categoryNameElement.textContent = editNameInput.value;
                        }

                        // PERBAIKAN UTAMA: Perbarui atribut data-name pada tombol edit yang terkait
                        if (currentEditButton) {
                            currentEditButton.dataset.name = editNameInput.value;
                        }

                    } else {
                        // Tampilkan pesan error validasi atau error lainnya
                        showAlert('error', data.message || 'Terjadi kesalahan saat memperbarui.', data.errors ? Object.values(data.errors).flat() : []);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan jaringan atau server.');
                });
            });


            // Delete Modal Logic
            const deleteModal = document.getElementById('deleteModal');
            const deleteButtons = document.querySelectorAll('.delete-button');
            const deleteForm = document.getElementById('deleteForm');
            const deleteCategoryName = document.getElementById('deleteCategoryName');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const destroyUrl = this.dataset.destroyUrl; // Ambil URL dari atribut data-destroy-url

                    deleteCategoryName.textContent = name;
                    deleteForm.action = destroyUrl; // Set form action dengan URL dari atribut data

                    deleteModal.classList.remove('hidden');
                });
            });

            // Handle Delete Form Submission via AJAX
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah form melakukan submit tradisional

                const categoryId = deleteForm.action.split('/').pop(); // Mengambil ID dari URL form action

                fetch(deleteForm.action, {
                    method: 'POST', // Metode tetap POST karena @method('DELETE') akan menanganinya di Laravel
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '',
                        'X-Requested-With': 'XMLHttpRequest', // Memberi tahu Laravel bahwa ini adalah request AJAX
                        'Accept': 'application/json' // Minta respons dalam format JSON
                    },
                    body: new FormData(deleteForm) // Kirim _method dan _token
                })
                .then(response => response.json()) // Parse respons sebagai JSON
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        deleteModal.classList.add('hidden'); // Tutup modal

                        // Hapus baris dari tabel secara dinamis
                        const rowToRemove = document.getElementById(`row-${categoryId}`);
                        if (rowToRemove) {
                            rowToRemove.remove();
                        }
                    } else {
                        showAlert('error', data.message || 'Terjadi kesalahan saat menghapus.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan jaringan atau server.');
                });
            });


            // Close Modals Logic
            document.querySelectorAll('.close-modal').forEach(button => {
                button.addEventListener('click', function() {
                    editModal.classList.add('hidden');
                    deleteModal.classList.add('hidden');
                });
            });

            // Close modal when clicking outside of the modal content
            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });

            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
            });
        });
    </script>
</x-layout.master>