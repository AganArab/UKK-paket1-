@extends('layouts.app')

@section('title', 'Daftar Area Parkir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-parking text-primary me-2"></i>
                            Daftar Area Parkir
                        </h4>
                        <a href="{{ route('area.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Area
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
                                    <th>Nama Area</th>
                                    <th>Kapasitas</th>
                                    <th>Kendaraan Parkir</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($areas as $index => $area)
                                    <tr>
                                        <td class="text-center">{{ $areas->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-parking text-primary me-2"></i>
                                                <strong>{{ $area->nama_area }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $area->kapasitas }} kendaraan</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $area->kendaraan_parkir }} kendaraan</span>
                                        </td>
                                        <td>
                                            @php
                                                $persentase = $area->kapasitas > 0 ? ($area->kendaraan_parkir / $area->kapasitas) * 100 : 0;
                                            @endphp
                                            @if($persentase >= 100)
                                                <span class="badge bg-danger">Penuh</span>
                                            @elseif($persentase >= 80)
                                                <span class="badge bg-warning">Hampir Penuh</span>
                                            @elseif($persentase >= 50)
                                                <span class="badge bg-info">Sedang</span>
                                            @else
                                                <span class="badge bg-success">Kosong</span>
                                            @endif
                                        </td>
                                        <td>{{ $area->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('area.show', $area->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('area.edit', $area->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit Area">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('area.destroy', $area->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus area parkir ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Hapus Area">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-parking fa-3x mb-3"></i>
                                                <p class="mb-0">Belum ada area parkir yang ditambahkan</p>
                                                <a href="{{ route('area.create') }}" class="btn btn-primary btn-sm mt-2">
                                                    <i class="fas fa-plus me-1"></i>Tambah Area Parkir Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($areas->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $areas->links() }}
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
