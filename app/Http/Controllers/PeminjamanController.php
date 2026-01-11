<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Mahasiswa;
use App\Models\Buku;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // 1. Tampilkan Halaman & JSON DataTables
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Tampilkan data peminjaman (termasuk buku & mahasiswa yg dihapus)
            $data = Peminjaman::with([
                'mahasiswa' => function($query) { $query->withTrashed(); }, 
                'buku' => function($query) { $query->withTrashed(); }
            ])->latest()->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_mahasiswa', function($row){
                    return $row->mahasiswa->nama ?? '-';
                })
                ->addColumn('judul_buku', function($row){
                    return $row->buku->judul ?? '-'; 
                })
                ->addColumn('denda_info', function($row){
                    if ($row->denda > 0) {
                        $status = $row->status_denda == 'Lunas' 
                            ? '<span class="text-xs bg-green-100 text-green-800 px-2 rounded-full ml-1">Lunas</span>' 
                            : '<span class="text-xs bg-red-100 text-red-800 px-2 rounded-full ml-1">Belum Lunas</span>';
                        
                        return '<div class="text-sm font-bold text-red-600">Rp ' . number_format($row->denda, 0, ',', '.') . '</div>' . $status;
                    }
                    return '-';
                })
                ->addColumn('action', function($row){
                    if($row->status == 'Dikembalikan') {
                         if ($row->denda > 0 && $row->status_denda != 'Lunas') {
                            return '<button onclick="bayarDenda('.$row->id.', '.$row->denda.')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded text-sm">Bayar</button>';
                        }
                        return '<span class="text-gray-400 text-xs italic">Selesai</span>';
                    }
                    return '<button onclick="editData('.$row->id.')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded mr-1 text-sm">Kembalikan</button>';
                })
                ->rawColumns(['denda_info', 'action'])
                ->make(true);
        }

        // --- PERBAIKAN DISINI ---
        // Tambahkan withTrashed() agar Mahasiswa & Buku yg dihapus TETAP MUNCUL di Dropdown
        $mahasiswas = Mahasiswa::withTrashed()->orderBy('nama', 'asc')->get();
        $bukus = Buku::withTrashed()->orderBy('judul', 'asc')->get(); 
        
        return view('peminjaman.index', compact('mahasiswas', 'bukus'));
    }

    // 2. Simpan Data (Pinjam / Kembali)
    public function store(Request $request)
    {
        $rules = [
            'status' => 'required', 
        ];

        if ($request->id == null) {
            $rules['mahasiswa_id'] = 'required';
            $rules['buku_id']      = 'required';
            $rules['tanggal_pinjam'] = 'required|date';
        }

        $request->validate($rules);

        // LOGIKA: PINJAM BARU
        if ($request->id == null) {
            $buku = Buku::find($request->buku_id);
            if($buku->jumlah > 0) {
                $buku->decrement('jumlah');
            } else {
                return response()->json(['error' => 'Stok buku habis!'], 422);
            }

            $tglPinjam = Carbon::parse($request->tanggal_pinjam);
            
            Peminjaman::create([
                'mahasiswa_id'   => $request->mahasiswa_id,
                'buku_id'        => $request->buku_id,
                'tanggal_pinjam' => $tglPinjam->format('Y-m-d'),
                'tanggal_kembali'=> null, 
                'status'         => 'Dipinjam',
                'denda'          => 0,
                'status_denda'   => 'Belum Lunas'
            ]);
        } 
        // LOGIKA: PENGEMBALIAN
        else {
            $peminjaman = Peminjaman::find($request->id);

            if($peminjaman->status == 'Dipinjam' && $request->status == 'Dikembalikan') {
                 // Cari buku meskipun sudah dihapus
                 $buku = Buku::withTrashed()->find($peminjaman->buku_id);
                 if($buku) {
                     $buku->increment('jumlah');
                 }
            }

            $peminjaman->update([
                'status' => $request->status,
                'denda'  => $request->denda ?? 0, 
                'tanggal_kembali' => $request->tanggal_kembali,
                'status_denda' => ($request->denda > 0) ? 'Belum Lunas' : 'Lunas'
            ]);
        }

        return response()->json(['success' => 'Transaksi berhasil disimpan.']);
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::find($id);
        return response()->json($peminjaman);
    }
    
    public function bayarDenda(Request $request)
    {
        $id = $request->id;
        $peminjaman = Peminjaman::find($id);

        if($peminjaman) {
            $peminjaman->update([
                'status_denda' => 'Lunas',
                'tanggal_pembayaran' => now()
            ]);
            return response()->json(['success' => 'Denda berhasil dilunasi!']);
        }
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function exportPdf()
    {
        // Pastikan export juga menyertakan data yang terhapus
        $data = Peminjaman::with([
            'mahasiswa' => function($q){ $q->withTrashed(); }, 
            'buku' => function($q){ $q->withTrashed(); }
        ])->latest()->get();

        $pdf = Pdf::loadView('peminjaman.pdf', ['data' => $data]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-peminjaman.pdf');
    }

    // -- Fitur Pengembalian Cepat (Scanner) --
    public function indexPengembalian()
    {
        return view('peminjaman.pengembalian');
    }

    public function processPengembalian(Request $request)
    {
        $bukuId = $request->buku_id;
        $peminjaman = Peminjaman::where('buku_id', $bukuId)->where('status', 'Dipinjam')->first();

        if (!$peminjaman) {
            return response()->json(['status' => 'error', 'message' => 'Buku ini tidak sedang dipinjam.']);
        }

        $today = now()->format('Y-m-d');
        $jatuhTempo = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->addDays(7);
        $tglKembali = \Carbon\Carbon::parse($today);
        $denda = 0;
        
        if ($tglKembali->gt($jatuhTempo)) {
            $diffDays = $tglKembali->diffInDays($jatuhTempo);
            $denda = $diffDays * 1000; 
        }

        $peminjaman->update([
            'status' => 'Dikembalikan',
            'tanggal_kembali' => $today,
            'denda' => $denda,
            'status_denda' => ($denda > 0) ? 'Belum Lunas' : 'Lunas'
        ]);

        $buku = Buku::withTrashed()->find($peminjaman->buku_id);
        if($buku) $buku->increment('jumlah');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku Berhasil Dikembalikan!',
            'data' => [
                'mahasiswa' => $peminjaman->mahasiswa->nama, // Nama akan muncul otomatis karena relasi model sudah pakai withTrashed
                'judul' => $buku->judul ?? '-',
                'denda' => $denda,
                'terlambat' => ($denda > 0)
            ]
        ]);
    }
}