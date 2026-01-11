<x-layout>
    <x-slot:title>Pengembalian Cepat</x-slot:title>

    <div class="max-w-4xl mx-auto">
        <div class="bg-indigo-600 p-6 rounded-t-lg text-white shadow-lg text-center">
            <h1 class="text-3xl font-bold mb-2">SCAN PENGEMBALIAN BUKU</h1>
            <p class="opacity-90">Scan Label QR Buku untuk memproses pengembalian secara otomatis.</p>
        </div>

        <div class="bg-white p-8 rounded-b-lg shadow-lg border border-gray-200">
            <div class="mb-6">
                <input type="text" id="scan_input" 
                    class="w-full text-center text-2xl font-bold p-4 border-2 border-indigo-400 rounded-lg focus:outline-none focus:ring-4 focus:ring-indigo-300 placeholder-gray-300" 
                    placeholder="Klik disini & Scan Buku..." autocomplete="off" autofocus>
                <p class="text-center text-gray-400 text-sm mt-2">Pastikan kursor aktif di kolom di atas.</p>
            </div>

            <div id="result_area" class="hidden animate-fade-in-down">
                
                <div id="alert_box" class="p-4 rounded-lg text-center mb-4">
                    <h2 id="status_title" class="text-2xl font-bold"></h2>
                    <p id="status_desc" class="text-lg"></p>
                </div>

                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Peminjam</p>
                            <p class="font-bold text-lg text-gray-800" id="res_mhs">-</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Judul Buku</p>
                            <p class="font-bold text-lg text-gray-800" id="res_buku">-</p>
                        </div>
                        <div class="col-span-2 border-t pt-3 mt-1">
                            <p class="text-gray-500">Status Denda</p>
                            <p class="font-bold text-xl" id="res_denda">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot:script>
        <script>
            $(document).ready(function() {
                // Auto Focus agar admin tidak perlu klik
                $('#scan_input').focus();
                
                // Pastikan kursor tidak hilang saat klik area kosong
                $(document).click(function() { 
                    $('#scan_input').focus(); 
                });

                $('#scan_input').on('keypress', function(e) {
                    if(e.which == 13) { // Enter Key
                        let bukuId = $(this).val();
                        if(bukuId.length > 0) {
                            processReturn(bukuId);
                        }
                        $(this).val(''); // Reset input
                    }
                });

                function processReturn(id) {
                    // Tampilkan Loading
                    $('#result_area').addClass('hidden');
                    
                    $.ajax({
                        url: "{{ route('pengembalian.process') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            buku_id: id
                        },
                        success: function(res) {
                            $('#result_area').removeClass('hidden');

                            if(res.status == 'success') {
                                // Style Sukses
                                $('#alert_box').removeClass('bg-red-100 text-red-800').addClass('bg-green-100 text-green-800');
                                $('#status_title').text('BERHASIL!');
                                $('#status_desc').text(res.message);

                                // Isi Data
                                $('#res_mhs').text(res.data.mahasiswa);
                                $('#res_buku').text(res.data.judul);

                                if(res.data.terlambat) {
                                    $('#res_denda').html('<span class="text-red-600">Terlambat! Denda: Rp ' + res.data.denda.toLocaleString('id-ID') + '</span>');
                                    // Mainkan Suara Warning (Opsional)
                                } else {
                                    $('#res_denda').html('<span class="text-green-600">Tepat Waktu (Tanpa Denda)</span>');
                                }

                            } else {
                                // Style Error
                                $('#alert_box').removeClass('bg-green-100 text-green-800').addClass('bg-red-100 text-red-800');
                                $('#status_title').text('GAGAL!');
                                $('#status_desc').text(res.message);
                                $('#res_mhs').text('-');
                                $('#res_buku').text('-');
                                $('#res_denda').text('-');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan sistem.');
                        }
                    });
                }
            });
        </script>
    </x-slot:script>
</x-layout>