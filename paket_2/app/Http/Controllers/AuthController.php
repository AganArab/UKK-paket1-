<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login pengguna
     */
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Cari user di database
        $user = DB::table('users')
            ->where('email', $validated['email'])
            ->where('status_aktif', 1)
            ->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah');
        }

        // Verifikasi password
        if (!Hash::check($validated['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah');
        }

        // Simpan user ke session
        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
        ]);

        // Log aktivitas login
        DB::table('log_aktivitas')->insert([
            'id_user' => $user->id,
            'aktivitas' => 'Login ke aplikasi',
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect berdasarkan role
        return $this->redirectByRole($user->role);
    }

    /**
     * Proses logout pengguna
     */
    public function logout()
    {
        // Log aktivitas logout
        if (session('user_id')) {
            DB::table('log_aktivitas')->insert([
                'id_user' => session('user_id'),
                'aktivitas' => 'Logout dari aplikasi',
                'waktu_aktivitas' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Hapus session
        session()->forget([
            'user_id',
            'user_name',
            'user_email',
            'user_role',
        ]);

        // Flush semua session
        session()->flush();

        return redirect('/login')
            ->with('success', 'Anda berhasil logout');
    }

    /**
     * Redirect ke halaman sesuai role
     */
    private function redirectByRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard')
                    ->with('success', 'Login berhasil sebagai Admin');
            case 'petugas':
                return redirect('/petugas/dashboard')
                    ->with('success', 'Login berhasil sebagai Petugas');
            case 'owner':
                return redirect('/owner/dashboard')
                    ->with('success', 'Login berhasil sebagai Owner');
            default:
                return redirect('/login')
                    ->with('error', 'Role tidak dikenal');
        }
    }
}
