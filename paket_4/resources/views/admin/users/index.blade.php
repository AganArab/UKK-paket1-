@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Daftar User</h2>
        <p class="subtitle">Manage akun admin dan siswa.</p>
    </div>
    <a href="/admin/users/create" class="button primary">Tambah User</a>
</div>

<div class="toolbar">
    <form method="GET" action="/admin/users" class="form-inline">
        <input type="text" name="search" placeholder="Cari nama user" value="{{ request('search') }}">
        <button type="submit" class="button secondary">Cari</button>
    </form>
</div>

<div class="table-card">
    <table class="table-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td class="table-actions">
                        <a href="/admin/users/{{ $user->id }}/edit" class="button small">Edit</a>
                        <form action="/admin/users/{{ $user->id }}/delete" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="button danger small" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
