# 🔐 UserController CRUD - Dokumentasi Lengkap

## 📦 File yang Dibuat

1. **UserController.php** - Controller CRUD lengkap untuk users
2. **users/index.blade.php** - View daftar users dengan pagination
3. **users/create.blade.php** - View form tambah user
4. **users/edit.blade.php** - View form edit user
5. **users/show.blade.php** - View detail user + log aktivitas
6. **web.php** - Routes untuk user management (hanya admin)

## 🚀 Setup & Requirements

### 1. Pastikan Migration Sudah Dijalankan
```bash
php artisan migrate
```

### 2. Jalankan Seeder untuk User Demo
```bash
php artisan db:seed
```

### 3. Update `bootstrap/app.php` - Register Middleware
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
```

### 4. Login sebagai Admin
Gunakan akun admin untuk mengakses user management:
- Email: `admin@parkir.test`
- Password: `password123`

## 🛣️ Routes & URL Access

| Method | Route | Controller Method | Description |
|--------|-------|-------------------|-------------|
| GET | `/users` | `index()` | Daftar semua users |
| GET | `/users/create` | `create()` | Form tambah user |
| POST | `/users` | `store()` | Simpan user baru |
| GET | `/users/{id}` | `show()` | Detail user |
| GET | `/users/{id}/edit` | `edit()` | Form edit user |
| PUT | `/users/{id}` | `update()` | Update user |
| DELETE | `/users/{id}` | `destroy()` | Hapus user |

### 🔒 Route Protection
Semua routes user management **hanya bisa diakses oleh Admin**:
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```

## 📋 Fitur UserController

### 1. **index()** - Daftar Users
- ✅ Tampilkan semua users dengan pagination (10 per halaman)
- ✅ Kolom: ID, Nama, Email, Role, Status, Dibuat, Aksi
- ✅ Badge untuk role (Admin/Petugas/Owner) dan status (Aktif/Nonaktif)
- ✅ Tombol aksi: Lihat, Edit, Hapus (kecuali user sendiri)
- ✅ Search & filter (bisa ditambahkan nanti)

### 2. **create()** - Form Tambah User
- ✅ Form dengan validasi client-side & server-side
- ✅ Input: Nama, Email, Password, Role, Status Aktif
- ✅ Radio button untuk Role dan Status
- ✅ Password toggle (show/hide)
- ✅ Validasi real-time

### 3. **store()** - Simpan User Baru
- ✅ Validasi input lengkap
- ✅ Hash password dengan `Hash::make()`
- ✅ Cek email unique
- ✅ Log aktivitas ke database
- ✅ Redirect dengan success message

### 4. **show()** - Detail User
- ✅ Tampilkan semua info user
- ✅ Avatar dengan inisial nama
- ✅ Badge role dan status
- ✅ Log aktivitas terbaru (10 terakhir)
- ✅ Tombol Edit & Hapus (dengan proteksi)

### 5. **edit()** - Form Edit User
- ✅ Pre-fill data user yang akan diedit
- ✅ Password optional (kosongkan jika tidak ingin ubah)
- ✅ Validasi email unique (exclude user sendiri)
- ✅ Radio button dengan value yang sudah terpilih

### 6. **update()** - Update User
- ✅ Validasi input
- ✅ Update password hanya jika diisi
- ✅ Track perubahan untuk log aktivitas
- ✅ Log detail perubahan (nama, email, role, status, password)
- ✅ Redirect dengan success message

### 7. **destroy()** - Hapus User
- ✅ Proteksi: tidak bisa hapus user yang sedang login
- ✅ Konfirmasi JavaScript sebelum hapus
- ✅ Log aktivitas penghapusan
- ✅ Redirect dengan success message

## ✅ Validasi Input

### Store (Tambah User Baru)
```php
'name' => 'required|string|max:50',
'email' => 'required|email|unique:users,email|max:100',
'password' => 'required|string|min:6|max:255',
'role' => 'required|in:admin,petugas,owner',
'status_aktif' => 'required|in:0,1',
```

### Update (Edit User)
```php
'name' => 'required|string|max:50',
'email' => 'required|email|max:100|unique:users,email,' . $id,
'password' => 'nullable|string|min:6|max:255', // Optional
'role' => 'required|in:admin,petugas,owner',
'status_aktif' => 'required|in:0,1',
```

## 🔐 Keamanan & Proteksi

### 1. **Authentication Required**
- Semua routes butuh login (`auth` middleware)

### 2. **Role-Based Access**
- Hanya Admin yang bisa akses (`role:admin` middleware)

### 3. **Self-Protection**
- User tidak bisa hapus akun sendiri
- Tombol hapus tidak muncul untuk user sendiri

### 4. **Input Validation**
- Server-side validation dengan custom error messages
- Client-side validation dengan HTML5 attributes
- SQL injection protection (Laravel built-in)

### 5. **Password Security**
- Password di-hash dengan `Hash::make()`
- Password tidak pernah ditampilkan di form
- Password optional saat edit

## 📊 Database Operations

### Menggunakan `DB::table()` (Tidak Eloquent)

```php
// Select dengan pagination
$users = DB::table('users')
    ->select('id', 'name', 'email', 'role', 'status_aktif', 'created_at')
    ->orderBy('created_at', 'desc')
    ->paginate(10);

// Insert user baru
$userId = DB::table('users')->insertGetId([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => $request->role,
    'status_aktif' => $request->status_aktif,
    'created_at' => now(),
    'updated_at' => now(),
]);

// Update user
DB::table('users')
    ->where('id', $id)
    ->update($updateData);

// Delete user
DB::table('users')->where('id', $id)->delete();

// Join dengan log aktivitas
$aktivitas = DB::table('log_aktivitas')
    ->where('id_user', $id)
    ->orderBy('waktu_aktivitas', 'desc')
    ->limit(10)
    ->get();
```

