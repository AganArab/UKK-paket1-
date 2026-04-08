<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kendaraan - Aplikasi Parkir</title>
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

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .badge-motor {
            background-color: #28a745;
            color: white;
        }

        .badge-mobil {
            background-color: #007bff;
            color: white;
        }

        .badge-lainnya {
            background-color: #6c757d;
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .transaction-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .transaction-item {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-time {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .transaction-icon.parkir {
            background-color: #d4edda;
            color: #155724;
        }

        .transaction-icon.keluar {
            background-color: #f8d7da;
            color: #721c24;
        }

        .empty-transaction {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-transaction i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .vehicle-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            margin: 0 auto 20px auto;
        }

        .vehicle-avatar.motor {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .vehicle-avatar.mobil {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        }

        .vehicle-avatar.lainnya {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .status-badge {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
        }

        .status-parkir {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-keluar {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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
        <!-- Vehicle Info Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-car me-2"></i>
                        Detail Kendaraan
                    </h5>
                    <div>
                        <a href="{{ route('kendaraan.edit', $kendaraan->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $kendaraan->plat_nomor }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Vehicle Avatar and Basic Info -->
                <div class="text-center mb-4">
                    <div class="vehicle-avatar {{ $kendaraan->jenis_kendaraan }}">
                        @if($kendaraan->jenis_kendaraan == 'motor')
                            <i class="fas fa-motorcycle"></i>
                        @elseif($kendaraan->jenis_kendaraan == 'mobil')
                            <i class="fas fa-car"></i>
                        @else
                            <i class="fas fa-question-circle"></i>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $kendaraan->plat_nomor }}</h4>
                    <p class="text-muted mb-2">
                        @if($kendaraan->jenis_kendaraan == 'motor')
                            Sepeda Motor
                        @elseif($kendaraan->jenis_kendaraan == 'mobil')
                            Mobil
                        @else
                            Kendaraan Lainnya
                        @endif
                    </p>
                    <div>
                        @if($kendaraan->jenis_kendaraan == 'motor')
                            <span class="badge badge-motor">
                                <i class="fas fa-motorcycle me-1"></i>Motor
                            </span>
                        @elseif($kendaraan->jenis_kendaraan == 'mobil')
                            <span class="badge badge-mobil">
                                <i class="fas fa-car me-1"></i>Mobil
                            </span>
                        @else
                            <span class="badge badge-lainnya">
                                <i class="fas fa-question-circle me-1"></i>Lainnya
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Plat Nomor</div>
                            <div class="info-value">
                                <i class="fas fa-id-card me-2 text-primary"></i>
                                <span class="badge badge-secondary">{{ $kendaraan->plat_nomor }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Jenis Kendaraan</div>
                            <div class="info-value">
                                @if($kendaraan->jenis_kendaraan == 'motor')
                                    <i class="fas fa-motorcycle me-2 text-success"></i>Sepeda Motor
                                @elseif($kendaraan->jenis_kendaraan == 'mobil')
                                    <i class="fas fa-car me-2 text-primary"></i>Mobil
                                @else
                                    <i class="fas fa-question-circle me-2 text-secondary"></i>Kendaraan Lainnya
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Ditambahkan Pada</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-plus me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($kendaraan->created_at)->format('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Terakhir Update</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-check me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($kendaraan->updated_at)->format('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Parkir Terbaru
                </h5>
            </div>

            <div class="card-body">
                @if($transaksis->count() > 0)
                    @foreach($transaksis as $transaksi)
                        <div class="transaction-item d-flex align-items-start">
                            <div class="transaction-icon
                                @if($transaksi->status == 'masuk') parkir
                                @else keluar @endif">
                                @if($transaksi->status == 'masuk')
                                    <i class="fas fa-parking"></i>
                                @else
                                    <i class="fas fa-sign-out-alt"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">
                                            @if($transaksi->status == 'masuk')
                                                Masuk Parkir
                                            @else
                                                Keluar Parkir
                                            @endif
                                            - {{ $transaksi->nama_area }}
                                        </div>
                                        <div class="transaction-time">
                                            @if($transaksi->status == 'masuk')
                                                Masuk: {{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d F Y, H:i') }}
                                            @else
                                                Masuk: {{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d F Y, H:i') }}
                                                | Keluar: {{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('d F Y, H:i') }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($transaksi->status == 'keluar' && $transaksi->total_bayar > 0)
                                            <div class="fw-semibold text-success">
                                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                                            </div>
                                        @endif
                                        <span class="status-badge
                                            @if($transaksi->status == 'masuk') status-parkir
                                            @else status-keluar @endif">
                                            @if($transaksi->status == 'masuk')
                                                <i class="fas fa-parking me-1"></i>Parkir
                                            @else
                                                <i class="fas fa-sign-out-alt me-1"></i>Keluar
                                            @endif
                                        </span>
                                        @if($transaksi->petugas)
                                            <div class="small text-muted mt-1">
                                                <i class="fas fa-user me-1"></i>{{ $transaksi->petugas }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-transaction">
                        <i class="fas fa-parking"></i>
                        <h6>Belum ada riwayat parkir</h6>
                        <p>Kendaraan ini belum pernah parkir di sistem.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
