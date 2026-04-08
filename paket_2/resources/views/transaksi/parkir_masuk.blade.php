@extends('layouts.app')

@section('title', 'Parkir Masuk')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-arrow-right me-2"></i>
                        Parkir Masuk
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('transaksi.store.masuk') }}" method="POST" id="parkirForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_kendaraan" class="form-label fw-bold">
                                        <i class="fas fa-car text-primary me-1"></i>Kendaraan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="id_kendaraan" name="id_kendaraan" required>
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach($kendaraans as $kendaraan)
                                            <option value="{{ $kendaraan->id }}" 
                                                    data-jenis="{{ $kendaraan->jenis_kendaraan }}"
                                                    data-pemilik="{{ $kendaraan->pemilik }}">
                                                {{ $kendaraan->plat_nomor }} ({{ ucfirst($kendaraan->jenis_kendaraan) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kendaraan')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_area" class="form-label fw-bold">
                                        <i class="fas fa-parking text-success me-1"></i>Area Parkir <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="id_area" name="id_area" required>
                                        <option value="">-- Pilih Area --</option>
                                        @foreach($areas as $area)
                                            @php
                                                $tersedia = $area->kapasitas - $area->kendaraan_parkir;
                                                $persentase = ($area->kendaraan_parkir / $area->kapasitas) * 100;
                                            @endphp
                                            <option value="{{ $area->id }}" data-tersedia="{{ $tersedia }}">
                                                {{ $area->nama_area }} ({{ $tersedia }}/{{ $area->kapasitas }} slot - {{ round($persentase) }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_area')
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
                                            <i class="fas fa-eye text-info me-2"></i>Preview Parkir Masuk
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Kendaraan:</strong>
                                                <div id="preview-kendaraan" class="text-primary">Belum dipilih</div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Area:</strong>
                                                <div id="preview-area" class="text-success">Belum dipilih</div>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Waktu Masuk:</strong>
                                                <div id="preview-waktu" class="text-info">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <span id="waktu-sekarang"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Simpan Parkir Masuk
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
    const kendaraanSelect = document.getElementById('id_kendaraan');
    const areaSelect = document.getElementById('id_area');
    const previewKendaraan = document.getElementById('preview-kendaraan');
    const previewArea = document.getElementById('preview-area');
    const waktuSekarang = document.getElementById('waktu-sekarang');

    function updateWaktu() {
        const now = new Date();
        const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        waktuSekarang.textContent = now.toLocaleDateString('id-ID', options);
    }

    function updatePreview() {
        // Update selected kendaraan
        const kendaraanOption = kendaraanSelect.selectedOptions[0];
        if (kendaraanOption.value) {
            const plat = kendaraanOption.text.split(' (')[0];
            const jenis = kendaraanOption.dataset.jenis;
            const badgeClass = jenis === 'motor' ? 'bg-success' : (jenis === 'mobil' ? 'bg-primary' : 'bg-secondary');
            previewKendaraan.innerHTML = `<span class="badge ${badgeClass}"><i class="fas fa-${jenis === 'motor' ? 'motorcycle' : (jenis === 'mobil' ? 'car' : 'question-circle')} me-1"></i>${plat}</span>`;
        } else {
            previewKendaraan.textContent = 'Belum dipilih';
        }

        // Update selected area
        const areaOption = areaSelect.selectedOptions[0];
        if (areaOption.value) {
            const areaNama = areaOption.text.split(' (')[0];
            const tersedia = areaOption.dataset.tersedia;
            const badgeClass = tersedia > 5 ? 'bg-success' : (tersedia > 0 ? 'bg-warning' : 'bg-danger');
            previewArea.innerHTML = `<span class="badge ${badgeClass}"><i class="fas fa-parking me-1"></i>${areaNama} (${tersedia} slot)</span>`;
        } else {
            previewArea.textContent = 'Belum dipilih';
        }
    }

    kendaraanSelect.addEventListener('change', updatePreview);
    areaSelect.addEventListener('change', updatePreview);

    // Update waktu setiap detik
    updateWaktu();
    updatePreview();
    setInterval(updateWaktu, 1000);

    // Auto-submit jika tombol hijau diklik
    document.querySelector('button[type="submit"]').addEventListener('click', function(e) {
        if (!kendaraanSelect.value || !areaSelect.value) {
            e.preventDefault();
            alert('Silakan pilih kendaraan dan area parkir');
        }
    });
});
</script>
@endsection
