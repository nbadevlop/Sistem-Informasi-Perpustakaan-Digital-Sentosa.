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
    Schema::table('peminjamen', function (Blueprint $table) {
        // Enum: Lunas / Belum Lunas. Default 'Belum Lunas'
        $table->enum('status_denda', ['Lunas', 'Belum Lunas'])->default('Belum Lunas')->after('denda');
        $table->date('tanggal_pembayaran')->nullable()->after('status_denda');
    });
}

public function down(): void
{
    Schema::table('peminjamen', function (Blueprint $table) {
        $table->dropColumn(['status_denda', 'tanggal_pembayaran']);
    });
}
};
