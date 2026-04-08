@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h2>Register</h2>
        <form action="/register" method="POST" class="auth-form">
            @csrf
            <label>
                <span>Nama</span>
                <input type="text" name="name" value="{{ old('name') }}" required>
            </label>
            <label>
                <span>Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>Role</span>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="siswa" selected>Siswa</option>
                </select>
            </label>
            <button type="submit" class="button primary">Simpan</button>
            <a href="/login" class="button secondary">Login</a>
        </form>
    </div>
</div>
@endsection
