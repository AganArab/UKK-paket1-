@extends('layouts.app')

@section('title', 'Detail Transaksi Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-receipt text-primary me-2"></i>
                            Detail Transaksi Parkir
                        </h4>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Info Kendaraan -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-car me-2"></i>Data Kendaraan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted small">Plat Nomor</label>
                                        <div class="h5 mb-0 text-primary">{{ $transaksi->plat_nomor }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Jenis Kendaraan</label>
                                        <div>
                                            <span class="badge
                                                @if($transaksi->jenis_kendaraan == 'motor') bg-success
                                                @elseif($transaksi->jenis_kendaraan == 'mobil') bg-primary
                                                @else bg-secondary @endif fs-6">
                                                <i class="fas
                                                    @if($transaksi->jenis_kendaraan == 'motor') fa-motorcycle
                                                    @elseif($transaksi->jenis_kendaraan == 'mobil') fa-car
                                                    @else fa-question-circle @endif me-1"></i>
                                                {{ ucfirst($transaksi->jenis_kendaraan) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-muted small">Area Parkir</label>
                                        <div class="h6 mb-0">
                                            <i class="fas fa-parking text-info me-2"></i>
                                            {{ $transaksi->nama_area }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Waktu -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-clock me-2"></i>Waktu Parkir
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted small">Waktu Masuk</label>
                                        <div class="small mb-0">{{ $transaksi->waktu_masuk->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Waktu Keluar</label>
                                        @if($transaksi->waktu_keluar)
                                            <div class="small mb-0">{{ $transaksi->waktu_keluar->format('d/m/Y H:i:s') }}</div>
                                        @else
                                            <div class="small mb-0 text-muted">Masih Parkir</div>
                                        @endif
                                    </div>
                                    @if($transaksi->durasi_jam)
                                    <div>
                                        <label class="text-muted small">Durasi</label>
                                        <div class="h6 mb-0 text-info">{{ $transaksi->durasi_jam }} jam</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Info Biaya -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-money-bill-wave me-2"></i>Biaya Parkir
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted small">Status</label>
                                        <div>
                                            @if($transaksi->status === 'masuk')
                                                <span class="badge bg-warning fs-6">Masih Parkir</span>
                                            @else
                                                <span class="badge bg-success fs-6">Sudah Keluar</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-muted small">Total Bayar</label>
                                        @if($transaksi->total_bayar)
                                            <div class="h4 mb-0 text-success">
                                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div class="small mb-0 text-muted">Menunggu keluar</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Petugas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <label class="text-muted small fw-bold">Petugas Masuk</label>
                                                <div class="h6 mb-0">
                                                    <i class="fas fa-user text-primary me-2"></i>
                                                    {{ $transaksi->petugas }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>
                                                <label class="text-muted small fw-bold">ID Transaksi</label>
                                                <div class="h6 mb-0 font-monospace">{{ $transaksi->id }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Duraasi Detail (jika sudah keluar) -->
                    @if($transaksi->status === 'keluar' && isset($durasi))
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-info-circle text-info me-2"></i>Detail Durasi Parkir
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">
                                        Kendaraan {{ $transaksi->plat_nomor }} parkir selama
                                        <strong>{{ $durasi->d }} hari, {{ $durasi->h }} jam, {{ $durasi->i }} menit, {{ $durasi->s }} detik</strong>
                                        ({{ $transaksi->durasi_jam }} jam dibulatkan)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
