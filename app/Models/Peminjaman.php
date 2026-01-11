<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id', 'buku_id', 'tanggal_pinjam', 
        'tanggal_kembali', 'status', 'denda', 
        'status_denda', 'tanggal_pembayaran'
    ];

    // --- UPDATE BAGIAN INI ---
    public function mahasiswa()
    {
        // Tambahkan ->withTrashed() agar nama mahasiswa yg dihapus tetap muncul di history
        return $this->belongsTo(Mahasiswa::class)->withTrashed();
    }

    public function buku()
    {
        // Ini yang tadi sudah kita tambahkan untuk buku
        return $this->belongsTo(Buku::class)->withTrashed(); 
    }
}