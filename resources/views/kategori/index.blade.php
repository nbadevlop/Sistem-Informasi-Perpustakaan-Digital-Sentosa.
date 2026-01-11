<x-layout>
    <x-slot:title>Manajemen Kategori & Rak</x-slot:title>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Data Kategori & Rak Buku</h1>
        
        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            + Tambah Kategori
        </button>
    </div>

    <div class="bg-white p-5 rounded shadow-lg overflow-hidden">
        <table id="tableKategori" class="stripe hover w-full" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Nama Kategori</th>
                    <th>Lokasi Rak</th>
                    <th style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="kategoriModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold" id="modalTitle">Tambah Kategori</p>
                    <div class="modal-close cursor-pointer z-50" onclick="closeModal()">
                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                </div>

                <form id="kategoriForm" name="kategoriForm">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_kategori" name="nama_kategori" placeholder="Contoh: Komputer, Sains" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Rak</label>
                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lokasi_rak" name="lokasi_rak" placeholder="Contoh: Rak A-01" required>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="button" onclick="closeModal()" class="px-4 text-indigo-500 mr-2">Batal</button>
                        <button type="submit" id="saveBtn" class="px-4 bg-blue-600 text-white rounded p-2 hover:bg-blue-700 font-bold">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <x-slot:script>
        <script type="text/javascript">
            $(function () {
                // Setup CSRF
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

                // 1. Render Table
                var table = $('#tableKategori').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('kategori.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'nama_kategori', name: 'nama_kategori'},
                        {data: 'lokasi_rak', name: 'lokasi_rak'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

                // 2. Simpan Data (Dengan Error Handling yang Lebih Baik)
                $('#kategoriForm').submit(function (e) {
                    e.preventDefault();
                    $('#saveBtn').html('Menyimpan..');
                    
                    $.ajax({
                        data: $(this).serialize(),
                        url: "{{ route('kategori.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#kategoriForm').trigger("reset");
                            $('#id').val('');
                            closeModal();
                            table.draw();
                            Swal.fire('Berhasil!', data.success, 'success');
                            $('#saveBtn').html('Simpan');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Simpan');
                            
                            // Cek apakah error validasi (422) atau error server (500)
                            if (data.status === 422) {
                                let errorMsg = "";
                                $.each(data.responseJSON.errors, function (key, value) {
                                    errorMsg += value[0] + "<br/>";
                                });
                                Swal.fire('Gagal!', errorMsg, 'warning');
                            } else {
                                Swal.fire('Error!', 'Terjadi kesalahan sistem. Cek Model Kategori.', 'error');
                            }
                        }
                    });
                });

                // 3. Edit Data
                window.editData = function(id) {
                    $.get("{{ url('kategori') }}" +'/' + id +'/edit', function (data) {
                        $('#modalTitle').html("Edit Kategori");
                        $('#saveBtn').html("Update");
                        $('#id').val(data.id);
                        $('#nama_kategori').val(data.nama_kategori);
                        $('#lokasi_rak').val(data.lokasi_rak);
                        openModal();
                    })
                };

                // 4. Hapus Data
                window.deleteData = function(id) {
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('kategori') }}"+'/'+id,
                                success: function (data) {
                                    table.draw();
                                    Swal.fire('Terhapus!', data.success, 'success');
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                                }
                            });
                        }
                    });
                };
            });

            // Fungsi Modal
            window.openModal = function() {
                if($('#id').val() == ''){
                    $('#modalTitle').html("Tambah Kategori");
                    $('#kategoriForm').trigger("reset");
                    $('#saveBtn').html('Simpan');
                }
                document.getElementById('kategoriModal').classList.remove('opacity-0', 'pointer-events-none');
            }

            window.closeModal = function() {
                document.getElementById('kategoriModal').classList.add('opacity-0', 'pointer-events-none');
                setTimeout(function(){
                    $('#id').val('');
                    $('#kategoriForm').trigger("reset");
                }, 300);
            }
        </script>
    </x-slot:script>
</x-layout>