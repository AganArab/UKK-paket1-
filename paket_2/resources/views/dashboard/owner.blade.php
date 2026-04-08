@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Owner
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statistik Keuangan -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-money-bill-wave me-2"></i>Pemasukan Hari Ini
                                    </h5>
                                    <h3>Rp {{ number_format(DB::table('transaksi')->where('status', 'keluar')->whereDate('waktu_keluar', today())->sum('total_bayar'), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-calendar-day me-2"></i>Pemasukan Bulan Ini
                                    </h5>
                                    <h3>Rp {{ number_format(DB::table('transaksi')->where('status', 'keluar')->whereMonth('waktu_keluar', now()->month)->whereYear('waktu_keluar', now()->year)->sum('total_bayar'), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-calendar-alt me-2"></i>Pemasukan Tahun Ini
                                    </h5>
                                    <h3>Rp {{ number_format(DB::table('transaksi')->where('status', 'keluar')->whereYear('waktu_keluar', now()->year)->sum('total_bayar'), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-car me-2"></i>Total Transaksi
                                    </h5>
                                    <h3>{{ DB::table('transaksi')->where('status', 'keluar')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Navigasi -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-list me-2"></i>Menu Laporan
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Laporan Pemasukan</h5>
                                    <p class="card-text">Lihat detail laporan pemasukan parkir</p>
                                    <a href="{{ route('laporan.index') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exchange-alt fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Transaksi Parkir</h5>
                                    <p class="card-text">Lihat semua transaksi parkir</p>
                                    <a href="{{ route('transaksi.index') }}" class="btn btn-success btn-lg">
                                        <i class="fas fa-list me-2"></i>Lihat Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Pemasukan Bulanan (Placeholder) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>Grafik Pemasukan Bulanan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-chart-line fa-4x mb-3"></i>
                                        <p>Grafik pemasukan bulanan akan ditampilkan di sini</p>
                                        <small class="text-muted">Fitur grafik akan diimplementasikan dengan Chart.js</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Area Berdasarkan Pemasukan -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-trophy me-2"></i>Top Area Parkir (Pemasukan Tertinggi)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $topAreas = DB::table('transaksi')
                                            ->join('area', 'transaksi.id_area', '=', 'area.id')
                                            ->select('area.nama_area', DB::raw('SUM(transaksi.total_bayar) as total_pemasukan'))
                                            ->where('transaksi.status', 'keluar')
                                            ->groupBy('area.id', 'area.nama_area')
                                            ->orderBy('total_pemasukan', 'desc')
                                            ->limit(5)
                                            ->get();
                                    @endphp

                                    @if($topAreas->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Area Parkir</th>
                                                        <th>Total Pemasukan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topAreas as $area)
                                                        <tr>
                                                            <td>{{ $area->nama_area }}</td>
                                                            <td>Rp {{ number_format($area->total_pemasukan, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                                            <p>Belum ada data pemasukan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection