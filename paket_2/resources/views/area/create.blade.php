@extends('layouts.app')

@section('title', 'Tambah Area Parkir')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus text-primary me-2"></i>
                        Tambah Area Parkir Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('area.store') }}" method="POST" id="areaForm">
                        @csrf

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
                                           value="{{ old('nama_area') }}"
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
                                               value="{{ old('kapasitas') }}"
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
                                                <span id="preview-nama" class="text-primary">Nama area akan muncul di sini</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Kapasitas:</strong>
                                                <span id="preview-kapasitas" class="text-success fw-bold">0 kendaraan</span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <strong>Status Awal:</strong>
                                                <span class="badge bg-success">Kosong (0/0 kendaraan)</span>
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
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Area
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

    function updatePreview() {
        // Update nama area preview
        const namaValue = namaInput.value.trim();
        if (namaValue) {
            previewNama.textContent = namaValue;
        } else {
            previewNama.textContent = 'Nama area akan muncul di sini';
        }

        // Update kapasitas preview
        const kapasitasValue = kapasitasInput.value;
        if (kapasitasValue && kapasitasValue > 0) {
            previewKapasitas.textContent = kapasitasValue + ' kendaraan';
        } else {
            previewKapasitas.textContent = '0 kendaraan';
        }
    }

    // Event listeners
    namaInput.addEventListener('input', updatePreview);
    kapasitasInput.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();

    // Auto-capitalize nama area
    namaInput.addEventListener('input', function() {
        this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
    });
});
</script>
@endsection
