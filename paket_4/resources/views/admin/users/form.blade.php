@extends('layouts.app')

@section('content')
<div class="form-card">
    <h2>{{ $user ? 'Edit User' : 'Tambah User' }}</h2>
    <form action="{{ $user ? '/admin/users/' . $user->id . '/update' : '/admin/users/store' }}" method="POST" class="form-grid">
        @csrf
        @if($user)
            @method('PUT')
        @endif

        <label>
            <span>Nama</span>
            <input type="text" name="name" value="{{ old('name', $user?->name) }}" required>
        </label>
        <label>
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email', $user?->email) }}" required>
        </label>
        <label>
            <span>Password {{ $user ? '(Kosongkan jika tidak diganti)' : '' }}</span>
            <input type="password" name="password" {{ $user ? '' : 'required' }}>
        </label>
        <label>
            <span>Role</span>
            <select name="role" required>
                <option value="admin" {{ old('role', $user?->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="siswa" {{ old('role', $user?->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
            </select>
        </label>

        <div class="form-actions">
            <button type="submit" class="button primary">Simpan</button>
            <a href="/admin/users" class="button secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
