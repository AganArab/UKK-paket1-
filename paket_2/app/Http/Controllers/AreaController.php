<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Tampilkan daftar semua area parkir
     */
    public function index()
    {
        // Ambil semua area parkir dengan informasi penggunaan
        $areas = DB::table('area_parkir')
            ->leftJoin('transaksi', function($join) {
                $join->on('area_parkir.id', '=', 'transaksi.id_area')
                     ->where('transaksi.status', 'masuk');
            })
            ->select(
                'area_parkir.id',
                'area_parkir.nama_area',
                'area_parkir.kapasitas',
                'area_parkir.created_at',
                DB::raw('COUNT(transaksi.id) as kendaraan_parkir')
            )
            ->groupBy('area_parkir.id', 'area_parkir.nama_area', 'area_parkir.kapasitas', 'area_parkir.created_at')
            ->orderBy('area_parkir.nama_area')
            ->paginate(10);

        return view('area.index', compact('areas'));
    }

    /**
     * Tampilkan form untuk membuat area parkir baru
     */
    public function create()
    {
        return view('area.create');
    }

    /**
     * Simpan area parkir baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100|unique:area_parkir,nama_area',
            'kapasitas' => 'required|integer|min:1|max:1000',
        ], [
            'nama_area.required' => 'Nama area wajib diisi',
            'nama_area.string' => 'Nama area harus berupa teks',
            'nama_area.max' => 'Nama area maksimal 100 karakter',
            'nama_area.unique' => 'Nama area sudah digunakan',
            'kapasitas.required' => 'Kapasitas wajib diisi',
            'kapasitas.integer' => 'Kapasitas harus berupa angka',
            'kapasitas.min' => 'Kapasitas minimal 1 kendaraan',
            'kapasitas.max' => 'Kapasitas maksimal 1000 kendaraan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan area parkir baru
        $areaId = DB::table('area_parkir')->insertGetId([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menambah area parkir baru: {$request->nama_area} (Kapasitas: {$request->kapasitas} kendaraan)",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('area.index')
            ->with('success', 'Area parkir berhasil ditambahkan');
    }

    /**
     * Tampilkan detail area parkir
     */
    public function show($id)
    {
        // Cari area parkir berdasarkan ID
        $area = DB::table('area_parkir')
            ->select('id', 'nama_area', 'kapasitas', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$area) {
            return redirect()
                ->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan');
        }

        // Hitung statistik area
        $totalTransaksi = DB::table('transaksi')
            ->where('id_area', $id)
            ->where('status', 'keluar')
            ->count();

        $totalPendapatan = DB::table('transaksi')
            ->where('id_area', $id)
            ->where('status', 'keluar')
            ->sum('total_bayar');

        $kendaraanSekarang = DB::table('transaksi')
            ->where('id_area', $id)
            ->where('status', 'masuk')
            ->count();

        $kapasitasTersedia = $area->kapasitas - $kendaraanSekarang;

        // Ambil kendaraan yang sedang parkir di area ini
        $kendaraanParkir = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->join('users', 'transaksi.id_user', '=', 'users.id')
            ->where('transaksi.id_area', $id)
            ->where('transaksi.status', 'masuk')
            ->select(
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan',
                'users.nama as petugas',
                'transaksi.waktu_masuk'
            )
            ->orderBy('transaksi.waktu_masuk', 'desc')
            ->get();

        return view('area.show', compact(
            'area',
            'totalTransaksi',
            'totalPendapatan',
            'kendaraanSekarang',
            'kapasitasTersedia',
            'kendaraanParkir'
        ));
    }

    /**
     * Tampilkan form edit area parkir
     */
    public function edit($id)
    {
        // Cari area parkir berdasarkan ID
        $area = DB::table('area_parkir')
            ->select('id', 'nama_area', 'kapasitas')
            ->where('id', $id)
            ->first();

        if (!$area) {
            return redirect()
                ->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan');
        }

        return view('area.edit', compact('area'));
    }

    /**
     * Update area parkir di database
     */
    public function update(Request $request, $id)
    {
        // Cari area parkir terlebih dahulu
        $area = DB::table('area_parkir')->where('id', $id)->first();

        if (!$area) {
            return redirect()
                ->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100|unique:area_parkir,nama_area,' . $id,
            'kapasitas' => 'required|integer|min:1|max:1000',
        ], [
            'nama_area.required' => 'Nama area wajib diisi',
            'nama_area.string' => 'Nama area harus berupa teks',
            'nama_area.max' => 'Nama area maksimal 100 karakter',
            'nama_area.unique' => 'Nama area sudah digunakan',
            'kapasitas.required' => 'Kapasitas wajib diisi',
            'kapasitas.integer' => 'Kapasitas harus berupa angka',
            'kapasitas.min' => 'Kapasitas minimal 1 kendaraan',
            'kapasitas.max' => 'Kapasitas maksimal 1000 kendaraan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek kapasitas tidak boleh kurang dari kendaraan yang sedang parkir
        $kendaraanParkir = DB::table('transaksi')
            ->where('id_area', $id)
            ->where('status', 'masuk')
            ->count();

        if ($request->kapasitas < $kendaraanParkir) {
            return back()
                ->withInput()
                ->with('error', 'Kapasitas tidak boleh kurang dari jumlah kendaraan yang sedang parkir (' . $kendaraanParkir . ' kendaraan)');
        }

        // Data untuk update
        $updateData = [
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'updated_at' => now(),
        ];

        // Update area parkir
        DB::table('area_parkir')
            ->where('id', $id)
            ->update($updateData);

        // Log aktivitas
        $changes = [];
        if ($area->nama_area != $request->nama_area) {
            $changes[] = "nama: '{$area->nama_area}' → '{$request->nama_area}'";
        }
        if ($area->kapasitas != $request->kapasitas) {
            $changes[] = "kapasitas: {$area->kapasitas} → {$request->kapasitas}";
        }

        $changeText = empty($changes) ? 'tidak ada perubahan' : implode(', ', $changes);

        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Mengupdate area parkir {$area->nama_area}: {$changeText}",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('area.index')
            ->with('success', 'Area parkir berhasil diupdate');
    }

    /**
     * Hapus area parkir dari database
     */
    public function destroy($id)
    {
        // Cari area parkir terlebih dahulu
        $area = DB::table('area_parkir')->where('id', $id)->first();

        if (!$area) {
            return redirect()
                ->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan');
        }

        // Cek apakah area sedang digunakan (ada kendaraan parkir)
        $kendaraanParkir = DB::table('transaksi')
            ->where('id_area', $id)
            ->where('status', 'masuk')
            ->exists();

        if ($kendaraanParkir) {
            return redirect()
                ->route('area.index')
                ->with('error', 'Tidak dapat menghapus area parkir yang masih memiliki kendaraan parkir');
        }

        // Simpan data untuk log
        $namaArea = $area->nama_area;
        $kapasitas = $area->kapasitas;

        // Hapus area parkir
        DB::table('area_parkir')->where('id', $id)->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Menghapus area parkir: {$namaArea} (Kapasitas: {$kapasitas} kendaraan)",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('area.index')
            ->with('success', 'Area parkir berhasil dihapus');
    }
}
