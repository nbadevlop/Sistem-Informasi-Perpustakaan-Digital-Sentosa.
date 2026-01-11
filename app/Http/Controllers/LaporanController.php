<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default tanggal: Awal bulan ini s/d Hari ini
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date ?? Carbon::now()->format('Y-m-d');

        // Ambil Data Peminjaman yang ADA DENDA-nya dalam rentang tanggal kembali
        $data = Peminjaman::with(['mahasiswa', 'buku' => function($q){ $q->withTrashed(); }])
            ->where('denda', '>', 0) // Hanya yang punya denda
            ->whereBetween('tanggal_kembali', [$startDate, $endDate])
            ->latest('tanggal_kembali')
            ->get();

        // Hitung Ringkasan
        $totalDenda      = $data->sum('denda');
        $totalDiterima   = $data->where('status_denda', 'Lunas')->sum('denda');
        $totalTertunggak = $data->where('status_denda', '!=', 'Lunas')->sum('denda');

        return view('laporan.index', compact('data', 'startDate', 'endDate', 'totalDenda', 'totalDiterima', 'totalTertunggak'));
    }

    public function cetakPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $data = Peminjaman::with(['mahasiswa', 'buku' => function($q){ $q->withTrashed(); }])
            ->where('denda', '>', 0)
            ->whereBetween('tanggal_kembali', [$startDate, $endDate])
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        $totalDenda      = $data->sum('denda');
        $totalDiterima   = $data->where('status_denda', 'Lunas')->sum('denda');
        $totalTertunggak = $data->where('status_denda', '!=', 'Lunas')->sum('denda');

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'startDate', 'endDate', 'totalDenda', 'totalDiterima', 'totalTertunggak'));
        return $pdf->download('laporan-keuangan-denda.pdf');
    }
}