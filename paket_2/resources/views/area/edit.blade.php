@extends('layouts.app')

@section('title', 'Edit Area Parkir')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Edit Area Parkir
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('area.update', $area->id) }}" method="POST" id="areaForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama_area" class="form-label fw-bold">
                                        <i class="fas fa-parking text-primary me-1"></i>Nama Area <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="nama_area"
                                           name="nama_area"
                                           value="{{ old('nama_area', $area->nama_area) }}"
                                           placeholder="Contoh: Area A, Lantai 1, Basement"
                                           maxlength="100"
                                           required>
                                    <div class="form-text">
                                        <small class="text-muted">Nama area parkir yang mudah diingat</small>
                                    </div>
                                    @error('nama_area')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kapasitas" class="form-label fw-bold">
                                        <i class="fas fa-users text-success me-1"></i>Kapasitas <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control"
                                               id="kapasitas"
                                               name="kapasitas"
                                               value="{{ old('kapasitas', $area->kapasitas) }}"
                                               placeholder="50"
                                               min="1"
                                               max="1000"
                                               required>
                                        <span class="input-group-text">kendaraan</span>
                                    </div>
                                    <div class="form-text">
                                        <small class="text-muted">1 - 1000 kendaraan</small>
                                    </div>
                                    @error('kapasitas')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Status Info -->
                        @php
                            $kendaraanParkir = DB::table('transaksi')
                                ->where('id_area', $area->id)
                                ->where('status', 'masuk')
                                ->count();
                        @endphp
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Status Saat Ini:</strong> {{ $kendaraanParkir }}/{{ $area->kapasitas }} kendaraan sedang parkir
                                    @if($kendaraanParkir > 0)
                                        <br><small class="text-muted">Kapasitas baru tidak boleh kurang dari {{ $kendaraanParkir }} kendaraan</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-eye text-info me-2"></i>Preview Area Parkir
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Nama Area:</strong>
                                                <span id="preview-nama" class="text-primary">{{ $area->nama_area }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Kapasitas:</strong>
                                                <span id="preview-kapasitas" class="text-success fw-bold">{{ $area->kapasitas }} kendaraan</span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <strong>Status:</strong>
                                                <span id="preview-status">
                                                    @if($kendaraanParkir >= $area->kapasitas)
                                                        <span class="badge bg-danger">Penuh ({{ $kendaraanParkir }}/{{ $area->kapasitas }} kendaraan)</span>
                                                    @elseif($kendaraanParkir >= $area->kapasitas * 0.8)
                                                        <span class="badge bg-warning">Hampir Penuh ({{ $kendaraanParkir }}/{{ $area->kapasitas }} kendaraan)</span>
                                                    @elseif($kendaraanParkir >= $area->kapasitas * 0.5)
                                                        <span class="badge bg-info">Sedang ({{ $kendaraanParkir }}/{{ $area->kapasitas }} kendaraan)</span>
                                                    @else
                                                        <span class="badge bg-success">Kosong ({{ $kendaraanParkir }}/{{ $area->kapasitas }} kendaraan)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('area.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Update Area
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const namaInput = document.getElementById('nama_area');
    const kapasitasInput = document.getElementById('kapasitas');
    const previewNama = document.getElementById('preview-nama');
    const previewKapasitas = document.getElementById('preview-kapasitas');
    const previewStatus = document.getElementById('preview-status');
    const currentParkir = @json($kendaraanParkir);
    const originalNamaArea = @json($area->nama_area);
    const originalKapasitas = @json($area->kapasitas);

    function updatePreview() {
        const namaValue = namaInput.value.trim();
        previewNama.textContent = namaValue || originalNamaArea;

        const kapasitasValue = parseInt(kapasitasInput.value, 10);
        const capacity = Number.isInteger(kapasitasValue) && kapasitasValue > 0 ? kapasitasValue : originalKapasitas;
        previewKapasitas.textContent = capacity + ' kendaraan';

        const persentase = capacity > 0 ? (currentParkir / capacity) * 100 : 0;
        let statusBadge = '';

        if (persentase >= 100) {
            statusBadge = '<span class="badge bg-danger">Penuh (' + currentParkir + '/' + capacity + ' kendaraan)</span>';
        } else if (persentase >= 80) {
            statusBadge = '<span class="badge bg-warning">Hampir Penuh (' + currentParkir + '/' + capacity + ' kendaraan)</span>';
        } else if (persentase >= 50) {
            statusBadge = '<span class="badge bg-info">Sedang (' + currentParkir + '/' + capacity + ' kendaraan)</span>';
        } else {
            statusBadge = '<span class="badge bg-success">Kosong (' + currentParkir + '/' + capacity + ' kendaraan)</span>';
        }

        previewStatus.innerHTML = statusBadge;
    }

    namaInput.addEventListener('input', updatePreview);
    kapasitasInput.addEventListener('input', updatePreview);
    updatePreview();

    namaInput.addEventListener('input', function() {
        this.value = this.value.replace(/\b\w/g, function(letter) {
            return letter.toUpperCase();
        });
    });
});
</script>
@endsection
