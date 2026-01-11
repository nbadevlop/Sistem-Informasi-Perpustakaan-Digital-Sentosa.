<x-layout>
    <x-slot:title>Laporan Keuangan Denda</x-slot:title>

    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Denda</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white p-5 rounded-lg shadow lg:col-span-1">
            <h3 class="font-bold text-gray-700 mb-3 uppercase text-sm border-b pb-2">Filter Periode</h3>
            <form action="{{ route('laporan.denda') }}" method="GET">
                <div class="mb-3">
                    <label class="text-xs font-bold text-gray-500">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border rounded p-2 text-sm">
                </div>
                <div class="mb-4">
                    <label class="text-xs font-bold text-gray-500">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border rounded p-2 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm font-bold">Tampilkan</button>
                    <a href="{{ route('laporan.denda.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm font-bold text-center flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-indigo-500">
                <p class="text-gray-500 text-xs font-bold uppercase">Total Tagihan Denda</p>
                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
            </div>
            
            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
                <p class="text-gray-500 text-xs font-bold uppercase">Uang Masuk (Lunas)</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Masuk ke Kas</p>
            </div>

            <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-500">
                <p class="text-gray-500 text-xs font-bold uppercase">Tertunggak (Belum Lunas)</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalTertunggak, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Harus ditagih</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 bg-gray-50 border-b font-bold text-gray-700">Rincian Transaksi Denda</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">Tgl Kembali</th>
                        <th class="px-6 py-3">Nama Mahasiswa</th>
                        <th class="px-6 py-3">Buku</th>
                        <th class="px-6 py-3 text-right">Nominal Denda</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $row->mahasiswa->nama ?? '-' }} <br> <span class="text-xs font-normal text-gray-400">{{ $row->mahasiswa->nim ?? '-' }}</span></td>
                        <td class="px-6 py-4">{{ $row->buku->judul ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-700">Rp {{ number_format($row->denda, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($row->status_denda == 'Lunas')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Lunas</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Tidak ada data denda pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layout>