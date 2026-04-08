<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - Aplikasi Parkir</title>
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

        .badge-admin {
            background-color: #dc3545;
            color: white;
        }

        .badge-petugas {
            background-color: #28a745;
            color: white;
        }

        .badge-owner {
            background-color: #ffc107;
            color: black;
        }

        .badge-aktif {
            background-color: #28a745;
            color: white;
        }

        .badge-nonaktif {
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

        .activity-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .activity-icon.login {
            background-color: #d4edda;
            color: #155724;
        }

        .activity-icon.logout {
            background-color: #f8d7da;
            color: #721c24;
        }

        .activity-icon.create {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .activity-icon.update {
            background-color: #fff3cd;
            color: #856404;
        }

        .activity-icon.delete {
            background-color: #f8d7da;
            color: #721c24;
        }

        .empty-activity {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-activity i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            margin: 0 auto 20px auto;
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
        <!-- User Info Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Detail User
                    </h5>
                    <div>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        @if($user->id != session('user_id'))
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- User Avatar and Basic Info -->
                <div class="text-center mb-4">
                    <div class="user-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <div>
                        @if($user->role == 'admin')
                            <span class="badge badge-admin">
                                <i class="fas fa-crown me-1"></i>Administrator
                            </span>
                        @elseif($user->role == 'petugas')
                            <span class="badge badge-petugas">
                                <i class="fas fa-user-tie me-1"></i>Petugas Parkir
                            </span>
                        @else
                            <span class="badge badge-owner">
                                <i class="fas fa-building me-1"></i>Pemilik
                            </span>
                        @endif
                        @if($user->status_aktif == 1)
                            <span class="badge badge-aktif ms-2">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge badge-nonaktif ms-2">
                                <i class="fas fa-times-circle me-1"></i>Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">
                                <i class="fas fa-user me-2 text-primary"></i>
                                {{ $user->name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Role</div>
                            <div class="info-value">
                                @if($user->role == 'admin')
                                    <i class="fas fa-crown me-2 text-warning"></i>Administrator
                                @elseif($user->role == 'petugas')
                                    <i class="fas fa-user-tie me-2 text-success"></i>Petugas Parkir
                                @else
                                    <i class="fas fa-building me-2 text-info"></i>Pemilik
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                @if($user->status_aktif == 1)
                                    <i class="fas fa-check-circle me-2 text-success"></i>Aktif
                                @else
                                    <i class="fas fa-times-circle me-2 text-secondary"></i>Nonaktif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Dibuat Pada</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-plus me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Terakhir Update</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-check me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($user->updated_at)->format('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Log Aktivitas Terbaru
                </h5>
            </div>

            <div class="card-body">
                @if($aktivitas->count() > 0)
                    @foreach($aktivitas as $activity)
                        <div class="activity-item d-flex align-items-start">
                            <div class="activity-icon
                                @if(str_contains($activity->aktivitas, 'Login'))
                                    login
                                @elseif(str_contains($activity->aktivitas, 'Logout'))
                                    logout
                                @elseif(str_contains($activity->aktivitas, 'Menambah'))
                                    create
                                @elseif(str_contains($activity->aktivitas, 'Mengupdate'))
                                    update
                                @elseif(str_contains($activity->aktivitas, 'Menghapus'))
                                    delete
                                @endif">
                                @if(str_contains($activity->aktivitas, 'Login'))
                                    <i class="fas fa-sign-in-alt"></i>
                                @elseif(str_contains($activity->aktivitas, 'Logout'))
                                    <i class="fas fa-sign-out-alt"></i>
                                @elseif(str_contains($activity->aktivitas, 'Menambah'))
                                    <i class="fas fa-plus"></i>
                                @elseif(str_contains($activity->aktivitas, 'Mengupdate'))
                                    <i class="fas fa-edit"></i>
                                @elseif(str_contains($activity->aktivitas, 'Menghapus'))
                                    <i class="fas fa-trash"></i>
                                @else
                                    <i class="fas fa-info-circle"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $activity->aktivitas }}</div>
                                <div class="activity-time">
                                    {{ \Carbon\Carbon::parse($activity->waktu_aktivitas)->format('d F Y, H:i:s') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-activity">
                        <i class="fas fa-inbox"></i>
                        <h6>Belum ada aktivitas</h6>
                        <p>User ini belum melakukan aktivitas apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
