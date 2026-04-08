@extends('layouts.app')

@section('title', 'Parkir Keluar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        Pilih Kendaraan untuk Keluar
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($kendaraans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Plat Nomor</th>
                                        <th>Jenis</th>
                                        <th>Area Parkir</th>
                                        <th>Waktu Masuk</th>
                                        <th>Durasi Parkir</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraans as $index => $kendaraan)
                                        @php
                                            $waktuMasuk = \Carbon\Carbon::parse($kendaraan->waktu_masuk);
                                            $durasi = now()->diffInMinutes($waktuMasuk);
                                            $durasi_jam = ceil($durasi / 60);
                                            $durasi_text = $durasi < 60 ? $durasi . ' menit' : $durasi_jam . ' jam';
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong class="text-primary">{{ $kendaraan->plat_nomor }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge
                                                    @if($kendaraan->jenis_kendaraan == 'motor') bg-success
                                                    @elseif($kendaraan->jenis_kendaraan == 'mobil') bg-primary
                                                    @else bg-secondary @endif">
                                                    <i class="fas
                                                        @if($kendaraan->jenis_kendaraan == 'motor') fa-motorcycle
                                                        @elseif($kendaraan->jenis_kendaraan == 'mobil') fa-car
                                                        @else fa-question-circle @endif me-1"></i>
                                                    {{ ucfirst($kendaraan->jenis_kendaraan) }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-parking text-info me-1"></i>
                                                {{ $kendaraan->nama_area }}
                                            </td>
                                            <td>{{ $waktuMasuk->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $durasi_text }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('transaksi.detail.keluar', $kendaraan->transaksi_id) }}"
                                                   class="btn btn-sm btn-danger">
                                                    <i class="fas fa-sign-out-alt me-1"></i>Keluar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Tidak ada kendaraan yang sedang parkir</p>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Transaksi
                            </a>
                        </div>
                    @endif

                    @if($kendaraans->count() > 0)
                        <div class="mt-3">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh setiap 30 detik untuk update durasi
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@endsection
