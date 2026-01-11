<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class WelcomeController extends Controller
{
    // 1. Tampilkan Halaman Depan
    public function index()
    {
        // Tampilkan 10 buku terbaru sebagai default
        $latestBooks = Buku::with('kategori')->latest()->take(8)->get();
        return view('welcome', compact('latestBooks'));
    }

    // 2. API Live Search (Dipanggil via AJAX)
    public function search(Request $request)
    {
        $query = $request->keyword;

        if(!$query) {
            return response()->json([]);
        }

        $books = Buku::with('kategori')
            ->where('judul', 'like', "%{$query}%")
            ->orWhere('penulis', 'like', "%{$query}%")
            ->orWhere('penerbit', 'like', "%{$query}%")
            ->limit(20) // Batasi hasil agar tidak berat
            ->get();

        return response()->json($books);
    }
}