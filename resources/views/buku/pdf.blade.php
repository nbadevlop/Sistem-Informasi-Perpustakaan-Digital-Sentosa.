<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Buku</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN DATA BUKU PERPUSTAKAAN</h1>
        <p>Dicetak Tanggal: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 25%">Judul Buku</th>
                <th style="width: 20%">Penulis</th>
                <th class="text-center" style="width: 10%">Tahun</th>
                <th style="width: 15%">Penerbit</th>
                <th style="width: 15%">Kota</th>
                <th class="text-center" style="width: 10%">Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $buku)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->penulis }}</td>
                <td class="text-center">{{ $buku->tahun }}</td>
                <td>{{ $buku->penerbit }}</td>
                <td>{{ $buku->kota }}</td>
                <td class="text-center">{{ $buku->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>