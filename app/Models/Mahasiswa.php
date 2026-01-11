<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- 1. Import Ini

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'nim', 'nama', 'jk', 'kelas', 'angkatan', 'jurusan', 'fakultas'
    ];
}
