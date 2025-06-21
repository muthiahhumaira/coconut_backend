<x-layout.master title="Daftar Kontak">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Kontak</h1>

        {{-- Alerts --}}
        <x-alerts.session-success />
        <x-alerts.ajax-success />
        <x-alerts.ajax-error />

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Perusahaan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Personal</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($contacts as $contact)
                        <tr id="row-{{ $contact->id }}">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $contact->company_name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $contact->personal_name ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $contact->email ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 space-x-2">
                                <button class="detail-button text-blue-600 hover:underline"
                                    data-id="{{ $contact->id }}"
                                    data-company="{{ $contact->company_name }}"
                                    data-personal="{{ $contact->personal_name }}"
                                    data-email="{{ $contact->email }}"
                                    data-phone="{{ $contact->phone }}"
                                    data-message="{{ $contact->message }}"
                                    data-document="{{ $contact->document }}">
                                    Detail
                                </button>

                                <button class="delete-button text-red-600 hover:underline"
                                    data-id="{{ $contact->id }}"
                                    data-name="{{ $contact->company_name }}"
                                    data-destroy-url="{{ route('kontak.destroy', $contact->id) }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Detail Kontak</h2>
                <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>Perusahaan:</strong> <span id="detail-company"></span></p>
                <p><strong>Nama Personal:</strong> <span id="detail-personal"></span></p>
                <p><strong>Email:</strong> <span id="detail-email"></span></p>
                <p><strong>Telepon:</strong> <span id="detail-phone"></span></p>
                <p><strong>Pesan:</strong> <span id="detail-message"></span></p>
                <p><strong>Dokumen:</strong> <a id="detail-document" href="#" class="text-blue-600 underline" target="_blank">Lihat Dokumen</a></p>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Konfirmasi Hapus</h2>
                <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <p>Yakin ingin menghapus kontak dari <strong id="deleteContactName"></strong>?</p>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" class="close-modal px-4 py-2 bg-gray-200 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function showAlert(type, message) {
                const alert = document.getElementById(`ajax-${type}-alert`);
                const messageEl = document.getElementById(`ajax-${type}-message`);
                alert.classList.remove('hidden');
                messageEl.textContent = message;
                setTimeout(() => alert.classList.add('hidden'), 5000);
            }

            const detailModal = document.getElementById('detailModal');
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');

            // Detail
            document.querySelectorAll('.detail-button').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('detail-company').textContent = button.dataset.company || '-';
                    document.getElementById('detail-personal').textContent = button.dataset.personal || '-';
                    document.getElementById('detail-email').textContent = button.dataset.email || '-';
                    document.getElementById('detail-phone').textContent = button.dataset.phone || '-';
                    document.getElementById('detail-message').textContent = button.dataset.message || '-';

                    const doc = button.dataset.document;
                    const docLink = document.getElementById('detail-document');
                    if (doc) {
                        docLink.href = `/storage/${doc}`;
                        docLink.textContent = "Lihat Dokumen";
                    } else {
                        docLink.href = "#";
                        docLink.textContent = "Tidak ada dokumen";
                    }

                    detailModal.classList.remove('hidden');
                });
            });

            // Delete
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', () => {
                    const name = button.dataset.name;
                    const url = button.dataset.destroyUrl;
                    const id = button.dataset.id;

                    document.getElementById('deleteContactName').textContent = name;
                    deleteForm.action = url;

                    deleteModal.setAttribute('data-row-id', id);
                    deleteModal.classList.remove('hidden');
                });
            });

            deleteForm.addEventListener('submit', e => {
                e.preventDefault();
                const rowId = deleteModal.getAttribute('data-row-id');

                fetch(deleteForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(deleteForm)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        document.getElementById(`row-${rowId}`).remove();
                        deleteModal.classList.add('hidden');
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlert('error', 'Terjadi kesalahan saat menghapus data');
                });
            });

            // Modal close
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    detailModal.classList.add('hidden');
                    deleteModal.classList.add('hidden');
                });
            });

            [detailModal, deleteModal].forEach(modal => {
                modal.addEventListener('click', e => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-layout.master>
