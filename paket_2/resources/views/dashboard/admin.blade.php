@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Statistik -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-users me-2"></i>Total Users
                                    </h5>
                                    <h3>{{ DB::table('users')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-car me-2"></i>Total Kendaraan
                                    </h5>
                                    <h3>{{ DB::table('kendaraan')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-parking me-2"></i>Area Parkir
                                    </h5>
                                    <h3>{{ DB::table('area')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-money-bill-wave me-2"></i>Pemasukan Hari Ini
                                    </h5>
                                    <h3>Rp {{ number_format(DB::table('transaksi')->where('status', 'keluar')->whereDate('waktu_keluar', today())->sum('total_bayar'), 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Navigasi -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-list me-2"></i>Menu Management
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">User Management</h5>
                                    <p class="card-text">Kelola pengguna sistem</p>
                                    <a href="{{ route('users.index') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-right me-2"></i>Kelola Users
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-car fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Kendaraan</h5>
                                    <p class="card-text">Kelola data kendaraan</p>
                                    <a href="{{ route('kendaraan.index') }}" class="btn btn-success">
                                        <i class="fas fa-arrow-right me-2"></i>Kelola Kendaraan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-dollar-sign fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">Tarif Parkir</h5>
                                    <p class="card-text">Kelola tarif parkir</p>
                                    <a href="{{ route('tarif.index') }}" class="btn btn-warning">
                                        <i class="fas fa-arrow-right me-2"></i>Kelola Tarif
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-parking fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">Area Parkir</h5>
                                    <p class="card-text">Kelola area parkir</p>
                                    <a href="{{ route('area.index') }}" class="btn btn-info">
                                        <i class="fas fa-arrow-right me-2"></i>Kelola Area
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-exchange-alt fa-3x text-secondary mb-3"></i>
                                    <h5 class="card-title">Transaksi</h5>
                                    <p class="card-text">Kelola transaksi parkir</p>
                                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-right me-2"></i>Kelola Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">Laporan</h5>
                                    <p class="card-text">Lihat laporan pemasukan</p>
                                    <a href="{{ route('laporan.index') }}" class="btn btn-danger">
                                        <i class="fas fa-arrow-right me-2"></i>Lihat Laporan
                                    </a>
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