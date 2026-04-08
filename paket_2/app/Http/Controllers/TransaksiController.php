<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar transaksi parkir
     */
    public function index()
    {
        // Ambil transaksi dengan join kendaraan, area, dan user
        $transaksis = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->join('area_parkir', 'transaksi.id_area', '=', 'area_parkir.id')
            ->join('users', 'transaksi.id_user', '=', 'users.id')
            ->select(
                'transaksi.id',
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan',
                'area_parkir.nama_area',
                'transaksi.waktu_masuk',
                'transaksi.waktu_keluar',
                'transaksi.total_bayar',
                'transaksi.status',
                'users.nama as petugas'
            )
            ->orderBy('transaksi.waktu_masuk', 'desc')
            ->paginate(15);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Tampilkan form untuk parkir masuk
     */
    public function createMasuk()
    {
        // Ambil daftar kendaraan yang belum parkir
        $kendaraans = DB::table('kendaraan')
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                    ->from('transaksi')
                    ->whereColumn('transaksi.id_kendaraan', 'kendaraan.id')
                    ->where('transaksi.status', 'masuk');
            })
            ->select('id', 'plat_nomor', 'jenis_kendaraan', 'pemilik')
            ->orderBy('plat_nomor')
            ->get();

        // Ambil daftar area dengan slot tersedia
        $areas = DB::table('area_parkir')
            ->leftJoin('transaksi', function ($join) {
                $join->on('area_parkir.id', '=', 'transaksi.id_area')
                     ->where('transaksi.status', 'masuk');
            })
            ->select(
                'area_parkir.id',
                'area_parkir.nama_area',
                'area_parkir.kapasitas',
                DB::raw('COUNT(transaksi.id) as kendaraan_parkir')
            )
            ->groupBy('area_parkir.id', 'area_parkir.nama_area', 'area_parkir.kapasitas')
            ->having(DB::raw('COUNT(transaksi.id)'), '<', DB::raw('area_parkir.kapasitas'))
            ->orderBy('area_parkir.nama_area')
            ->get();

        return view('transaksi.parkir_masuk', compact('kendaraans', 'areas'));
    }

    /**
     * Simpan parkir masuk
     */
    public function storeMasuk(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_kendaraan' => 'required|exists:kendaraan,id',
            'id_area' => 'required|exists:area_parkir,id',
        ], [
            'id_kendaraan.required' => 'Kendaraan wajib dipilih',
            'id_kendaraan.exists' => 'Kendaraan tidak ditemukan',
            'id_area.required' => 'Area parkir wajib dipilih',
            'id_area.exists' => 'Area parkir tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek kendaraan sudah parkir atau belum
        $kendaraanSudahParkir = DB::table('transaksi')
            ->where('id_kendaraan', $request->id_kendaraan)
            ->where('status', 'masuk')
            ->exists();

        if ($kendaraanSudahParkir) {
            return back()
                ->withInput()
                ->with('error', 'Kendaraan ini sudah sedang parkir');
        }

        // Cek kapasitas area
        $kendaraanDiArea = DB::table('transaksi')
            ->where('id_area', $request->id_area)
            ->where('status', 'masuk')
            ->count();

        $kapasitas = DB::table('area_parkir')
            ->where('id', $request->id_area)
            ->value('kapasitas');

        if ($kendaraanDiArea >= $kapasitas) {
            return back()
                ->withInput()
                ->with('error', 'Area parkir sudah penuh');
        }

        // Ambil info kendaraan
        $kendaraan = DB::table('kendaraan')->where('id', $request->id_kendaraan)->first();
        $area = DB::table('area_parkir')->where('id', $request->id_area)->first();

        // Simpan transaksi parkir masuk
        $transaksiId = DB::table('transaksi')->insertGetId([
            'id_kendaraan' => $request->id_kendaraan,
            'id_area' => $request->id_area,
            'id_user' => session('user_id'),
            'waktu_masuk' => now(),
            'status' => 'masuk',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Parkir masuk: {$kendaraan->plat_nomor} ({$kendaraan->jenis_kendaraan}) di {$area->nama_area}",
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Parkir masuk berhasil dicatat');
    }

    /**
     * Tampilkan form untuk parkir keluar
     */
    public function createKeluar()
    {
        // Ambil kendaraan yang sedang parkir
        $kendaraans = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->join('area_parkir', 'transaksi.id_area', '=', 'area_parkir.id')
            ->where('transaksi.status', 'masuk')
            ->select(
                'transaksi.id as transaksi_id',
                'kendaraan.id as kendaraan_id',
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan',
                'area_parkir.nama_area',
                'transaksi.waktu_masuk'
            )
            ->orderBy('transaksi.waktu_masuk', 'asc')
            ->get();

        return view('transaksi.parkir_keluar', compact('kendaraans'));
    }

    /**
     * Tampilkan detail parkir keluar sebelum konfirmasi
     */
    public function detailKeluar($id)
    {
        // Cari transaksi parkir masuk
        $transaksi = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->join('area_parkir', 'transaksi.id_area', '=', 'area_parkir.id')
            ->where('transaksi.id', $id)
            ->where('transaksi.status', 'masuk')
            ->select(
                'transaksi.id',
                'transaksi.waktu_masuk',
                'kendaraan.id as kendaraan_id',
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan',
                'area_parkir.nama_area'
            )
            ->first();

        if (!$transaksi) {
            return redirect()
                ->route('transaksi.index')
                ->with('error', 'Transaksi parkir tidak ditemukan');
        }

        // Hitung durasi
        $waktuMasuk = Carbon::parse($transaksi->waktu_masuk);
        $waktuKeluar = now();
        $durasi = $waktuKeluar->diffInMinutes($waktuMasuk);
        $durasi_jam = ceil($durasi / 60);

        // Ambil tarif
        $tarif = DB::table('tarif')
            ->where('jenis_kendaraan', $transaksi->jenis_kendaraan)
            ->first();

        if (!$tarif) {
            return back()
                ->with('error', 'Tarif untuk ' . $transaksi->jenis_kendaraan . ' tidak ditemukan');
        }

        // Hitung total bayar
        $totalBayar = $tarif->harga_per_jam * $durasi_jam;

        return view('transaksi.konfirmasi_keluar', compact(
            'transaksi',
            'waktuMasuk',
            'durasi',
            'durasi_jam',
            'tarif',
            'totalBayar'
        ));
    }

    /**
     * Simpan parkir keluar
     */
    public function storeKeluar(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required|exists:transaksi,id',
        ], [
            'id_transaksi.required' => 'Transaksi wajib diisi',
            'id_transaksi.exists' => 'Transaksi tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cari transaksi parkir masuk
        $transaksi = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->where('transaksi.id', $request->id_transaksi)
            ->where('transaksi.status', 'masuk')
            ->select(
                'transaksi.id',
                'transaksi.waktu_masuk',
                'transaksi.id_kendaraan',
                'transaksi.id_area',
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan'
            )
            ->first();

        if (!$transaksi) {
            return back()
                ->with('error', 'Transaksi parkir tidak ditemukan atau sudah keluar');
        }

        // Hitung durasi
        $waktuMasuk = Carbon::parse($transaksi->waktu_masuk);
        $waktuKeluar = now();
        $durasi = $waktuKeluar->diffInMinutes($waktuMasuk);
        $durasi_jam = ceil($durasi / 60);

        // Ambil tarif
        $tarif = DB::table('tarif')
            ->where('jenis_kendaraan', $transaksi->jenis_kendaraan)
            ->first();

        if (!$tarif) {
            return back()
                ->with('error', 'Tarif tidak ditemukan');
        }

        // Hitung total bayar
        $totalBayar = $tarif->harga_per_jam * $durasi_jam;

        // Update transaksi menjadi keluar
        DB::table('transaksi')
            ->where('id', $request->id_transaksi)
            ->update([
                'waktu_keluar' => $waktuKeluar,
                'durasi_jam' => $durasi_jam,
                'total_bayar' => $totalBayar,
                'status' => 'keluar',
                'updated_at' => now(),
            ]);

        // Log aktivitas
        $area = DB::table('area_parkir')
            ->where('id', $transaksi->id_area)
            ->value('nama_area');

        DB::table('log_aktivitas')->insert([
            'id_user' => session('user_id'),
            'aktivitas' => "Parkir keluar: {$transaksi->plat_nomor} dari {$area}, durasi {$durasi_jam} jam, bayar Rp " . number_format($totalBayar, 0, ',', '.'),
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Parkir keluar berhasil. Total bayar: Rp ' . number_format($totalBayar, 0, ',', '.'));
    }

    /**
     * Tampilkan detail transaksi
     */
    public function show($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->join('area_parkir', 'transaksi.id_area', '=', 'area_parkir.id')
            ->join('users', 'transaksi.id_user', '=', 'users.id')
            ->where('transaksi.id', $id)
            ->select(
                'transaksi.*',
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan',
                'area_parkir.nama_area',
                'users.nama as petugas'
            )
            ->first();

        if (!$transaksi) {
            return redirect()
                ->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan');
        }

        // Jika keluar, hitung durasi
        if ($transaksi->status === 'keluar') {
            $waktuMasuk = Carbon::parse($transaksi->waktu_masuk);
            $waktuKeluar = Carbon::parse($transaksi->waktu_keluar);
            $durasi = $waktuMasuk->diff($waktuKeluar);
        }

        return view('transaksi.show', compact('transaksi', 'durasi'));
    }
}
