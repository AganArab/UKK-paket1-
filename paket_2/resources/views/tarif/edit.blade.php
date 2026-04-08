@extends('layouts.app')

@section('title', 'Edit Tarif Parkir')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Edit Tarif Parkir
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tarif.update', $tarif->id) }}" method="POST" id="tarifForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-car text-primary me-1"></i>Jenis Kendaraan
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kendaraan" id="motor" value="motor" {{ $tarif->jenis_kendaraan == 'motor' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="motor">
                                                    <i class="fas fa-motorcycle text-success me-1"></i>Motor
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kendaraan" id="mobil" value="mobil" {{ $tarif->jenis_kendaraan == 'mobil' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="mobil">
                                                    <i class="fas fa-car text-primary me-1"></i>Mobil
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kendaraan" id="lainnya" value="lainnya" {{ $tarif->jenis_kendaraan == 'lainnya' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="lainnya">
                                                    <i class="fas fa-question-circle text-secondary me-1"></i>Lainnya
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        <small class="text-muted">Jenis kendaraan tidak dapat diubah setelah tarif dibuat</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga_per_jam" class="form-label fw-bold">
                                        <i class="fas fa-dollar-sign text-success me-1"></i>Harga per Jam <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               class="form-control"
                                               id="harga_per_jam"
                                               name="harga_per_jam"
                                               value="{{ old('harga_per_jam', $tarif->harga_per_jam) }}"
                                               placeholder="10000"
                                               min="1000"
                                               max="100000"
                                               required>
                                    </div>
                                    <div class="form-text">
                                        <small class="text-muted">Minimal Rp 1.000, maksimal Rp 100.000 per jam</small>
                                    </div>
                                    @error('harga_per_jam')
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
                                            <i class="fas fa-eye text-info me-2"></i>Preview Tarif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Jenis Kendaraan:</strong>
                                                <span id="preview-jenis">
                                                    @if($tarif->jenis_kendaraan == 'motor')
                                                        <i class="fas fa-motorcycle text-success me-1"></i>Motor
                                                    @elseif($tarif->jenis_kendaraan == 'mobil')
                                                        <i class="fas fa-car text-primary me-1"></i>Mobil
                                                    @else
                                                        <i class="fas fa-question-circle text-secondary me-1"></i>Lainnya
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Harga per Jam:</strong>
                                                <span id="preview-harga" class="text-success fw-bold">
                                                    Rp {{ number_format($tarif->harga_per_jam, 0, ',', '.') }}
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
                                    <a href="{{ route('tarif.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Update Tarif
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
    const hargaInput = document.getElementById('harga_per_jam');
    const previewHarga = document.getElementById('preview-harga');

    function updatePreview() {
        // Update harga preview
        const hargaValue = hargaInput.value;
        if (hargaValue) {
            const formattedHarga = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(hargaValue);
            previewHarga.textContent = formattedHarga;
        } else {
            previewHarga.textContent = 'Rp 0';
        }
    }

    // Event listeners
    hargaInput.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();

    // Format harga input on blur
    hargaInput.addEventListener('blur', function() {
        const value = this.value;
        if (value) {
            this.value = parseInt(value).toString();
        }
    });
});
</script>
@endsection
