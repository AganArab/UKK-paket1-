# 🔐 AuthController - Login/Logout Documentation

## 📦 File yang Dibuat

1. **AuthController.php** - Controller untuk login/logout
2. **login.blade.php** - View untuk halaman login
3. **UserSeeder.php** - Seeder untuk user demo
4. **CheckAuth.php** - Middleware untuk authentikasi
5. **CheckRole.php** - Middleware untuk otorisasi role

## 🚀 Langkah Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Jalankan Seeder (Membuat User Demo)
```bash
php artisan db:seed
```

### 3. Update Middleware di `bootstrap/app.php`
```php
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => CheckAuth::class,
            'role' => CheckRole::class,
        ]);
    })
    // ... rest of configuration
```

## 📝 Akun Demo

Setelah menjalankan seeder, Anda bisa login dengan akun-akun berikut:

| Email | Password | Role |
|-------|----------|------|
| admin@parkir.test | password123 | admin |
| petugas@parkir.test | password123 | petugas |
| owner@parkir.test | password123 | owner |

## 🛣️ Contoh Penggunaan Routes

### Basic Auth Routes (Sudah Ada di `routes/web.php`)
```php
// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Process login
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

### Protected Routes dengan Middleware

```php
// Route yang hanya untuk authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route hanya untuk Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::post('/admin/users', [AdminController::class, 'storeUser']);
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
});

// Route hanya untuk Petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard']);
    Route::post('/petugas/transaksi', [PetugasController::class, 'storeTransaksi']);
    Route::get('/petugas/cetak-struk/{id}', [PetugasController::class, 'cetakStruk']);
});

// Route hanya untuk Owner
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard']);
    Route::get('/owner/rekap-transaksi', [OwnerController::class, 'rekapTransaksi']);
});

// Route untuk multiple roles
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index']);
});
```

## 🔄 Fitur AuthController

### 1. Login (`login()`)
- Validasi email dan password
- Hash password verification
- Simpan user data ke session
- Log aktivitas login ke database
- Redirect berdasarkan role

### 2. Logout (`logout()`)
- Log aktivitas logout ke database
- Hapus semua session user
- Redirect ke halaman login

### 3. Role Redirect (`redirectByRole()`)
Automatic redirect sesuai role:
- **Admin** → `/admin/dashboard`
- **Petugas** → `/petugas/dashboard`
- **Owner** → `/owner/dashboard`

## 💾 Session Variables

Setelah login, session akan berisi:

```php
session('user_id')      // ID user
session('user_name')    // Nama user
session('user_email')   // Email user
session('user_role')    // Role user (admin/petugas/owner)
```

## 🔒 Keamanan

✅ Password di-hash menggunakan `Hash::make()`
✅ Password di-verify dengan `Hash::check()`
✅ Status user aktif dicek saat login
✅ Session regeneration untuk mencegah session fixation
✅ Log aktivitas untuk setiap login/logout
✅ Role-based access control via middleware

## 🎨 Tampilan Login

Halaman login memiliki desain modern dengan:
- Background gradient purple
- Form validation feedback
- Bootstrap 5 styling
- Responsive design
- Demo akun info di footer

## 📋 Database Tables Used

**users** table:
- id (primary key)
- name
- email (unique)
- password (hashed)
- role (enum: admin, petugas, owner)
- status_aktif (0/1)
- created_at, updated_at

**log_aktivitas** table:
- Mencatat setiap login/logout

## 🐛 Testing

Untuk test login:
1. Buka `http://localhost:8000/login`
2. Masukkan email: `admin@parkir.test`
3. Masukkan password: `password123`
4. Klik Login

Untuk logout, gunakan route POST ke `/logout` (biasanya dari navbar dengan form).

## 📌 Catatan Penting

1. **Jangan gunakan Eloquent** - Semua query menggunakan `DB::table()`
2. **Session berbasis file** - Default Laravel menggunakan file driver
3. **CSRF Protection** - Form sudah include `@csrf`
4. **Validasi Server** - Semua input di-validate di server-side
5. **Password tidak boleh ditampilkan** - Gunakan tipe password input

## 🔧 Customization

### Mengubah redirect URL setelah login:
Edit method `redirectByRole()` di AuthController.php

### Menambah role baru:
1. Update `enum` di migration file users table
2. Tambah case di method `redirectByRole()`
3. Buat middleware untuk role baru jika diperlukan

### Mengganti password demo:
Edit UserSeeder.php dan ubah `Hash::make('password123')`
