@extends('layouts.app')

@section('title', 'Daftar Transaksi Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-receipt text-primary me-2"></i>
                            Daftar Transaksi Parkir
                        </h4>
                        <div class="btn-group" role="group">
                            <a href="{{ route('transaksi.masuk') }}" class="btn btn-success">
                                <i class="fas fa-arrow-right me-2"></i>Parkir Masuk
                            </a>
                            <a href="{{ route('transaksi.keluar') }}" class="btn btn-danger">
                                <i class="fas fa-arrow-left me-2"></i>Parkir Keluar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Area</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Status</th>
                                    <th>Total Bayar</th>
                                    <th>Petugas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $index => $transaksi)
                                    <tr>
                                        <td class="text-center">{{ $transaksis->firstItem() + $index }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $transaksi->plat_nomor }}</strong>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <i class="fas fa-parking text-info me-1"></i>
                                            {{ $transaksi->nama_area }}
                                        </td>
                                        <td>{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($transaksi->waktu_keluar)
                                                {{ $transaksi->waktu_keluar->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaksi->status === 'masuk')
                                                <span class="badge bg-warning">Masuk</span>
                                            @else
                                                <span class="badge bg-success">Keluar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaksi->total_bayar)
                                                <strong class="text-success">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaksi->petugas }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p class="mb-0">Belum ada transaksi parkir</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transaksis->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transaksis->links() }}
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
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection
