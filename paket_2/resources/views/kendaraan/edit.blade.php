<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kendaraan - Aplikasi Parkir</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border: none;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
            color: #6c757d;
        }

        .form-control:focus + .input-group-text,
        .input-group-text:focus {
            border-color: #667eea;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            flex: 1;
        }

        .radio-option:hover {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }

        .radio-option input[type="radio"] {
            margin: 0;
            width: 18px;
            height: 18px;
        }

        .radio-option.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }

        .vehicle-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-top: 10px;
            border: 2px dashed #dee2e6;
        }

        .vehicle-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }

        .vehicle-icon.motor {
            background-color: #d4edda;
            color: #155724;
        }

        .vehicle-icon.mobil {
            background-color: #cce5ff;
            color: #004085;
        }

        .vehicle-icon.lainnya {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .vehicle-text {
            text-align: center;
        }

        .vehicle-text h5 {
            margin: 0;
            font-weight: 600;
        }

        .vehicle-text p {
            margin: 5px 0 0 0;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-parking me-2"></i>
                Parkir App - Admin Panel
            </a>

            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i>
                    {{ session('user_name') }} ({{ ucfirst(session('user_role')) }})
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Gagal Menyimpan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Edit Kendaraan Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="{{ route('kendaraan.index') }}" class="btn btn-light me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Kendaraan: {{ $kendaraan->plat_nomor }}
                    </h5>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST" id="kendaraanForm">
                    @csrf
                    @method('PUT')

                    <!-- Jenis Kendaraan -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tags me-1"></i>Jenis Kendaraan *
                        </label>
                        <div class="radio-group">
                            <label class="radio-option @if(old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'motor') selected @endif">
                                <input type="radio"
                                       name="jenis_kendaraan"
                                       value="motor"
                                       @if(old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'motor') checked @endif
                                       required>
                                <i class="fas fa-motorcycle fa-2x text-success me-2"></i>
                                <div>
                                    <strong>Motor</strong><br>
                                    <small class="text-muted">Sepeda Motor</small>
                                </div>
                            </label>
                            <label class="radio-option @if(old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'mobil') selected @endif">
                                <input type="radio"
                                       name="jenis_kendaraan"
                                       value="mobil"
                                       @if(old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'mobil') checked @endif>
                                <i class="fas fa-car fa-2x text-primary me-2"></i>
                                <div>
                                    <strong>Mobil</strong><br>
                                    <small class="text-muted">Kendaraan Roda 4</small>
                                </div>
                            </label>
                            <label class="radio-option @if(old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'lainnya') selected @endif">
                                <input type="radio"
                                       name="jenis_kendaraan"
                                       value="lainnya"
                                       @if(old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'lainnya') checked @endif>
                                <i class="fas fa-question-circle fa-2x text-secondary me-2"></i>
                                <div>
                                    <strong>Lainnya</strong><br>
                                    <small class="text-muted">Kendaraan Lain</small>
                                </div>
                            </label>
                        </div>
                        @error('jenis_kendaraan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Plat Nomor -->
                    <div class="form-group">
                        <label class="form-label" for="plat_nomor">
                            <i class="fas fa-id-card me-1"></i>Plat Nomor *
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control text-uppercase @error('plat_nomor') is-invalid @enderror"
                                   id="plat_nomor"
                                   name="plat_nomor"
                                   placeholder="Contoh: B 1234 ABC"
                                   value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}"
                                   maxlength="15"
                                   required>
                            <span class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </div>
                        <small class="text-muted">Format: [Kode Daerah] [Nomor] [Huruf] (contoh: B 1234 ABC)</small>
                        @error('plat_nomor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vehicle Preview -->
                    <div class="vehicle-preview" id="vehiclePreview">
                        <div class="vehicle-icon motor" id="previewIcon">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div class="vehicle-text">
                            <h5 id="previewTitle">Motor</h5>
                            <p id="previewPlat">{{ $kendaraan->plat_nomor }}</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Kendaraan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Radio button selection styling
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all options in the same group
                document.querySelectorAll(`input[name="${this.name}"]`).forEach(r => {
                    r.closest('.radio-option').classList.remove('selected');
                });
                // Add selected class to the checked option
                if (this.checked) {
                    this.closest('.radio-option').classList.add('selected');
                    updatePreview(this.value);
                }
            });
        });

        // Initialize selected state on page load
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            radio.closest('.radio-option').classList.add('selected');
            updatePreview(radio.value);
        });

        // Update vehicle preview
        function updatePreview(jenis) {
            const icon = document.getElementById('previewIcon');
            const title = document.getElementById('previewTitle');

            if (jenis === 'motor') {
                icon.className = 'vehicle-icon motor';
                icon.innerHTML = '<i class="fas fa-motorcycle"></i>';
                title.textContent = 'Motor';
            } else if (jenis === 'mobil') {
                icon.className = 'vehicle-icon mobil';
                icon.innerHTML = '<i class="fas fa-car"></i>';
                title.textContent = 'Mobil';
            } else {
                icon.className = 'vehicle-icon lainnya';
                icon.innerHTML = '<i class="fas fa-question-circle"></i>';
                title.textContent = 'Lainnya';
            }
        }

        // Update preview when plat nomor changes
        document.getElementById('plat_nomor').addEventListener('input', function() {
            const plat = document.getElementById('previewPlat');
            plat.textContent = this.value || 'Plat nomor akan muncul di sini';
        });

        // Auto-format plat nomor
        document.getElementById('plat_nomor').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            // Remove any existing spaces and special characters except letters and numbers
            value = value.replace(/[^A-Z0-9]/g, '');

            // Add space after first character if it's a letter
            if (value.length > 1 && /^[A-Z]$/.test(value[0])) {
                value = value[0] + ' ' + value.slice(1);
            }

            // Add space before last 3 characters if they are letters
            if (value.length > 3 && /^[A-Z]{3}$/.test(value.slice(-3))) {
                value = value.slice(0, -3) + ' ' + value.slice(-3);
            }

            e.target.value = value;
        });
    </script>
</body>
</html>
