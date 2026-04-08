@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Petugas
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statistik -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-car-side me-2"></i>Kendaraan Masuk Hari Ini
                                    </h5>
                                    <h3>{{ DB::table('transaksi')->where('status', 'masuk')->whereDate('waktu_masuk', today())->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-clock me-2"></i>Kendaraan Sedang Parkir
                                    </h5>
                                    <h3>{{ DB::table('transaksi')->where('status', 'masuk')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-sign-out-alt me-2"></i>Kendaraan Keluar Hari Ini
                                    </h5>
                                    <h3>{{ DB::table('transaksi')->where('status', 'keluar')->whereDate('waktu_keluar', today())->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Navigasi -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-list me-2"></i>Menu Transaksi
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-sign-in-alt fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Kendaraan Masuk</h5>
                                    <p class="card-text">Proses kendaraan masuk parkir</p>
                                    <a href="{{ route('transaksi.masuk') }}" class="btn btn-success btn-lg">
                                        <i class="fas fa-plus me-2"></i>Masuk Parkir
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">Kendaraan Keluar</h5>
                                    <p class="card-text">Proses kendaraan keluar parkir</p>
                                    <a href="{{ route('transaksi.keluar') }}" class="btn btn-danger btn-lg">
                                        <i class="fas fa-minus me-2"></i>Keluar Parkir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Transaksi Hari Ini -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-list me-2"></i>Transaksi Hari Ini
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $transaksiHariIni = DB::table('transaksi')
                                            ->join('kendaraan', 'transaksi.id_kendaraan', '=', 'kendaraan.id')
                                            ->join('area', 'transaksi.id_area', '=', 'area.id')
                                            ->select('transaksi.*', 'kendaraan.plat_nomor', 'kendaraan.jenis_kendaraan', 'area.nama_area')
                                            ->whereDate('transaksi.created_at', today())
                                            ->orderBy('transaksi.created_at', 'desc')
                                            ->limit(10)
                                            ->get();
                                    @endphp

                                    @if($transaksiHariIni->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Plat Nomor</th>
                                                        <th>Jenis</th>
                                                        <th>Area</th>
                                                        <th>Status</th>
                                                        <th>Waktu</th>
                                                        <th>Total Bayar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transaksiHariIni as $transaksi)
                                                        <tr>
                                                            <td>{{ $transaksi->plat_nomor }}</td>
                                                            <td>{{ $transaksi->jenis_kendaraan }}</td>
                                                            <td>{{ $transaksi->nama_area }}</td>
                                                            <td>
                                                                @if($transaksi->status == 'masuk')
                                                                    <span class="badge bg-success">Masuk</span>
                                                                @else
                                                                    <span class="badge bg-danger">Keluar</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($transaksi->status == 'masuk')
                                                                    {{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('H:i') }}
                                                                @else
                                                                    {{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('H:i') }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($transaksi->status == 'keluar')
                                                                    Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                                            <p>Belum ada transaksi hari ini</p>
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