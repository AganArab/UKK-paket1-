@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div class="page-header">
    <h1>{{ $category->name }}</h1>
    <div>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="detail-grid">
    <div class="detail-card">
        <h3>Informasi Kategori</h3>
        <div class="detail-row">
            <strong>ID:</strong> {{ $category->id }}
        </div>
        <div class="detail-row">
            <strong>Nama:</strong> {{ $category->name }}
        </div>
        <div class="detail-row">
            <strong>Deskripsi:</strong> {{ $category->description ?? '-' }}
        </div>
        <div class="detail-row">
            <strong>Dibuat:</strong> {{ $category->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="detail-row">
            <strong>Diupdate:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
        </div>
    </div>
</div>

<div class="page-header">
    <h2>Alat dalam Kategori Ini</h2>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipment as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->stock }}</td>
                <td>
                    <span class="status status-{{ $item->condition }}">
                        {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-info">Lihat</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada alat dalam kategori ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $equipment->links() }}
@endsection