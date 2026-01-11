<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('peminjamen', function (Blueprint $table) { // Cek nama tabel di file migrasi Anda
        $table->id();
        // Foreign Key ke Mahasiswa
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
        // Foreign Key ke Buku
        $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
        
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali')->nullable(); // Boleh kosong jika belum kembali
        $table->enum('status', ['Dipinjam', 'Dikembalikan'])->default('Dipinjam');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
