<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Denda</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { margin-top: 20px; width: 40%; float: right; }
        .summary table td { border: none; padding: 4px; }
        .summary .total { font-weight: bold; border-top: 1px solid #333; }
        .lunas { color: green; font-weight: bold; }
        .belum { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan Denda</h1>
        <p>{{ config('app.name') }}</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tgl Kembali</th>
                <th style="width: 25%">Mahasiswa</th>
                <th style="width: 30%">Buku</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%" class="text-right">Denda (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach($data as $row)
            <tr>
                <td class="text-center">{{ $i++ }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y') }}</td>
                <td>{{ $row->mahasiswa->nama ?? '-' }}<br><small>{{ $row->mahasiswa->nim ?? '' }}</small></td>
                <td>{{ $row->buku->judul ?? '-' }}</td>
                <td class="text-center">
                    <span class="{{ $row->status_denda == 'Lunas' ? 'lunas' : 'belum' }}">
                        {{ $row->status_denda }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($row->denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Total Tagihan Denda</td>
                <td class="text-right">: Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Sudah Diterima (Lunas)</td>
                <td class="text-right lunas">: Rp {{ number_format($totalDiterima, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Tertunggak (Belum Lunas)</td>
                <td class="text-right belum">: Rp {{ number_format($totalTertunggak, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>