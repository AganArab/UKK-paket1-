@extends('layouts.app')

@section('title', 'Detail Area Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-parking text-primary me-2"></i>
                            Detail Area Parkir
                        </h4>
                        <div>
                            <a href="{{ route('area.edit', $area->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('area.index') }}" class="btn btn-secondary btn-sm">
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
                                        <i class="fas fa-info-circle me-2"></i>Informasi Area
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Area</label>
                                        <div class="h5 text-primary mb-0">
                                            <i class="fas fa-parking me-2"></i>{{ $area->nama_area }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kapasitas</label>
                                        <div class="h5 text-success mb-0">
                                            <i class="fas fa-users me-2"></i>{{ $area->kapasitas }} kendaraan
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status Saat Ini</label>
                                        <div>
                                            @php
                                                $persentase = $area->kapasitas > 0 ? ($kendaraanSekarang / $area->kapasitas) * 100 : 0;
                                            @endphp
                                            @if($persentase >= 100)
                                                <span class="badge fs-6 px-3 py-2 bg-danger">Penuh</span>
                                            @elseif($persentase >= 80)
                                                <span class="badge fs-6 px-3 py-2 bg-warning">Hampir Penuh</span>
                                            @elseif($persentase >= 50)
                                                <span class="badge fs-6 px-3 py-2 bg-info">Sedang</span>
                                            @else
                                                <span class="badge fs-6 px-3 py-2 bg-success">Kosong</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kendaraan Saat Ini</label>
                                        <div class="h5 mb-0">
                                            <span class="text-warning">{{ $kendaraanSekarang }}</span> / {{ $area->kapasitas }} kendaraan
                                        </div>
                                        <small class="text-muted">{{ $kapasitasTersedia }} slot tersedia</small>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Dibuat Pada</label>
                                        <div>
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            {{ $area->created_at->format('d F Y \p\u\k\u\l H:i') }}
                                        </div>
                                    </div>
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
                                        <small class="text-muted">transaksi selesai</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Total Pendapatan</label>
                                        <div class="h4 text-success mb-0">
                                            <i class="fas fa-money-bill-wave me-2"></i>
                                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">dari transaksi selesai</small>
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

                    <!-- Kendaraan Parkir Saat Ini -->
                    @if($kendaraanParkir->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-car me-2"></i>Kendaraan Parkir Saat Ini ({{ $kendaraanParkir->count() }})
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Plat Nomor</th>
                                                    <th>Jenis</th>
                                                    <th>Petugas</th>
                                                    <th>Waktu Masuk</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kendaraanParkir as $kendaraan)
                                                <tr>
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
                                                    <td>{{ $kendaraan->petugas }}</td>
                                                    <td>{{ $kendaraan->waktu_masuk->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                            <a href="{{ route('area.edit', $area->id) }}" class="btn btn-warning w-100">
                                                <i class="fas fa-edit me-2"></i>Edit Area
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('area.create') }}" class="btn btn-success w-100">
                                                <i class="fas fa-plus me-2"></i>Tambah Area Baru
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <form action="{{ route('area.destroy', $area->id) }}"
                                                  method="POST"
                                                  class="d-inline w-100"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus area parkir ini? Semua data statistik akan hilang.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fas fa-trash me-2"></i>Hapus Area
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
            <!-- Capacity Visualization -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-info me-2"></i>Visualisasi Kapasitas
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="position-relative d-inline-block">
                            <svg width="150" height="150" class="position-relative">
                                <!-- Background circle -->
                                <circle cx="75" cy="75" r="60" fill="none" stroke="#e9ecef" stroke-width="15"/>
                                <!-- Progress circle -->
                                @php
                                    $strokeDasharray = 376.99; // 2 * π * 60
                                    $strokeDashoffset = $strokeDasharray - ($strokeDasharray * $persentase / 100);
                                @endphp
                                <circle cx="75" cy="75" r="60" fill="none" stroke="#007bff" stroke-width="15"
                                        stroke-dasharray="{{ $strokeDasharray }}"
                                        stroke-dashoffset="{{ $strokeDashoffset }}"
                                        transform="rotate(-90 75 75)"
                                        stroke-linecap="round"/>
                            </svg>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <div class="h4 mb-0 text-primary">{{ round($persentase) }}%</div>
                                <small class="text-muted">Terisi</small>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h6 text-success mb-0">{{ $kapasitasTersedia }}</div>
                            <small class="text-muted">Tersedia</small>
                        </div>
                        <div class="col-6">
                            <div class="h6 text-warning mb-0">{{ $kendaraanSekarang }}</div>
                            <small class="text-muted">Terpakai</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-info me-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentActivities = DB::table('log_aktivitas')
                            ->join('users', 'log_aktivitas.id_user', '=', 'users.id')
                            ->where('log_aktivitas.aktivitas', 'like', '%'.$area->nama_area.'%')
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
