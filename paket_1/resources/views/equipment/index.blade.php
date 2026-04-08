@extends('layouts.app')

@section('title', 'Daftar Alat')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Daftar Alat</h1>
        <div style="display:flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('equipment.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Alat</a>
            @if(in_array(Auth::user()->role, ['admin', 'petugas']))
            <a href="{{ route('reports.equipment') }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-print"></i> Cetak Laporan</a>
            @endif
        </div>
    </div>
</div>

<div class="filters card">
    <form method="GET" action="{{ route('equipment.index') }}" class="filter-form">
        <div class="filter-group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat..." class="form-control">
        </div>
        <div class="filter-group">
            <select name="category" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('equipment.index') }}" class="btn btn-outline">Reset</a>
        </div>
    </form>
</div>

<div class="table-wrapper">
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipment as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name }}</td>
                <td>{{ $item->stock }}</td>
                <td>
                    <span class="status status-{{ $item->condition }}">
                        {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i> Lihat</a>
                    <a href="{{ route('equipment.edit', $item) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i> Edit</a>
                    <form method="POST" action="{{ route('equipment.destroy', $item) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus alat ini?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data alat</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $equipment->links() }}
@endsection