<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TarifController extends Controller
{
    /**
     * Tampilkan daftar semua tarif
     */
    public function index()
    {
        // Ambil semua tarif dengan pagination
        $tarifs = DB::table('tarif')
            ->select('id', 'jenis_kendaraan', 'harga_per_jam', 'created_at')
            ->orderBy('jenis_kendaraan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tarif.index', compact('tarifs'));
    }

    /**
     * Tampilkan form untuk membuat tarif baru
     */
    public function create()
    {
        return view('tarif.create');
    }

    /**
     * Simpan tarif baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'jenis_kendaraan' => 'required|in:motor,mobil,lainnya',
            'harga_per_jam' => 'required|integer|min:1000|max:100000',
        ], [
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib dipilih',
            'jenis_kendaraan.in' => 'Jenis kendaraan tidak valid',
            'harga_per_jam.required' => 'Harga per jam wajib diisi',
            'harga_per_jam.integer' => 'Harga per jam harus berupa angka',
            'harga_per_jam.min' => 'Harga per jam minimal Rp 1.000',
            'harga_per_jam.max' => 'Harga per jam maksimal Rp 100.000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah tarif untuk jenis kendaraan ini sudah ada
        $existingTarif = DB::table('tarif')
            ->where('jenis_kendaraan', $request->jenis_kendaraan)
            ->exists();

        if ($existingTarif) {
            return back()
                ->withInput()
                ->with('error', 'Tarif untuk jenis kendaraan ' . $request->jenis_kendaraan . ' sudah ada. Silakan update tarif yang sudah ada.');
        }

        // Simpan tarif baru
        $tarifId = DB::table('tarif')->insertGetId([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'harga_per_jam' => $request->harga_per_jam,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menambah tarif baru: {$request->jenis_kendaraan} - Rp " . number_format($request->harga_per_jam, 0, ',', '.'),
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('tarif.index')
            ->with('success', 'Tarif berhasil ditambahkan');
    }

    /**
     * Tampilkan detail tarif
     */
    public function show($id)
    {
        // Cari tarif berdasarkan ID
        $tarif = DB::table('tarif')
            ->select('id', 'jenis_kendaraan', 'harga_per_jam', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$tarif) {
            return redirect()
                ->route('tarif.index')
                ->with('error', 'Tarif tidak ditemukan');
        }

        // Hitung statistik penggunaan tarif ini
        $totalTransaksi = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->where('kendaraan.jenis_kendaraan', $tarif->jenis_kendaraan)
            ->where('transaksi.status', 'keluar')
            ->count();

        $totalPendapatan = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->where('kendaraan.jenis_kendaraan', $tarif->jenis_kendaraan)
            ->where('transaksi.status', 'keluar')
            ->sum('transaksi.total_bayar');

        return view('tarif.show', compact('tarif', 'totalTransaksi', 'totalPendapatan'));
    }

    /**
     * Tampilkan form edit tarif
     */
    public function edit($id)
    {
        // Cari tarif berdasarkan ID
        $tarif = DB::table('tarif')
            ->select('id', 'jenis_kendaraan', 'harga_per_jam')
            ->where('id', $id)
            ->first();

        if (!$tarif) {
            return redirect()
                ->route('tarif.index')
                ->with('error', 'Tarif tidak ditemukan');
        }

        return view('tarif.edit', compact('tarif'));
    }

    /**
     * Update tarif di database
     */
    public function update(Request $request, $id)
    {
        // Cari tarif terlebih dahulu
        $tarif = DB::table('tarif')->where('id', $id)->first();

        if (!$tarif) {
            return redirect()
                ->route('tarif.index')
                ->with('error', 'Tarif tidak ditemukan');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'harga_per_jam' => 'required|integer|min:1000|max:100000',
        ], [
            'harga_per_jam.required' => 'Harga per jam wajib diisi',
            'harga_per_jam.integer' => 'Harga per jam harus berupa angka',
            'harga_per_jam.min' => 'Harga per jam minimal Rp 1.000',
            'harga_per_jam.max' => 'Harga per jam maksimal Rp 100.000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Data untuk update
        $updateData = [
            'harga_per_jam' => $request->harga_per_jam,
            'updated_at' => now(),
        ];

        // Update tarif
        DB::table('tarif')
            ->where('id', $id)
            ->update($updateData);

        // Log aktivitas
        $changes = [];
        if ($tarif->harga_per_jam != $request->harga_per_jam) {
            $changes[] = "harga: Rp " . number_format($tarif->harga_per_jam, 0, ',', '.') . " → Rp " . number_format($request->harga_per_jam, 0, ',', '.');
        }

        $changeText = empty($changes) ? 'tidak ada perubahan' : implode(', ', $changes);

        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Mengupdate tarif {$tarif->jenis_kendaraan}: {$changeText}",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('tarif.index')
            ->with('success', 'Tarif berhasil diupdate');
    }

    /**
     * Hapus tarif dari database
     */
    public function destroy($id)
    {
        // Cari tarif terlebih dahulu
        $tarif = DB::table('tarif')->where('id', $id)->first();

        if (!$tarif) {
            return redirect()
                ->route('tarif.index')
                ->with('error', 'Tarif tidak ditemukan');
        }

        // Cek apakah tarif sedang digunakan dalam transaksi aktif
        $transaksiAktif = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->where('kendaraan.jenis_kendaraan', $tarif->jenis_kendaraan)
            ->where('transaksi.status', 'masuk')
            ->exists();

        if ($transaksiAktif) {
            return redirect()
                ->route('tarif.index')
                ->with('error', 'Tidak dapat menghapus tarif yang sedang digunakan kendaraan parkir');
        }

        // Simpan data untuk log
        $jenisKendaraan = $tarif->jenis_kendaraan;
        $hargaPerJam = $tarif->harga_per_jam;

        // Hapus tarif
        DB::table('tarif')->where('id', $id)->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menghapus tarif: {$jenisKendaraan} - Rp " . number_format($hargaPerJam, 0, ',', '.'),
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('tarif.index')
            ->with('success', 'Tarif berhasil dihapus');
    }
}
