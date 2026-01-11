<x-layout>
    <x-slot:title>Data Mahasiswa</x-slot:title>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Data Mahasiswa</h1>
        
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('mahasiswa.export-excel') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                Excel
            </a>

            <button onclick="openImportModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                Import
            </button>

            <a href="{{ route('mahasiswa.export-pdf') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                PDF
            </a>

            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                + Baru
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded shadow-lg overflow-hidden">
        <table id="tableMahasiswa" class="stripe hover w-full" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
            <thead>
                <tr>
                    <th data-priority="1">No</th>
                    <th data-priority="2">NIM</th>
                    <th data-priority="3">Nama</th>
                    <th>L/P</th>
                    <th>Kelas</th>
                    <th>Angkatan</th>
                    <th>Jurusan</th>
                    <th>Fakultas</th>
                    <th data-priority="4">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="mahasiswaModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold" id="modalTitle">Tambah Mahasiswa</p>
                    <div class="modal-close cursor-pointer z-50" onclick="closeModal()">X</div>
                </div>
                <form id="mahasiswaForm" name="mahasiswaForm" class="space-y-4">
                    <input type="hidden" name="id" id="id">
                    <input type="text" name="nim" id="nim" placeholder="NIM" class="w-full border p-2 rounded" required>
                    <input type="text" name="nama" id="nama" placeholder="Nama" class="w-full border p-2 rounded" required>
                    <div class="grid grid-cols-2 gap-4">
                        <select name="jk" id="jk" class="w-full border p-2 rounded"><option value="L">L</option><option value="P">P</option></select>
                        <input type="text" name="kelas" id="kelas" placeholder="Kelas" class="w-full border p-2 rounded">
                    </div>
                    <input type="number" name="angkatan" id="angkatan" placeholder="Angkatan" class="w-full border p-2 rounded">
                    <input type="text" name="jurusan" id="jurusan" placeholder="Jurusan" class="w-full border p-2 rounded">
                    <input type="text" name="fakultas" id="fakultas" placeholder="Fakultas" class="w-full border p-2 rounded">

                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="closeModal()" class="px-4 text-indigo-500 mr-2">Batal</button>
                        <button type="submit" id="saveBtn" class="px-4 bg-blue-600 text-white rounded p-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="importModal" class="modal-import opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto p-6">
             <form id="importForm" enctype="multipart/form-data">
                <p class="text-xl font-bold mb-4">Import Excel</p>
                <input type="file" name="file_excel" class="mb-4 border w-full p-2">
                <div class="flex justify-end">
                    <button type="button" onclick="closeImportModal()" class="mr-2">Batal</button>
                    <button type="submit" id="btnImport" class="bg-purple-600 text-white px-4 py-2 rounded">Upload</button>
                </div>
             </form>
        </div>
    </div>

<x-slot:script>
        <script type="text/javascript">
            $(function () {
                
                // 0. Setup CSRF Token agar tidak Error 419/500 saat POST/DELETE
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // 1. Inisialisasi DataTables
                var table = $('#tableMahasiswa').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('mahasiswa.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'nim', name: 'nim'},
                        {data: 'nama', name: 'nama'},
                        {data: 'jk', name: 'jk'},
                        {data: 'kelas', name: 'kelas'},
                        {data: 'angkatan', name: 'angkatan'},
                        {data: 'jurusan', name: 'jurusan'},
                        {data: 'fakultas', name: 'fakultas'},
{
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        // Tombol Edit
                        let btn = '<button onclick="editData('+row.id+')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-2 rounded mr-1 text-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>';
                        
                        // Tombol Hapus
                        btn += '<button onclick="deleteData('+row.id+')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded mr-1 text-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>';

                        // --> TOMBOL CETAK KARTU (BARU)
                        btn += '<a href="/mahasiswa/'+row.id+'/kartu" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-2 rounded text-sm inline-flex items-center" title="Cetak Kartu"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg></a>';
                        
                        return btn;
                    }
                },                    ]
                });

                // 2. LOGIKA SIMPAN (CREATE / UPDATE)
                $('#mahasiswaForm').submit(function(e) {
                    e.preventDefault();
                    $('#saveBtn').html('Menyimpan..'); // Ubah tombol jadi loading
                
                    $.ajax({
                        data: $(this).serialize(),
                        url: "{{ route('mahasiswa.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#mahasiswaForm').trigger("reset"); // Kosongkan form
                            $('#id').val(''); // Pastikan ID kosong lagi
                            closeModal(); // Tutup modal
                            table.draw(); // Refresh tabel
                            Swal.fire('Sukses!', 'Data berhasil disimpan.', 'success');
                            $('#saveBtn').html('Simpan'); // Balikin tombol
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Simpan');
                            // Tampilkan pesan error validasi jika ada
                            Swal.fire('Error!', 'Gagal menyimpan data (Cek inputan Anda).', 'error');
                        }
                    });
                });

                // 3. LOGIKA IMPORT EXCEL
                $('#importForm').submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $('#btnImport').html('Mengupload...');
                    
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('mahasiswa.import-excel') }}", // Pastikan route ini ada
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            closeImportModal();
                            $('#importForm').trigger("reset");
                            table.draw();
                            Swal.fire('Berhasil!', data.success, 'success');
                            $('#btnImport').html('Upload');
                        },
                        error: function(data) {
                            $('#btnImport').html('Upload');
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat import.', 'error');
                        }
                    });
                });

                // 4. LOGIKA EDIT (Ambil data masuk ke Form)
                window.editData = function(id) {
                    $.get("{{ url('mahasiswa') }}" +'/' + id +'/edit', function (data) {
                        $('#modalTitle').html("Edit Data Mahasiswa");
                        $('#saveBtn').html("Update");
                        $('#id').val(data.id);
                        $('#nim').val(data.nim);
                        $('#nama').val(data.nama);
                        $('#jk').val(data.jk);
                        $('#kelas').val(data.kelas);
                        $('#angkatan').val(data.angkatan);
                        $('#jurusan').val(data.jurusan);
                        $('#fakultas').val(data.fakultas);
                        openModal();
                    })
                };

                // 5. LOGIKA DELETE
                window.deleteData = function(id) {
                    Swal.fire({
                        title: 'Yakin hapus data ini?',
                        text: "Data tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('mahasiswa') }}"+'/'+id,
                                success: function (data) {
                                    table.draw();
                                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }
                    });
                };
            });

            // FUNGSI BUKA/TUTUP MODAL (Tanpa jQuery agar ringan)
            function openModal() {
                // Jika ID kosong, berarti Mode Tambah Baru -> Reset Form
                if($('#id').val() == '') {
                    $('#modalTitle').html("Tambah Mahasiswa");
                    $('#saveBtn').html("Simpan");
                    $('#mahasiswaForm').trigger("reset");
                }
                document.getElementById('mahasiswaModal').classList.remove('opacity-0', 'pointer-events-none');
            }

            function closeModal() {
                document.getElementById('mahasiswaModal').classList.add('opacity-0', 'pointer-events-none');
                // Beri jeda sedikit sebelum reset ID agar transisi mulus
                setTimeout(() => { 
                    $('#id').val(''); 
                    $('#mahasiswaForm').trigger("reset");
                    $('#saveBtn').html('Simpan'); 
                }, 300);
            }

            function openImportModal() {
                document.getElementById('importModal').classList.remove('opacity-0', 'pointer-events-none');
            }

            function closeImportModal() {
                document.getElementById('importModal').classList.add('opacity-0', 'pointer-events-none');
            }
        </script>
    </x-slot:script>

</x-layout>