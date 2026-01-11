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
    Schema::create('mahasiswas', function (Blueprint $table) {
        $table->id();
        $table->string('nim')->unique();
        $table->string('nama');
        $table->enum('jk', ['L', 'P']); // L = Laki-laki, P = Perempuan
        $table->string('kelas');
        $table->year('angkatan');
        $table->string('jurusan');
        $table->string('fakultas');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
