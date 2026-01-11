<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

// --> Import Class OpenSpout
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Common\Entity\Row;

class MahasiswaController extends Controller
{
    // 1. Menampilkan Halaman & JSON DataTables
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Mahasiswa::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button onclick="editData('.$row->id.')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded mr-1 text-sm">Edit</button>';
                    $btn .= '<button onclick="deleteData('.$row->id.')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('mahasiswa.index');
    }

    // 2. Simpan Data (Create/Update)
    public function store(Request $request)
    {
        // Validasi sederhana, bisa dikembangkan
        $request->validate([
            'nim'      => 'required|unique:mahasiswas,nim,' . $request->id,
            'nama'     => 'required',
            'jk'       => 'required',
            'kelas'    => 'required',
            'angkatan' => 'required',
            'jurusan'  => 'required',
            'fakultas' => 'required',
        ]);

        Mahasiswa::updateOrCreate(
            ['id' => $request->id], // Kunci pencarian (jika ada ID, update. Jika null, create)
            [
                'nim'      => $request->nim,
                'nama'     => $request->nama,
                'jk'       => $request->jk,
                'kelas'    => $request->kelas,
                'angkatan' => $request->angkatan,
                'jurusan'  => $request->jurusan,
                'fakultas' => $request->fakultas
            ]
        );

        return response()->json(['success' => 'Data berhasil disimpan.']);
    }

    // 3. Ambil Data Satuan untuk Edit
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        return response()->json($mahasiswa);
    }

    // 4. Hapus Data
    public function destroy($id)
    {
        Mahasiswa::find($id)->delete();
        return response()->json(['success' => 'Data berhasil dihapus.']);
    }
    public function exportPdf()
{
    // Ambil semua data mahasiswa
    $data = Mahasiswa::orderBy('nim', 'asc')->get();

    // Load view khusus PDF (bukan view index yang ada tombol edit/hapus)
    $pdf = Pdf::loadView('mahasiswa.pdf', ['data' => $data]);

    // Set ukuran kertas dan orientasi (Opsional)
    $pdf->setPaper('A4', 'landscape');

    // Download file
    return $pdf->download('laporan-data-mahasiswa.pdf');
}

// 1. FUNGSI EXPORT EXCEL (STREAMING)
    public function exportExcel()
    {
        return response()->streamDownload(function () {
            $writer = new Writer();
            
            // Tulis langsung ke output stream (browser download)
            $writer->openToFile('php://output');

            // Bikin Header
            $header = Row::fromValues(['NIM', 'Nama', 'L/P', 'Kelas', 'Angkatan', 'Jurusan', 'Fakultas']);
            $writer->addRow($header);

            // Ambil data (gunakan cursor() agar hemat memori untuk data besar)
            foreach (Mahasiswa::cursor() as $mhs) {
                $row = Row::fromValues([
                    $mhs->nim,
                    $mhs->nama,
                    $mhs->jk,
                    $mhs->kelas,
                    $mhs->angkatan,
                    $mhs->jurusan,
                    $mhs->fakultas,
                ]);
                $writer->addRow($row);
            }

            $writer->close();
        }, 'data-mahasiswa.xlsx');
    }

    // 2. FUNGSI IMPORT EXCEL
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
                // Skip baris pertama (Header)
                if ($index === 1) continue; 

                $cells = $row->getCells();
                
                // Pastikan baris tidak kosong
                if (count($cells) < 7) continue;

                // Mapping kolom (0=NIM, 1=Nama, dst sesuai urutan Excel)
                // Kita pakai updateOrCreate agar kalau NIM sudah ada, dia update. Kalau belum, dia create.
                Mahasiswa::updateOrCreate(
                    ['nim' => $cells[0]->getValue()], 
                    [
                        'nama'     => $cells[1]->getValue(),
                        'jk'       => $cells[2]->getValue(),
                        'kelas'    => $cells[3]->getValue(),
                        'angkatan' => $cells[4]->getValue(),
                        'jurusan'  => $cells[5]->getValue(),
                        'fakultas' => $cells[6]->getValue(),
                    ]
                );
                $count++;
            }
            // Hanya proses sheet pertama
            break; 
        }

        $reader->close();

        return response()->json(['success' => "$count Data berhasil diimport!"]);
    }
    public function cetakKartu($id)
    {
        $mahasiswa = \App\Models\Mahasiswa::find($id);
        
        // Kita return view khusus kartu (bukan PDF download, tapi Halaman Print)
        return view('mahasiswa.kartu', compact('mahasiswa'));
    }
}