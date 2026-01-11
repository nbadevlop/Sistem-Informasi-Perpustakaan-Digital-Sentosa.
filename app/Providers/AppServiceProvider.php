<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import View
use Illuminate\Support\Facades\Schema; // Import Schema
use App\Models\Setting; // Import Model

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Cek dulu apakah tabel settings sudah ada (agar tidak error saat migrate fresh)
        if (Schema::hasTable('settings')) {
            // Ambil nama aplikasi dari DB, kalau tidak ada pakai default 'Laravel'
            $appName = Setting::where('key', 'app_name')->value('value') ?? 'Perpustakaan Digital';
            
            // Share ke semua view dengan nama variabel $globalAppName
            View::share('globalAppName', $appName);
        }
    }
}