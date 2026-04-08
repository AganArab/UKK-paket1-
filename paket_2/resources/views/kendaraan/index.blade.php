<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kendaraan - Aplikasi Parkir</title>
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

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 12px;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
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

        .alert {
            border-radius: 10px;
            border: none;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: #667eea;
            border-color: #667eea;
            border-radius: 6px !important;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: #667eea;
            color: white;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item i {
            width: 16px;
            margin-right: 8px;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .vehicle-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Kendaraan Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-car me-2"></i>
                        Daftar Kendaraan
                    </h5>
                    <a href="{{ route('kendaraan.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Kendaraan
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if($kendaraans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Plat Nomor</th>
                                    <th>Ditambahkan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kendaraans as $kendaraan)
                                    <tr>
                                        <td>{{ $loop->iteration + (($kendaraans->currentPage() - 1) * $kendaraans->perPage()) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="vehicle-icon
                                                    @if($kendaraan->jenis_kendaraan == 'motor') motor
                                                    @elseif($kendaraan->jenis_kendaraan == 'mobil') mobil
                                                    @else lainnya @endif">
                                                    @if($kendaraan->jenis_kendaraan == 'motor')
                                                        <i class="fas fa-motorcycle"></i>
                                                    @elseif($kendaraan->jenis_kendaraan == 'mobil')
                                                        <i class="fas fa-car"></i>
                                                    @else
                                                        <i class="fas fa-question-circle"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>
                                                        @if($kendaraan->jenis_kendaraan == 'motor')
                                                            Motor
                                                        @elseif($kendaraan->jenis_kendaraan == 'mobil')
                                                            Mobil
                                                        @else
                                                            Lainnya
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($kendaraan->jenis_kendaraan == 'motor') badge-motor
                                                @elseif($kendaraan->jenis_kendaraan == 'mobil') badge-mobil
                                                @else badge-lainnya @endif">
                                                {{ $kendaraan->plat_nomor }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($kendaraan->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('kendaraan.show', $kendaraan->id) }}"
                                                   class="btn btn-info btn-sm"
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('kendaraan.edit', $kendaraan->id) }}"
                                                   class="btn btn-warning btn-sm"
                                                   title="Edit Kendaraan">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $kendaraan->plat_nomor }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Hapus Kendaraan">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $kendaraans->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-car"></i>
                        <h5>Belum ada kendaraan</h5>
                        <p>Belum ada kendaraan yang terdaftar dalam sistem.</p>
                        <a href="{{ route('kendaraan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Kendaraan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
