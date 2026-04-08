<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KendaraanController extends Controller
{
    /**
     * Tampilkan daftar semua kendaraan
     */
    public function index()
    {
        // Ambil semua kendaraan dengan pagination
        $kendaraans = DB::table('kendaraan')
            ->select('id', 'jenis_kendaraan', 'plat_nomor', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kendaraan.index', compact('kendaraans'));
    }

    /**
     * Tampilkan form untuk membuat kendaraan baru
     */
    public function create()
    {
        return view('kendaraan.create');
    }

    /**
     * Simpan kendaraan baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'plat_nomor' => 'required|string|max:15|unique:kendaraan,plat_nomor',
        ], [
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib dipilih',
            'jenis_kendaraan.in' => 'Jenis kendaraan tidak valid',
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'plat_nomor.max' => 'Plat nomor maksimal 15 karakter',
            'plat_nomor.unique' => 'Plat nomor sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan kendaraan baru
        $kendaraanId = DB::table('kendaraan')->insertGetId([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'plat_nomor' => strtoupper($request->plat_nomor), // Kapitalisasi plat nomor
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menambah kendaraan baru: {$request->plat_nomor} ({$request->jenis_kendaraan})",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    /**
     * Tampilkan detail kendaraan
     */
    public function show($id)
    {
        // Cari kendaraan berdasarkan ID
        $kendaraan = DB::table('kendaraan')
            ->select('id', 'jenis_kendaraan', 'plat_nomor', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$kendaraan) {
            return redirect()
                ->route('kendaraan.index')
                ->with('error', 'Kendaraan tidak ditemukan');
        }

        // Ambil transaksi kendaraan ini
        $transaksis = DB::table('transaksi')
            ->join('area_parkir', 'transaksi.id_area', '=', 'area_parkir.id')
            ->leftJoin('users', 'transaksi.id_user', '=', 'users.id')
            ->where('transaksi.id_kendaraan', $id)
            ->select(
                'transaksi.id',
                'transaksi.waktu_masuk',
                'transaksi.waktu_keluar',
                'transaksi.total_bayar',
                'transaksi.status',
                'area_parkir.nama_area',
                'users.name as petugas'
            )
            ->orderBy('transaksi.waktu_masuk', 'desc')
            ->limit(10)
            ->get();

        return view('kendaraan.show', compact('kendaraan', 'transaksis'));
    }

    /**
     * Tampilkan form edit kendaraan
     */
    public function edit($id)
    {
        // Cari kendaraan berdasarkan ID
        $kendaraan = DB::table('kendaraan')
            ->select('id', 'jenis_kendaraan', 'plat_nomor')
            ->where('id', $id)
            ->first();

        if (!$kendaraan) {
            return redirect()
                ->route('kendaraan.index')
                ->with('error', 'Kendaraan tidak ditemukan');
        }

        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Update kendaraan di database
     */
    public function update(Request $request, $id)
    {
        // Cari kendaraan terlebih dahulu
        $kendaraan = DB::table('kendaraan')->where('id', $id)->first();

        if (!$kendaraan) {
            return redirect()
                ->route('kendaraan.index')
                ->with('error', 'Kendaraan tidak ditemukan');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'plat_nomor' => 'required|string|max:15|unique:kendaraan,plat_nomor,' . $id,
        ], [
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib dipilih',
            'jenis_kendaraan.in' => 'Jenis kendaraan tidak valid',
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'plat_nomor.max' => 'Plat nomor maksimal 15 karakter',
            'plat_nomor.unique' => 'Plat nomor sudah digunakan kendaraan lain',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Data untuk update
        $updateData = [
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'plat_nomor' => strtoupper($request->plat_nomor),
            'updated_at' => now(),
        ];

        // Update kendaraan
        DB::table('kendaraan')
            ->where('id', $id)
            ->update($updateData);

        // Log aktivitas
        $changes = [];
        if ($kendaraan->jenis_kendaraan !== $request->jenis_kendaraan) {
            $changes[] = "jenis: {$kendaraan->jenis_kendaraan} → {$request->jenis_kendaraan}";
        }
        if ($kendaraan->plat_nomor !== strtoupper($request->plat_nomor)) {
            $changes[] = "plat: {$kendaraan->plat_nomor} → " . strtoupper($request->plat_nomor);
        }

        $changeText = empty($changes) ? 'tidak ada perubahan' : implode(', ', $changes);

        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Mengupdate kendaraan {$kendaraan->plat_nomor}: {$changeText}",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diupdate');
    }

    /**
     * Hapus kendaraan dari database
     */
    public function destroy($id)
    {
        // Cari kendaraan terlebih dahulu
        $kendaraan = DB::table('kendaraan')->where('id', $id)->first();

        if (!$kendaraan) {
            return redirect()
                ->route('kendaraan.index')
                ->with('error', 'Kendaraan tidak ditemukan');
        }

        // Cek apakah kendaraan sedang dalam transaksi aktif
        $transaksiAktif = DB::table('transaksi')
            ->where('id_kendaraan', $id)
            ->where('status', 'masuk')
            ->exists();

        if ($transaksiAktif) {
            return redirect()
                ->route('kendaraan.index')
                ->with('error', 'Tidak dapat menghapus kendaraan yang sedang parkir');
        }

        // Simpan data untuk log
        $platNomor = $kendaraan->plat_nomor;
        $jenisKendaraan = $kendaraan->jenis_kendaraan;

        // Hapus kendaraan
        DB::table('kendaraan')->where('id', $id)->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menghapus kendaraan: {$platNomor} ({$jenisKendaraan})",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus');
    }
}
