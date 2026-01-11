<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Common\Entity\Row;

class BukuController extends Controller
{
    // 1. Tampilkan Halaman & JSON DataTables
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager load kategori agar query ringan
            $data = Buku::with('kategori')->latest()->get(); 
            
            return DataTables::of($data)
                ->addIndexColumn()
                // Kolom Kategori & Rak
                ->addColumn('kategori_rak', function($row){
                    if($row->kategori) {
                        return $row->kategori->nama_kategori . ' <br><small class="text-gray-500">(' . $row->kategori->lokasi_rak . ')</small>';
                    }
                    return '<span class="text-red-500 text-xs">Belum set</span>';
                })
                // --- BAGIAN INI YANG SEBELUMNYA HILANG ---
                ->addColumn('action', function($row){
                    $btn = '<button onclick="editData('.$row->id.')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded mr-1 text-sm">Edit</button>';
                    $btn .= '<button onclick="deleteData('.$row->id.')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">Hapus</button>';
                    return $btn;
                })
                // ------------------------------------------
                ->rawColumns(['kategori_rak', 'action']) // Pastikan 'action' masuk di sini juga
                ->make(true);
        }
        
        // Kirim data kategori untuk dropdown di modal
        $kategoris = \App\Models\Kategori::all(); 
        
        return view('buku.index', compact('kategoris'));
    }

    // 2. Simpan Data (Create / Update)
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'judul'    => 'required',
            'penulis'  => 'required',
            'tahun'    => 'required|digits:4',
            'penerbit' => 'required',
            'kota'     => 'required',
            'jumlah'   => 'required|integer|min:0',
        ]);

        Buku::updateOrCreate(
            ['id' => $request->id],
            [
                'kategori_id' => $request->kategori_id,
                'judul'    => $request->judul,
                'penulis'  => $request->penulis,
                'tahun'    => $request->tahun,
                'penerbit' => $request->penerbit,
                'kota'     => $request->kota,
                'jumlah'   => $request->jumlah
            ]
        );

        return response()->json(['success' => 'Data buku berhasil disimpan.']);
    }

    // 3. Ambil Data Satuan untuk Edit
    public function edit($id)
    {
        $buku = Buku::find($id);
        return response()->json($buku);
    }

    // 4. Hapus Data
    public function destroy($id)
    {
        Buku::find($id)->delete();
        return response()->json(['success' => 'Data buku berhasil dihapus.']);
    }

    // 5. Export PDF
    public function exportPdf()
    {
        $data = Buku::with('kategori')->orderBy('judul', 'asc')->get();
        $pdf = Pdf::loadView('buku.pdf', ['data' => $data]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-data-buku.pdf');
    }

    // 6. Export Excel
    public function exportExcel()
    {
        return response()->streamDownload(function () {
            $writer = new Writer();
            $writer->openToFile('php://output');

            $header = Row::fromValues(['Judul', 'Penulis', 'Tahun', 'Penerbit', 'Kota', 'Stok', 'Kategori', 'Rak']);
            $writer->addRow($header);

            foreach (Buku::with('kategori')->cursor() as $buku) {
                $namaKategori = $buku->kategori ? $buku->kategori->nama_kategori : '-';
                $lokasiRak    = $buku->kategori ? $buku->kategori->lokasi_rak : '-';

                $row = Row::fromValues([
                    $buku->judul,
                    $buku->penulis,
                    $buku->tahun,
                    $buku->penerbit,
                    $buku->kota,
                    $buku->jumlah,
                    $namaKategori,
                    $lokasiRak
                ]);
                $writer->addRow($row);
            }

            $writer->close();
        }, 'data-buku.xlsx');
    }

    // 7. Import Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx'
        ]);

        $file = $request->file('file_excel');
        $reader = new Reader();
        $reader->open($file->getRealPath());

        $count = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index === 1) continue; // Skip Header

                $cells = $row->getCells();
                if (count($cells) < 6) continue;

                // Pastikan Kategori diset NULL jika tidak ada info di Excel
                // Atau Anda bisa tambahkan logika pencarian kategori berdasarkan nama di sini
                
                Buku::updateOrCreate(
                    [
                        'judul'   => $cells[0]->getValue(),
                        'penulis' => $cells[1]->getValue()
                    ], 
                    [
                        'tahun'    => $cells[2]->getValue(),
                        'penerbit' => $cells[3]->getValue(),
                        'kota'     => $cells[4]->getValue(),
                        'jumlah'   => $cells[5]->getValue(),
                        // Default ke kategori ID 1 jika ada, atau biarkan null
                        // 'kategori_id' => 1 
                    ]
                );
                $count++;
            }
            break; 
        }

        $reader->close();
        return response()->json(['success' => "$count Data Buku berhasil diimport!"]);
    }
    public function cetakLabel($id)
    {
        // Ambil data buku beserta kategorinya (untuk info Rak)
        $buku = \App\Models\Buku::with('kategori')->find($id);
        return view('buku.label', compact('buku'));
    }
}