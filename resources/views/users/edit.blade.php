@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="page-header card">
    <h1>Edit Pengguna</h1>
</div>

<div class="card">
    @if($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="peminjam" {{ old('role', $user->role) === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password baru (opsional)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </form>
</div>
@endsection