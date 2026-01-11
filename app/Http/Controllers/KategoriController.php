<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::latest()->get();
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
        return view('kategori.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'lokasi_rak'    => 'required',
        ]);

        Kategori::updateOrCreate(
            ['id' => $request->id],
            [
                'nama_kategori' => $request->nama_kategori,
                'lokasi_rak'    => $request->lokasi_rak
            ]
        );

        return response()->json(['success' => 'Kategori berhasil disimpan.']);
    }

    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return response()->json($kategori);
    }

    public function destroy($id)
    {
        Kategori::find($id)->delete();
        return response()->json(['success' => 'Kategori berhasil dihapus.']);
    }
}