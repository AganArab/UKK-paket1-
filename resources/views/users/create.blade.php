@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="page-header card">
    <h1>Tambah Pengguna</h1>
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

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="peminjam" {{ old('role') === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </form>
</div>
@endsection