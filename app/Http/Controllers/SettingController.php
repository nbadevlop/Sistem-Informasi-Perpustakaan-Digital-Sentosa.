<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil setting 'app_name'
        $setting = Setting::where('key', 'app_name')->first();
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        Setting::updateOrCreate(
            ['key' => 'app_name'],
            ['value' => $request->app_name]
        );

        return back()->with('success', 'Nama Perpustakaan berhasil diperbarui!');
    }
}