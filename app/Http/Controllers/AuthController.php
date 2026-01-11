<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <--- PENTING: Import Hash
use App\Models\User;

class AuthController extends Controller
{
    // ... (Method index, login, logout yang lama BIARKAN SAJA) ...

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ==========================================
    // TAMBAHKAN 2 METHOD BARU INI DI BAWAH SINI
    // ==========================================

    // 1. Tampilkan Form Ganti Password
    public function changePassword()
    {
        return view('auth.change-password');
    }

    // 2. Proses Update Password
    public function updatePassword(Request $request)
    {
        // Validasi Input
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed', // 'confirmed' otomatis cek field new_password_confirmation
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min'       => 'Password baru minimal 6 karakter.'
        ]);

        // Cek apakah password lama benar?
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        // Update Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diganti!');
    }
}