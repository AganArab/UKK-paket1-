<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan
     */
    public function index(Request $request)
    {
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        try {
            $fromDate = $dari ? Carbon::createFromFormat('Y-m-d', $dari)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        } catch (\Exception $e) {
            $fromDate = Carbon::now()->subDays(30)->startOfDay();
            $dari = $fromDate->format('Y-m-d');
        }

        try {
            $toDate = $sampai ? Carbon::createFromFormat('Y-m-d', $sampai)->endOfDay() : Carbon::now()->endOfDay();
        } catch (\Exception $e) {
            $toDate = Carbon::now()->endOfDay();
            $sampai = $toDate->format('Y-m-d');
        }

        if (!$dari) {
            $dari = $fromDate->format('Y-m-d');
        }

        if (!$sampai) {
            $sampai = $toDate->format('Y-m-d');
        }

        $query = DB::table('transaksi')
            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
            ->join('area_parkir', 'transaksi.id_area', '=', 'area_parkir.id')
            ->join('users', 'transaksi.id_user', '=', 'users.id')
            ->where('transaksi.status', 'keluar')
            ->whereBetween('transaksi.waktu_keluar', [$fromDate, $toDate]);

        $totalPemasukan = (float) $query->sum('transaksi.total_bayar');

        $laporans = $query
            ->select(
                'transaksi.id',
                'kendaraan.plat_nomor',
                'kendaraan.jenis_kendaraan',
                'area_parkir.nama_area',
                'transaksi.waktu_masuk',
                'transaksi.waktu_keluar',
                'transaksi.total_bayar',
                'users.nama as petugas'
            )
            ->orderBy('transaksi.waktu_keluar', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('laporan.index', compact('laporans', 'totalPemasukan', 'dari', 'sampai'));
    }
}
