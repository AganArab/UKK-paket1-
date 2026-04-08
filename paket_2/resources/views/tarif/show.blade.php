@extends('layouts.app')

@section('title', 'Detail Tarif Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-dollar-sign text-primary me-2"></i>
                            Detail Tarif Parkir
                        </h4>
                        <div>
                            <a href="{{ route('tarif.edit', $tarif->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('tarif.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Tarif
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Jenis Kendaraan</label>
                                        <div>
                                            <span class="badge fs-6 px-3 py-2
                                                @if($tarif->jenis_kendaraan == 'motor') bg-success
                                                @elseif($tarif->jenis_kendaraan == 'mobil') bg-primary
                                                @else bg-secondary @endif">
                                                <i class="fas
                                                    @if($tarif->jenis_kendaraan == 'motor') fa-motorcycle
                                                    @elseif($tarif->jenis_kendaraan == 'mobil') fa-car
                                                    @else fa-question-circle @endif me-2"></i>
                                                {{ ucfirst($tarif->jenis_kendaraan) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Harga per Jam</label>
                                        <div class="h4 text-success mb-0">
                                            Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dibuat Pada</label>
                                        <div>
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            {{ $tarif->created_at->format('d F Y \p\u\k\u\l H:i') }}
                                        </div>
                                    </div>

                                    @if($tarif->updated_at != $tarif->created_at)
                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Terakhir Diupdate</label>
                                        <div>
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            {{ $tarif->updated_at->format('d F Y \p\u\k\u\l H:i') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-chart-line me-2"></i>Statistik Penggunaan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Total Transaksi</label>
                                        <div class="h4 text-primary mb-0">
                                            <i class="fas fa-receipt me-2"></i>{{ number_format($totalTransaksi, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">transaksi kendaraan {{ ucfirst($tarif->jenis_kendaraan) }}</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Total Pendapatan</label>
                                        <div class="h4 text-success mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>
                                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">dari transaksi yang sudah selesai</small>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Rata-rata Pendapatan per Transaksi</label>
                                        <div class="h5 text-info mb-0">
                                            @if($totalTransaksi > 0)
                                                <i class="fas fa-calculator me-2"></i>
                                                Rp {{ number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') }}
                                            @else
                                                <i class="fas fa-minus me-2"></i>
                                                Belum ada data
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <a href="{{ route('tarif.edit', $tarif->id) }}" class="btn btn-warning w-100">
                                                <i class="fas fa-edit me-2"></i>Edit Tarif
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('tarif.create') }}" class="btn btn-success w-100">
                                                <i class="fas fa-plus me-2"></i>Tambah Tarif Baru
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <form action="{{ route('tarif.destroy', $tarif->id) }}"
                                                  method="POST"
                                                  class="d-inline w-100"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus tarif ini? Semua data statistik akan hilang.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fas fa-trash me-2"></i>Hapus Tarif
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-info me-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentActivities = DB::table('log_aktivitas')
                            ->join('users', 'log_aktivitas.id_user', '=', 'users.id')
                            ->where('log_aktivitas.aktivitas', 'like', '%'.$tarif->jenis_kendaraan.'%')
                            ->select('log_aktivitas.*', 'users.nama')
                            ->orderBy('log_aktivitas.waktu_aktivitas', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">{{ $activity->waktu_aktivitas->format('d/m/Y H:i') }}</div>
                                    <div class="small">{{ $activity->aktivitas }}</div>
                                    <div class="small text-primary">Oleh: {{ $activity->nama }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada aktivitas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
