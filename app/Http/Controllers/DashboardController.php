<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Buku; // <--- Pastikan model Buku diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman; // <--- Import Model Peminjaman

class DashboardController extends Controller
{
    public function index()
    {
        // --- STATISTIK MAHASISWA ---
        // 1. Grafik Pie (Fakultas)
        $fakultasData = Mahasiswa::select('fakultas', DB::raw('count(*) as total'))
            ->groupBy('fakultas')->pluck('total', 'fakultas');

        // 2. Grafik Bar (Jurusan - Top 10)
        $jurusanData = Mahasiswa::select('jurusan', DB::raw('count(*) as total'))
            ->groupBy('jurusan')->orderBy('total', 'desc')->limit(10)->pluck('total', 'jurusan');

        // 3. Grafik Bar (Angkatan)
        $angkatanData = Mahasiswa::select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')->orderBy('angkatan', 'asc')->pluck('total', 'angkatan');

        // 4. Kartu Ringkasan Mahasiswa
        $totalMahasiswa = Mahasiswa::count();
        $totalLaki      = Mahasiswa::where('jk', 'L')->count();
        $totalPerempuan = Mahasiswa::where('jk', 'P')->count();

        // --- STATISTIK PERPUSTAKAAN ---
        $totalJudulBuku = Buku::count();
        $totalStokBuku  = Buku::sum('jumlah');

        // ==============================
        // 3. STATISTIK PEMINJAMAN (BARU)
        // ==============================
        
        // A. Status Peminjaman (Dipinjam vs Dikembalikan)
        $statusPeminjaman = Peminjaman::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // B. Tren Peminjaman per Bulan (Tahun Ini)
        // Menggunakan DATE_FORMAT untuk MySQL (Format: NamaBulan)
        $trenPeminjaman = Peminjaman::select(DB::raw("DATE_FORMAT(tanggal_pinjam, '%M') as bulan"), DB::raw('count(*) as total'))
            ->whereYear('tanggal_pinjam', date('Y'))
            ->groupBy('bulan')
            ->orderByRaw("MIN(tanggal_pinjam)") // Urutkan berdasarkan bulan kalender
            ->pluck('total', 'bulan');

        // C. Top 5 Buku Paling Sering Dipinjam
        $topBuku = DB::table('peminjamen')
            ->join('bukus', 'peminjamen.buku_id', '=', 'bukus.id')
            ->select('bukus.judul', DB::raw('count(*) as total'))
            ->groupBy('bukus.judul')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'bukus.judul');

        // D. Top 5 Mahasiswa Paling Rajin
        $topMahasiswa = DB::table('peminjamen')
            ->join('mahasiswas', 'peminjamen.mahasiswa_id', '=', 'mahasiswas.id')
            ->select('mahasiswas.nama', DB::raw('count(*) as total'))
            ->groupBy('mahasiswas.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'mahasiswas.nama');

        // --- RETURN KE VIEW (HANYA SEKALI DI AKHIR) ---
        return view('dashboard.index', compact(
'fakultasData', 'jurusanData', 'angkatanData', 
            'totalMahasiswa', 'totalLaki', 'totalPerempuan',
            'totalJudulBuku', 'totalStokBuku',
            'statusPeminjaman', 'trenPeminjaman', 'topBuku', 'topMahasiswa' // <--- Data Baru
        ));
    }
}