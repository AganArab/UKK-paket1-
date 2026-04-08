@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Daftar Pengguna</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah User</a>
    </div>
</div>

<div class="table-wrapper">
    <table class="custom-table">
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
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i> Lihat</a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i> Edit</a>
                    <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data pengguna</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $users->links() }}
@endsection