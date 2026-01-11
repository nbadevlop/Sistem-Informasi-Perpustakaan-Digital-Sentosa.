<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Bagian ini PENTING agar data bisa disimpan!
    protected $fillable = [
        'nama_kategori', 
        'lokasi_rak'
    ];

    // Relasi ke Buku
    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}