## 📝 Log Aktivitas

Setiap aksi dicatat ke tabel `log_aktivitas`:

### Login/Logout
- "Login ke aplikasi"
- "Logout dari aplikasi"

### CRUD Users
- "Menambah user baru: {nama} ({email})"
- "Mengupdate user {nama}: {detail_perubahan}"
- "Menghapus user: {nama} ({email})"

## 🎨 UI/UX Features

### 1. **Responsive Design**
- Bootstrap 5 responsive grid
- Mobile-friendly layout
- Adaptive table untuk mobile

### 2. **Modern UI**
- Gradient backgrounds
- Card-based layout
- Hover effects dan transitions
- Font Awesome icons

### 3. **Interactive Elements**
- Radio button dengan custom styling
- Password toggle visibility
- Confirmation dialogs
- Loading states (implicit)

### 4. **User Feedback**
- Success/error alerts dengan auto-dismiss
- Form validation feedback
- Loading indicators

### 5. **Navigation**
- Breadcrumb navigation
- Back buttons
- Consistent navbar

## 🧪 Testing Checklist

### Functional Testing
- [ ] Login sebagai admin
- [ ] Akses `/users` - lihat daftar users
- [ ] Klik "Tambah User" - buka form create
- [ ] Isi form dan submit - user baru tersimpan
- [ ] Klik "Lihat" - detail user + log aktivitas
- [ ] Klik "Edit" - form edit ter-pre-fill
- [ ] Update data dan submit - data terupdate
- [ ] Klik "Hapus" - konfirmasi dan user terhapus

### Validation Testing
- [ ] Submit form kosong - error validation
- [ ] Email duplicate - error unique
- [ ] Password < 6 karakter - error min length
- [ ] Role invalid - error in validation
- [ ] Edit tanpa ubah password - tetap aman

### Security Testing
- [ ] Akses tanpa login - redirect ke login
- [ ] Akses sebagai petugas/owner - forbidden
- [ ] Hapus user sendiri - blocked
- [ ] SQL injection attempts - sanitized

## 🔧 Customization

### Mengubah Pagination
```php
// Di UserController index()
$users = DB::table('users')
    ->paginate(20); // Ubah dari 10 ke 20
```

### Menambah Field Baru
1. Update migration `users` table
2. Tambah validasi di Controller
3. Update semua View (create, edit, show, index)
4. Update log aktivitas jika perlu

### Mengubah Role Permissions
```php
// Di routes/web.php
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::resource('users', UserController::class);
});
```

### Custom Validation Messages
```php
// Di UserController
$validator = Validator::make($request->all(), [
    // rules...
], [
    'name.required' => 'Nama lengkap harus diisi!',
    'email.unique' => 'Email ini sudah terdaftar di sistem.',
    // custom messages...
]);
```

## 📈 Performance Optimization

### Database Indexing
Pastikan kolom yang sering di-query memiliki index:
```sql
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_status_aktif ON users(status_aktif);
CREATE INDEX idx_log_aktivitas_user_id ON log_aktivitas(id_user);
```

### Query Optimization
- Gunakan `select()` untuk kolom spesifik
- Gunakan pagination untuk data besar
- Index foreign keys

### Caching (Optional)
```php
// Cache roles untuk dropdown
$roles = Cache::remember('user_roles', 3600, function () {
    return ['admin', 'petugas', 'owner'];
});
```

## 🐛 Troubleshooting

### Error: "Middleware not found"
- Pastikan `CheckAuth` dan `CheckRole` sudah dibuat
- Register di `bootstrap/app.php`

### Error: "Route not found"
- Jalankan `php artisan route:clear`
- Pastikan routes sudah ditambahkan dengan benar

### Error: "Table doesn't exist"
- Jalankan `php artisan migrate`
- Pastikan migration files sudah ada

### Error: "Validation fails"
- Cek form input names match dengan validation rules
- Pastikan CSRF token ada di form (`@csrf`)

### Error: "Cannot delete user"
- User yang sedang login tidak bisa dihapus
- Cek session user_id vs user yang akan dihapus

## 📚 Related Files

- `app/Http/Controllers/AuthController.php` - Login/Logout
- `app/Http/Middleware/CheckAuth.php` - Auth middleware
- `app/Http/Middleware/CheckRole.php` - Role middleware
- `database/migrations/*_create_users_table.php` - Users table
- `database/migrations/*_create_log_aktivitas_table.php` - Activity log table
- `database/seeders/UserSeeder.php` - Demo users

## 🎯 Next Steps

1. **Add Search & Filter** - Tambah fitur pencarian nama/email, filter role/status
2. **Bulk Actions** - Hapus multiple users sekaligus
3. **Export Data** - Export users ke Excel/PDF
4. **User Profile** - Halaman profile untuk user mengubah data sendiri
5. **Email Verification** - Verifikasi email saat registrasi
6. **Password Reset** - Fitur lupa password
7. **Two-Factor Auth** - Keamanan tambahan

---

**✅ UserController CRUD lengkap siap digunakan!**

Akses: `http://localhost:8000/users` (setelah login sebagai admin)