@extends('layouts.app')

@section('title', 'Daftar Tarif Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-dollar-sign text-primary me-2"></i>
                            Daftar Tarif Parkir
                        </h4>
                        <a href="{{ route('tarif.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Tarif
                        </a>
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
                                    <th>Jenis Kendaraan</th>
                                    <th>Harga per Jam</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tarifs as $index => $tarif)
                                    <tr>
                                        <td class="text-center">{{ $tarifs->firstItem() + $index }}</td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>{{ $tarif->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('tarif.show', $tarif->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tarif.edit', $tarif->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit Tarif">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tarif.destroy', $tarif->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus tarif ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Hapus Tarif">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p class="mb-0">Belum ada tarif yang ditambahkan</p>
                                                <a href="{{ route('tarif.create') }}" class="btn btn-primary btn-sm mt-2">
                                                    <i class="fas fa-plus me-1"></i>Tambah Tarif Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($tarifs->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tarifs->links() }}
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
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection
