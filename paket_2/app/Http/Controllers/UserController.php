<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua users
     */
    public function index()
    {
        // Ambil semua users dengan pagination
        $users = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'status_aktif', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form untuk membuat user baru
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan user baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:6|max:255',
            'role' => 'required|in:admin,petugas,owner',
            'status_aktif' => 'required|in:0,1',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 50 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'email.max' => 'Email maksimal 100 karakter',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 255 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'status_aktif.required' => 'Status aktif wajib dipilih',
            'status_aktif.in' => 'Status aktif tidak valid',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan user baru
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status_aktif' => $request->status_aktif,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menambah user baru: {$request->name} ({$request->email})",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Tampilkan detail user
     */
    public function show($id)
    {
        // Cari user berdasarkan ID
        $user = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'status_aktif', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$user) {
            return redirect()
                ->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        // Ambil log aktivitas user ini
        $aktivitas = DB::table('log_aktivitas')
            ->where('id_user', $id)
            ->orderBy('waktu_aktivitas', 'desc')
            ->limit(10)
            ->get();

        return view('users.show', compact('user', 'aktivitas'));
    }

    /**
     * Tampilkan form edit user
     */
    public function edit($id)
    {
        // Cari user berdasarkan ID
        $user = DB::table('users')
            ->select('id', 'name', 'email', 'role', 'status_aktif')
            ->where('id', $id)
            ->first();

        if (!$user) {
            return redirect()
                ->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update user di database
     */
    public function update(Request $request, $id)
    {
        // Cari user terlebih dahulu
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()
                ->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|max:255',
            'role' => 'required|in:admin,petugas,owner',
            'status_aktif' => 'required|in:0,1',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 50 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'email.max' => 'Email maksimal 100 karakter',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 255 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'status_aktif.required' => 'Status aktif wajib dipilih',
            'status_aktif.in' => 'Status aktif tidak valid',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Data untuk update
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status_aktif' => $request->status_aktif,
            'updated_at' => now(),
        ];

        // Jika password diisi, hash dan tambahkan ke update data
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user
        DB::table('users')
            ->where('id', $id)
            ->update($updateData);

        // Log aktivitas
        $changes = [];
        if ($user->name !== $request->name) $changes[] = "nama: {$user->name} → {$request->name}";
        if ($user->email !== $request->email) $changes[] = "email: {$user->email} → {$request->email}";
        if ($user->role !== $request->role) $changes[] = "role: {$user->role} → {$request->role}";
        if ($user->status_aktif != $request->status_aktif) $changes[] = "status: {$user->status_aktif} → {$request->status_aktif}";
        if ($request->filled('password')) $changes[] = "password diubah";

        $changeText = empty($changes) ? 'tidak ada perubahan' : implode(', ', $changes);

        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Mengupdate user {$user->name}: {$changeText}",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Hapus user dari database
     */
    public function destroy($id)
    {
        // Cari user terlebih dahulu
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()
                ->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        // Cek apakah user sedang login
        if ($user->id == session('user_id')) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Tidak dapat menghapus user yang sedang login');
        }

        // Simpan nama user untuk log
        $userName = $user->name;
        $userEmail = $user->email;

        // Hapus user
        DB::table('users')->where('id', $id)->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menghapus user: {$userName} ({$userEmail})",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
