<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Aplikasi Parkir</title>
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
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .radio-option:hover {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }

        .radio-option input[type="radio"] {
            margin: 0;
        }

        .radio-option.selected {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
        }

        .password-container {
            position: relative;
        }

        .password-note {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
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

        <!-- Edit User Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="{{ route('users.index') }}" class="btn btn-light me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Edit User: {{ $user->name }}
                    </h5>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST" id="userForm">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <i class="fas fa-user me-1"></i>Nama Lengkap *
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope me-1"></i>Email *
                        </label>
                        <div class="input-group">
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   placeholder="Masukkan alamat email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <i class="fas fa-lock me-1"></i>Password Baru
                        </label>
                        <div class="password-container">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Kosongkan jika tidak ingin mengubah password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                        <div class="password-note">
                            <i class="fas fa-info-circle me-1"></i>
                            Kosongkan jika tidak ingin mengubah password. Minimal 6 karakter jika diisi.
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tag me-1"></i>Role *
                        </label>
                        <div class="radio-group">
                            <label class="radio-option @if(old('role', $user->role) == 'admin') selected @endif">
                                <input type="radio"
                                       name="role"
                                       value="admin"
                                       @if(old('role', $user->role) == 'admin') checked @endif
                                       required>
                                <i class="fas fa-crown text-warning me-1"></i>
                                <span>Admin</span>
                            </label>
                            <label class="radio-option @if(old('role', $user->role) == 'petugas') selected @endif">
                                <input type="radio"
                                       name="role"
                                       value="petugas"
                                       @if(old('role', $user->role) == 'petugas') checked @endif>
                                <i class="fas fa-user-tie text-success me-1"></i>
                                <span>Petugas</span>
                            </label>
                            <label class="radio-option @if(old('role', $user->role) == 'owner') selected @endif">
                                <input type="radio"
                                       name="role"
                                       value="owner"
                                       @if(old('role', $user->role) == 'owner') checked @endif>
                                <i class="fas fa-building text-info me-1"></i>
                                <span>Owner</span>
                            </label>
                        </div>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-toggle-on me-1"></i>Status Aktif *
                        </label>
                        <div class="radio-group">
                            <label class="radio-option @if(old('status_aktif', $user->status_aktif) == 1) selected @endif">
                                <input type="radio"
                                       name="status_aktif"
                                       value="1"
                                       @if(old('status_aktif', $user->status_aktif) == 1) checked @endif
                                       required>
                                <i class="fas fa-check-circle text-success me-1"></i>
                                <span>Aktif</span>
                            </label>
                            <label class="radio-option @if(old('status_aktif', $user->status_aktif) == 0) selected @endif">
                                <input type="radio"
                                       name="status_aktif"
                                       value="0"
                                       @if(old('status_aktif', $user->status_aktif) == 0) checked @endif>
                                <i class="fas fa-times-circle text-secondary me-1"></i>
                                <span>Nonaktif</span>
                            </label>
                        </div>
                        @error('status_aktif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

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
                }
            });
        });

        // Initialize selected state on page load
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            radio.closest('.radio-option').classList.add('selected');
        });
    </script>
</body>
</html>
