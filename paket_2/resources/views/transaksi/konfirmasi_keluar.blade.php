@extends('layouts.app')

@section('title', 'Konfirmasi Parkir Keluar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Konfirmasi Parkir Keluar
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Detail Kendaraan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-car me-2"></i>Detail Kendaraan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="text-muted small">Plat Nomor</label>
                                        <div class="h5 mb-0">{{ $transaksi->plat_nomor }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Jenis Kendaraan</label>
                                        <div>
                                            <span class="badge
                                                @if($transaksi->jenis_kendaraan == 'motor') bg-success
                                                @elseif($transaksi->jenis_kendaraan == 'mobil') bg-primary
                                                @else bg-secondary @endif">
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

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-clock me-2"></i>Detail Waktu
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="text-muted small">Waktu Masuk</label>
                                        <div class="h6 mb-0">{{ $waktuMasuk->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Waktu Keluar</label>
                                        <div class="h6 mb-0">{{ now()->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                    <div>
                                        <label class="text-muted small">Durasi Parkir</label>
                                        <div class="h5 mb-0 text-info">
                                            {{ $durasi }} menit ({{ $durasi_jam }} jam)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Perhitungan Biaya -->
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-calculator text-warning me-2"></i>Perhitungan Biaya
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tarif</label>
                                        <div class="h5 text-success">
                                            Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }} per jam
                                        </div>
                                        <small class="text-muted">{{ ucfirst($transaksi->jenis_kendaraan) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Durasi</label>
                                        <div class="h5 text-info">
                                            {{ $durasi_jam }} jam
                                        </div>
                                        <small class="text-muted">Dibulatkan ke atas</small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label fw-bold mb-0">Total Biaya Parkir</label>
                                        <div class="h3 text-success mb-0">
                                            Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Submit -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <form action="{{ route('transaksi.store.keluar') }}" method="POST" id="keluarForm">
                                @csrf
                                <input type="hidden" name="id_transaksi" value="{{ $transaksi->id }}">

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('transaksi.keluar') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-check me-2"></i>Konfirmasi & Simpan Parkir Keluar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('keluarForm').addEventListener('submit', function(e) {
        const konfirmasi = confirm('Apakah Anda yakin? Kendaraan {{ $transaksi->plat_nomor }} akan keluar dengan biaya Rp {{ number_format($totalBayar, 0, ',', '.') }}');
        if (!konfirmasi) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
