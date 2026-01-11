<x-layout>
    <x-slot:title>Transaksi Peminjaman</x-slot:title>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Transaksi Peminjaman</h1>
        
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('peminjaman.export-pdf') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                </svg>
                PDF
            </a>

            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Transaksi Baru
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded shadow-lg overflow-hidden">
        <table id="tablePeminjaman" class="stripe hover w-full" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
            <thead>
                <tr>
                    <th data-priority="1">No</th>
                    <th data-priority="2">Nama Mahasiswa</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tenggat</th>
                    <th>Status</th>
                    <th>Info Denda</th>
                    <th data-priority="3">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="peminjamanModal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-11/12 md:max-w-xl mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold" id="modalTitle">Transaksi Baru</p>
                    <div class="modal-close cursor-pointer z-50" onclick="closeModal()">X</div>
                </div>
                
                <form id="peminjamanForm" name="peminjamanForm" class="space-y-4">
                    <input type="hidden" name="id" id="id">

                    <div id="divScan" class="p-4 bg-indigo-50 border border-indigo-200 rounded mb-4 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-indigo-600 uppercase mb-1">1. SCAN KARTU MAHASISWA (NIM)</label>
                                <input type="text" id="scan_mhs_qr" class="w-full border-2 border-indigo-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700 placeholder-indigo-300" placeholder="Scan NIM..." autocomplete="off">
                                <p class="text-xs mt-1 h-4" id="scanMhsStatus"></p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-teal-600 uppercase mb-1">2. SCAN LABEL BUKU (ID)</label>
                                <input type="text" id="scan_buku_qr" class="w-full border-2 border-teal-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 font-bold text-gray-700 placeholder-teal-300" placeholder="Scan Buku..." autocomplete="off">
                                <p class="text-xs mt-1 h-4" id="scanBukuStatus"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mahasiswa</label>
                        <select name="mahasiswa_id" id="mahasiswa_id" class="w-full border p-2 rounded bg-white" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Buku</label>
                        <select name="buku_id" id="buku_id" class="w-full border p-2 rounded bg-white" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($bukus as $buku)
                                <option value="{{ $buku->id }}">{{ $buku->judul }} (Stok: {{ $buku->jumlah }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tgl Pinjam (Otomatis)</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="w-full border p-2 rounded bg-gray-200 text-gray-600 cursor-not-allowed" readonly required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <input type="text" name="status" id="status" class="w-full border p-2 rounded bg-gray-200 text-gray-800 font-bold cursor-not-allowed" readonly>
                    </div>

                    <div id="divPengembalian" class="hidden border-t pt-4 mt-4 bg-gray-50 p-3 rounded">
                        <h3 class="text-sm font-bold text-gray-700 mb-2">Info Pengembalian</h3>
                        
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Tgl Dikembalikan (Otomatis)</label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="w-full border p-2 rounded bg-gray-200 text-gray-800 cursor-not-allowed" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Denda Keterlambatan</label>
                            <div class="flex items-center">
                                <span class="bg-gray-200 border border-r-0 border-gray-300 rounded-l p-2 text-gray-600">Rp</span>
                                <input type="number" name="denda" id="denda" class="w-full border p-2 rounded-r bg-gray-100 text-red-600 font-bold" value="0" readonly>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="infoDenda">Tidak ada denda.</p>
                        </div>
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
            const BIAYA_DENDA_PER_HARI = 1000; 

            $(function () {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

                // 1. DataTables
                var table = $('#tablePeminjaman').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('peminjaman.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'nama_mahasiswa', name: 'mahasiswa.nama'},
                        {data: 'judul_buku', name: 'buku.judul'},
                        {data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
                        {data: 'tanggal_kembali', name: 'tanggal_kembali'},
                        {
                            data: 'status', 
                            render: function(data) {
                                if(data && data.trim() === 'Dipinjam') return '<span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Dipinjam</span>';
                                else return '<span class="bg-green-200 text-green-800 text-xs font-semibold px-2 py-1 rounded">Dikembalikan</span>';
                            }
                        }, 
                        { data: 'denda_info', name: 'denda_info' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });

                // 2. Logic Scanner Mahasiswa
                $('#scan_mhs_qr').on('keyup', function(e) {
                    var nim = $(this).val();
                    if(e.keyCode === 13 || nim.length >= 5) { 
                        e.preventDefault();
                        var found = false;
                        
                        $('#mahasiswa_id option').each(function() {
                            var text = $(this).text(); 
                            if(text.includes(nim) && nim !== '') {
                                $('#mahasiswa_id').val($(this).val()).trigger('change');
                                found = true;
                                return false; 
                            }
                        });

                        if(found) {
                            $('#scanMhsStatus').html('<span class="text-green-600 font-bold">✓ Mahasiswa OK</span>');
                            $('#scan_buku_qr').focus(); 
                        } else {
                            $('#scanMhsStatus').html('<span class="text-red-500">x Tidak ditemukan</span>');
                            $('#mahasiswa_id').val('');
                        }
                    }
                });

                // 3. Logic Scanner Buku
                $('#scan_buku_qr').on('keyup', function(e) {
                    var bukuId = $(this).val();
                    if(e.keyCode === 13 || bukuId.length >= 1) { 
                        e.preventDefault();
                        var found = false;
                        if ($('#buku_id option[value="'+bukuId+'"]').length > 0) {
                             $('#buku_id').val(bukuId).trigger('change');
                             found = true;
                        }
                        if(found) {
                            $('#scanBukuStatus').html('<span class="text-green-600 font-bold">✓ Buku OK</span>');
                        } else {
                            $('#scanBukuStatus').html('<span class="text-red-500">x Buku tidak ada</span>');
                            $('#buku_id').val('');
                        }
                    }
                });


                // 4. Simpan Data
                $('#peminjamanForm').submit(function(e) {
                    e.preventDefault();
                    $('#saveBtn').html('Menyimpan..');
                    
                    $.ajax({
                        data: $(this).serialize(),
                        url: "{{ route('peminjaman.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#peminjamanForm').trigger("reset");
                            $('#id').val('');
                            closeModal();
                            table.draw();
                            Swal.fire('Sukses!', 'Transaksi berhasil.', 'success');
                            $('#saveBtn').html('Simpan');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Simpan');
                            if(data.responseJSON.error) {
                                Swal.fire('Gagal!', data.responseJSON.error, 'error');
                            } else {
                                Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
                            }
                        }
                    });
                });

                // 5. Edit Data (Pengembalian)
                window.editData = function(id) {
                    $.get("{{ url('peminjaman') }}" +'/' + id +'/edit', function (data) {
                        $('#modalTitle').html("Pengembalian Buku");
                        $('#saveBtn').html("Simpan Pengembalian");
                        $('#id').val(data.id);
                        $('#mahasiswa_id').val(data.mahasiswa_id);
                        $('#buku_id').val(data.buku_id);
                        
                        // Mode Edit: Matikan Input & Scanner
                        $('#mahasiswa_id').addClass('bg-gray-100 pointer-events-none');
                        $('#buku_id').addClass('bg-gray-100 pointer-events-none');
                        $('#divScan').addClass('hidden'); 
                        
                        // Munculkan Area Pengembalian
                        $('#divPengembalian').removeClass('hidden'); 
                        
                        // Tanggal Pinjam Readonly
                        $('#tanggal_pinjam').val(data.tanggal_pinjam);
                        
                        // === LOGIKA PENGEMBALIAN OTOMATIS ===
                        var today = new Date().toISOString().split('T')[0];
                        
                        // 1. Tgl Kembali = Hari Ini (Readonly)
                        $('#tanggal_kembali').val(today);

                        // 2. Status = Dikembalikan (Readonly, tidak bisa ubah ke Dipinjam)
                        $('#status').val('Dikembalikan');

                        // 3. Hitung Denda
                        hitungDenda(today);

                        openModal();
                    })
                };

                // 6. Hitung Denda Logic
                function hitungDenda(tglKembali) {
                    var tglPinjam = new Date($('#tanggal_pinjam').val());
                    var jatuhTempo = new Date(tglPinjam);
                    jatuhTempo.setDate(jatuhTempo.getDate() + 7); // Jatuh Tempo = Pinjam + 7 Hari

                    var aktualKembali = new Date(tglKembali);
                    jatuhTempo.setHours(0,0,0,0);
                    aktualKembali.setHours(0,0,0,0);

                    var diffTime = aktualKembali - jatuhTempo;
                    
                    if (diffTime > 0) {
                        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        var totalDenda = diffDays * BIAYA_DENDA_PER_HARI;
                        $('#denda').val(totalDenda);
                        $('#infoDenda').html(`<span class="text-red-600 font-bold">Terlambat ${diffDays} hari (Rp ${BIAYA_DENDA_PER_HARI}/hari).</span>`);
                    } else {
                        $('#denda').val(0);
                        $('#infoDenda').html('<span class="text-green-600 font-bold">Tepat Waktu (Tidak ada denda).</span>');
                    }
                }

                // 7. Bayar Denda
                window.bayarDenda = function(id, nominal) {
                    Swal.fire({
                        title: 'Bayar Denda?',
                        text: "Bayar denda Rp " + parseInt(nominal).toLocaleString('id-ID'),
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lunasi!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('peminjaman.bayar') }}",
                                data: { id: id },
                                success: function (data) {
                                    table.draw();
                                    Swal.fire('Lunas!', data.success, 'success');
                                }
                            });
                        }
                    });
                };
            });

            // Modal Functions
            window.openModal = function() {
                if($('#id').val() == '') {
                    $('#modalTitle').html("Transaksi Baru");
                    $('#peminjamanForm').trigger("reset");
                    
                    // === SETTING OTOMATIS TRANSAKSI BARU ===
                    document.getElementById('tanggal_pinjam').valueAsDate = new Date();
                    
                    // Status Otomatis "Dipinjam" (Readonly)
                    $('#status').val('Dipinjam');  
                    
                    $('#divScan').removeClass('hidden');     
                    $('#divPengembalian').addClass('hidden'); 
                    $('#scanMhsStatus').text('');
                    $('#scanBukuStatus').text('');
                    
                    document.getElementById('peminjamanModal').classList.remove('opacity-0', 'pointer-events-none');
                    setTimeout(() => { $('#scan_mhs_qr').focus(); }, 100);
                } else {
                    document.getElementById('peminjamanModal').classList.remove('opacity-0', 'pointer-events-none');
                }
            }

            window.closeModal = function() {
                document.getElementById('peminjamanModal').classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => { 
                    $('#id').val(''); 
                    $('#peminjamanForm').trigger("reset"); 
                    $('#saveBtn').html('Simpan');
                    
                    $('#mahasiswa_id').removeClass('bg-gray-100 pointer-events-none');
                    $('#buku_id').removeClass('bg-gray-100 pointer-events-none');
                    $('#denda').val(0);
                    $('#infoDenda').text('');
                }, 300);
            }
        </script>
    </x-slot:script>
</x-layout>