<x-layout>
    <x-slot:title>Data Buku</x-slot:title>

<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Data Buku</h1>
        
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('buku.export-excel') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Excel
            </a>

            <button onclick="openImportModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                Import
            </button>

            <a href="{{ route('buku.export-pdf') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                </svg>
                PDF
            </a>

            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Tambah Buku
            </button>
        </div>
    </div>
    <div class="bg-white p-5 rounded shadow-lg overflow-hidden">
        <table id="tableBuku" class="stripe hover w-full" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
            <thead>
                <tr>
                    <th data-priority="1">No</th>
                    <th data-priority="2">Judul</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Penerbit</th>
                    <th>Kota</th>
                    <th>Stok</th>
                    <th>Kategori / Rak</th>
                    <th data-priority="3">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="bukuModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold" id="modalTitle">Tambah Buku</p>
                    <div class="modal-close cursor-pointer z-50" onclick="closeModal()">X</div>
                </div>
                
                <form id="bukuForm" name="bukuForm" class="space-y-4">
                    <input type="hidden" name="id" id="id">
                    <div>
    <label class="block text-sm font-medium text-gray-700">Kategori & Rak</label>
    <select name="kategori_id" id="kategori_id" class="w-full border p-2 rounded bg-white" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $kat)
            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }} ({{ $kat->lokasi_rak }})</option>
        @endforeach
    </select>
</div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                        <input type="text" name="judul" id="judul" class="w-full border p-2 rounded focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penulis</label>
                        <input type="text" name="penulis" id="penulis" class="w-full border p-2 rounded" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                            <input type="number" name="tahun" id="tahun" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah (Stok)</label>
                            <input type="number" name="jumlah" id="jumlah" class="w-full border p-2 rounded" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" class="w-full border p-2 rounded" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kota Terbit</label>
                        <input type="text" name="kota" id="kota" class="w-full border p-2 rounded" required>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="closeModal()" class="px-4 text-indigo-500 mr-2">Batal</button>
                        <button type="submit" id="saveBtn" class="px-4 bg-blue-600 text-white rounded p-2 hover:bg-blue-700 font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<div id="importModal" class="modal-import opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto p-6">
             <form id="importForm" enctype="multipart/form-data">
                <p class="text-xl font-bold mb-4">Import Data Buku</p>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File Excel (.xlsx)</label>
                    <input type="file" name="file_excel" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept=".xlsx" required>
                    <p class="text-xs text-gray-500 mt-1">Urutan kolom: Judul, Penulis, Tahun, Penerbit, Kota, Stok</p>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeImportModal()" class="mr-2 text-gray-500 hover:text-gray-700">Batal</button>
                    <button type="submit" id="btnImport" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Upload & Import</button>
                </div>
             </form>
        </div>
    </div>
    <x-slot:script>
        <script type="text/javascript">
            $(function () {
                
                // 1. Setup CSRF
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                // 2. Inisialisasi DataTables
                var table = $('#tableBuku').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('buku.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'judul', name: 'judul'},
                        {data: 'penulis', name: 'penulis'},
                        {data: 'tahun', name: 'tahun'},
                        {data: 'penerbit', name: 'penerbit'},
                        {data: 'kota', name: 'kota'},
                        {data: 'jumlah', name: 'jumlah'},
                        {data: 'kategori_rak', name: 'kategori.nama_kategori'}, // Kolom Baru
{
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        // Tombol Edit
                        let btn = '<button onclick="editData('+row.id+')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-2 rounded mr-1 text-sm">Edit</button>';
                        
                        // Tombol Hapus
                        btn += '<button onclick="deleteData('+row.id+')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded mr-1 text-sm">Hapus</button>';

                        // --> TOMBOL CETAK LABEL (Warna Hijau Teal)
                        btn += '<a href="/buku/'+row.id+'/label" target="_blank" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-1 px-2 rounded text-sm inline-flex items-center ml-1" title="Cetak Label QR"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></a>';
                        
                        return btn;
                    }
                },                    ],
                    language: {
                        search: "Cari Buku:",
                        lengthMenu: "Tampilkan _MENU_ buku",
                        info: "Halaman _PAGE_ dari _PAGES_",
                    }
                });

                // 3. Simpan Data
                $('#bukuForm').submit(function(e) {
                    e.preventDefault();
                    $('#saveBtn').html('Menyimpan..');
                    
                    $.ajax({
                        data: $(this).serialize(),
                        url: "{{ route('buku.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#bukuForm').trigger("reset");
                            $('#id').val('');
                            closeModal();
                            table.draw();
                            Swal.fire('Sukses!', data.success, 'success');
                            $('#saveBtn').html('Simpan');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Simpan');
                            Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
                        }
                    });
                });

// 4. Edit Data
window.editData = function(id) {
    $.get("{{ url('buku') }}" +'/' + id +'/edit', function (data) {
        $('#modalTitle').html("Edit Data Buku");
        $('#saveBtn').html("Update");
        $('#id').val(data.id);
        
        // Pastikan Kategori terpilih saat edit
        $('#kategori_id').val(data.kategori_id); 
        
        $('#judul').val(data.judul);
        $('#penulis').val(data.penulis);
        $('#tahun').val(data.tahun);
        $('#penerbit').val(data.penerbit);
        $('#kota').val(data.kota);
        $('#jumlah').val(data.jumlah);
        openModal();
    })
};

                // 5. Hapus Data
                window.deleteData = function(id) {
                    Swal.fire({
                        title: 'Hapus Buku Ini?',
                        text: "Data tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('buku') }}"+'/'+id,
                                success: function (data) {
                                    table.draw();
                                    Swal.fire('Terhapus!', data.success, 'success');
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                };
            });

            // Modal Controls
            window.openModal = function() {
                if($('#id').val() == '') {
                    $('#modalTitle').html("Tambah Buku");
                    $('#saveBtn').html("Simpan");
                    $('#bukuForm').trigger("reset");
                }
                document.getElementById('bukuModal').classList.remove('opacity-0', 'pointer-events-none');
            }
            window.closeModal = function() {
                document.getElementById('bukuModal').classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => { 
                    $('#id').val(''); 
                    $('#bukuForm').trigger("reset");
                    $('#saveBtn').html('Simpan'); 
                }, 300);
            }
            // ... (Kode Inisialisasi DataTables dll yang sudah ada) ...

            // --- LOGIKA IMPORT EXCEL ---
            $('#importForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('#btnImport').html('Mengupload...');
                
                $.ajax({
                    type: 'POST',
                    url: "{{ route('buku.import-excel') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        closeImportModal();
                        $('#importForm').trigger("reset");
                        table.draw();
                        Swal.fire('Berhasil!', data.success, 'success');
                        $('#btnImport').html('Upload & Import');
                    },
                    error: function(data) {
                        console.log(data);
                        $('#btnImport').html('Upload & Import');
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat import.', 'error');
                    }
                });
            });

            // --- KONTROL MODAL IMPORT ---
            window.openImportModal = function() {
                document.querySelector('#importModal').classList.remove('opacity-0', 'pointer-events-none');
            }
            window.closeImportModal = function() {
                document.querySelector('#importModal').classList.add('opacity-0', 'pointer-events-none');
            }
        </script>
    </x-slot:script>
</x-layout>