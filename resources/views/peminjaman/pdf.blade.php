<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        .text-center { text-align: center; }
        .badge { 
            padding: 2px 5px; border-radius: 3px; font-size: 10px; color: black;
        }
        .bg-yellow { background-color: #fff3cd; } /* Warna Kuning soft */
        .bg-green { background-color: #d1e7dd; } /* Warna Hijau soft */
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Transaksi Peminjaman Buku</h1>
        <p>Sistem Informasi Akademik & Perpustakaan</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Mahasiswa</th>
                <th style="width: 25%">Judul Buku</th>
                <th style="width: 12%">Tgl Pinjam</th>
                <th style="width: 12%">Tgl Kembali</th>
                <th style="width: 10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    {{ $row->mahasiswa->nama ?? '-' }} <br>
                    <small style="color: grey">({{ $row->mahasiswa->nim ?? '' }})</small>
                </td>
                <td>{{ $row->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ $row->tanggal_pinjam }}</td>
                <td class="text-center">{{ $row->tanggal_kembali ?? '-' }}</td>
                <td class="text-center">
                    @if($row->status == 'Dipinjam')
                        <span class="badge bg-yellow">Dipinjam</span>
                    @else
                        <span class="badge bg-green">Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>