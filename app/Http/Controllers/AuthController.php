<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// PERBAIKAN: Tambahkan library pendukung untuk fitur reset password
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // PERBAIKAN: Diubah menjadi 'true' agar selalu mengingat login meskipun server mati mendadak
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->aktif) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dokter.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu.');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (User::where('role', 'admin')->exists()) {
            abort(403);
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'aktif'    => true,
        ]);

        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Akun admin berhasil dibuat!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // =========================================================================
    // FITUR TAMBAHAN: HANDLER LUPA PASSWORD (KIRIM EMAIL VIA GMAIL)
    // =========================================================================

    // 1. Menampilkan Form Input Email Lupa Password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // 2. Memproses Pengiriman Link Token ke Gmail Penerima
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Mengirimkan link via Mailer SMTP yang dikonfigurasi di .env
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password sudah dikirim ke Gmail kamu!');
        }

        return back()->withErrors(['email' => 'Gagal mengirimkan email reset password.']);
    }

    // 3. Menampilkan Form untuk Bikin Password Baru (Diakses dari Link Gmail)
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // 4. Mengeksekusi Update Password Baru ke Database
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Melakukan update password baru menggunakan token pencocokan token bawaan Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');
        }

        return back()->withErrors(['email' => 'Token kedaluwarsa atau tidak valid.']);
    }
}