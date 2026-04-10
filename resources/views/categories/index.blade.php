@extends('layouts.app')

@section('title', 'Kategori Alat')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Kategori Alat</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Kategori</a>
    </div>
</div>

<div class="table-wrapper">
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description ?? '-' }}</td>
                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i> Lihat</a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i> Edit</a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data kategori</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $categories->links() }}
@endsection