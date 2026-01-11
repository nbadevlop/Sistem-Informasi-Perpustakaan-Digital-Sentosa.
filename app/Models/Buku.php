<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- 1. Import Ini

class Buku extends Model
{
    use HasFactory, SoftDeletes; // <--- 2. Tambahkan Ini
    
    protected $fillable = [
        'kategori_id', 'judul', 'penulis', 'tahun', 'penerbit', 'kota', 'jumlah'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}