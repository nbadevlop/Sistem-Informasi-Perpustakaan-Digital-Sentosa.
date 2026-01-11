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
        // Default 0 (tidak ada denda)
        $table->integer('denda')->default(0)->after('status'); 
    });
}

public function down(): void
{
    Schema::table('peminjamen', function (Blueprint $table) {
        $table->dropColumn('denda');
    });
}
};
