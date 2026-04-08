<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAspirasi = DB::table('aspirasi')->count();
        $totalKategori = DB::table('kategori')->count();
        $totalSelesai = DB::table('aspirasi')->where('status', 'selesai')->count();

        return view('admin.dashboard', compact('totalAspirasi', 'totalKategori', 'totalSelesai'));
    }
}
