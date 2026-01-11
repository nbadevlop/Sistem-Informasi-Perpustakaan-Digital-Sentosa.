<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse; /* Agar garis tabel menyatu */
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2; /* Warna abu muda untuk header tabel */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping */
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN DATA MAHASISWA</h1>
        <p>Universitas Contoh Laravel 12</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">NIM</th>
                <th style="width: 20%">Nama</th>
                <th style="width: 5%">L/P</th>
                <th style="width: 10%">Kelas</th>
                <th style="width: 10%">Angkatan</th>
                <th style="width: 15%">Jurusan</th>
                <th style="width: 20%">Fakultas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $mhs)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->nama }}</td>
                <td style="text-align: center">{{ $mhs->jk }}</td>
                <td style="text-align: center">{{ $mhs->kelas }}</td>
                <td style="text-align: center">{{ $mhs->angkatan }}</td>
                <td>{{ $mhs->jurusan }}</td>
                <td>{{ $mhs->fakultas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>