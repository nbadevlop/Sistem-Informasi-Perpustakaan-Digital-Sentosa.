<?php

use Illuminate\Support\Facades\Route;

// --- IMPORT SEMUA CONTROLLER ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIK (Bisa diakses tanpa login)
// ==========================================
// Halaman Depan / Pencarian Buku
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/search-books', [WelcomeController::class, 'search'])->name('books.search');
// ==========================================
// 2. ROUTE TAMU (Hanya untuk yang BELUM login)
// ==========================================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});


// ==========================================
// 3. ROUTE AMAN (Hanya untuk yang SUDAH login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // --- Logout ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Fitur Ganti Password ---
    Route::get('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

    // --- Fitur Pengaturan Aplikasi (Ganti Nama) ---
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

    // --- Manajemen Mahasiswa ---
    Route::controller(MahasiswaController::class)->group(function(){
        Route::get('mahasiswa', 'index')->name('mahasiswa.index');
        Route::post('mahasiswa', 'store')->name('mahasiswa.store');
        Route::get('mahasiswa/{id}/kartu', 'cetakKartu')->name('mahasiswa.cetakKartu');
        // Export & Import
        Route::get('mahasiswa/export-pdf', 'exportPdf')->name('mahasiswa.export-pdf');
        Route::get('mahasiswa/export-excel', 'exportExcel')->name('mahasiswa.export-excel');
        Route::post('mahasiswa/import-excel', 'importExcel')->name('mahasiswa.import-excel');
        
        // Edit & Hapus
        Route::get('mahasiswa/{id}/edit', 'edit')->name('mahasiswa.edit');
        Route::delete('mahasiswa/{id}', 'destroy')->name('mahasiswa.destroy');
    });

    // --- Manajemen Kategori & Rak ---
    Route::controller(KategoriController::class)->group(function(){
        Route::get('kategori', 'index')->name('kategori.index');
        Route::post('kategori', 'store')->name('kategori.store');
        Route::get('kategori/{id}/edit', 'edit')->name('kategori.edit');
        Route::delete('kategori/{id}', 'destroy')->name('kategori.destroy');
    });

    // --- Manajemen Buku ---
    Route::controller(BukuController::class)->group(function(){
        Route::get('buku', 'index')->name('buku.index');
        Route::post('buku', 'store')->name('buku.store');
        Route::get('buku/{id}/label', 'cetakLabel')->name('buku.cetakLabel');
        // Export & Import
        Route::get('buku/export-pdf', 'exportPdf')->name('buku.export-pdf');
        Route::get('buku/export-excel', 'exportExcel')->name('buku.export-excel');
        Route::post('buku/import-excel', 'importExcel')->name('buku.import-excel');
        
        // Edit & Hapus
        Route::get('buku/{id}/edit', 'edit')->name('buku.edit');
        Route::delete('buku/{id}', 'destroy')->name('buku.destroy');
    });
    // --> ROUTE LAPORAN KEUANGAN
    Route::controller(\App\Http\Controllers\LaporanController::class)->group(function(){
        Route::get('laporan/denda', 'index')->name('laporan.denda');
        Route::get('laporan/denda/pdf', 'cetakPdf')->name('laporan.denda.pdf');
    });

    // --- Transaksi Peminjaman ---
    Route::controller(PeminjamanController::class)->group(function(){
        Route::get('peminjaman', 'index')->name('peminjaman.index');
        Route::post('peminjaman', 'store')->name('peminjaman.store');
        Route::get('/pengembalian-cepat', [PeminjamanController::class, 'indexPengembalian'])->name('pengembalian.index');
    Route::post('/pengembalian-cepat', [PeminjamanController::class, 'processPengembalian'])->name('pengembalian.process');
        // Fitur Tambahan Peminjaman
        Route::get('peminjaman/export-pdf', 'exportPdf')->name('peminjaman.export-pdf');
        Route::post('peminjaman/bayar', 'bayarDenda')->name('peminjaman.bayar'); // Bayar Denda
        
        // Edit (Pengembalian)
        Route::get('peminjaman/{id}/edit', 'edit')->name('peminjaman.edit');
        
        // Delete dinonaktifkan demi keamanan stok & history
        // Route::delete('peminjaman/{id}', 'destroy')->name('peminjaman.destroy'); 
    });

});