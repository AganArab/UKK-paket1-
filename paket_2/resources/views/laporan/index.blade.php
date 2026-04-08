@extends('layouts.app')

@section('title', 'Laporan Pemasukan Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Laporan Pemasukan Parkir
                        </h4>
                        <small class="text-muted">Filter berdasarkan tanggal waktu keluar transaksi</small>
                    </div>
                    <div>
                        <span class="badge bg-success fs-6">
                            Total Pemasukan: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="dari" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="dari" name="dari" value="{{ $dari }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sampai" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="sampai" name="sampai" value="{{ $sampai }}" required>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Area</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Total Bayar</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporans as $index => $laporan)
                                    <tr>
                                        <td class="text-center">{{ $laporans->firstItem() + $index }}</td>
                                        <td><strong class="text-primary">{{ $laporan->plat_nomor }}</strong></td>
                                        <td>
                                            <span class="badge @if($laporan->jenis_kendaraan == 'motor') bg-success @elseif($laporan->jenis_kendaraan == 'mobil') bg-primary @else bg-secondary @endif">
                                                <i class="fas @if($laporan->jenis_kendaraan == 'motor') fa-motorcycle @elseif($laporan->jenis_kendaraan == 'mobil') fa-car @else fa-question-circle @endif me-1"></i>
                                                {{ ucfirst($laporan->jenis_kendaraan) }}
                                            </span>
                                        </td>
                                        <td>{{ $laporan->nama_area }}</td>
                                        <td>{{ \Carbon\Carbon::parse($laporan->waktu_masuk)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($laporan->waktu_keluar)->format('d/m/Y H:i') }}</td>
                                        <td><strong class="text-success">Rp {{ number_format($laporan->total_bayar, 0, ',', '.') }}</strong></td>
                                        <td>{{ $laporan->petugas }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            Belum ada data laporan untuk rentang tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($laporans->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $laporans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection