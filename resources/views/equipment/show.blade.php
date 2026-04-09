@extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')
<div class="page-header">
    <h1>{{ $equipment->name }}</h1>
    <div>
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="detail-grid">
    <div class="detail-card">
        <h3>Informasi Alat</h3>
        <div class="detail-row">
            <strong>ID:</strong> {{ $equipment->id }}
        </div>
        <div class="detail-row">
            <strong>Nama:</strong> {{ $equipment->name }}
        </div>
        <div class="detail-row">
            <strong>Deskripsi:</strong> {{ $equipment->description ?? '-' }}
        </div>
        <div class="detail-row">
            <strong>Kategori:</strong> {{ $equipment->category->name }}
        </div>
        <div class="detail-row">
            <strong>Stok:</strong> {{ $equipment->stock }}
        </div>
        <div class="detail-row">
            <strong>Kondisi:</strong>
            <span class="status status-{{ $equipment->condition }}">
                {{ ucfirst(str_replace('_', ' ', $equipment->condition)) }}
            </span>
        </div>
        <div class="detail-row">
            <strong>Dibuat:</strong> {{ $equipment->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="detail-row">
            <strong>Diupdate:</strong> {{ $equipment->updated_at->format('d/m/Y H:i') }}
        </div>
    </div>
</div>

<div class="page-header">
    <h2>Riwayat Peminjaman</h2>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipment->borrowingDetails as $detail)
            <tr>
                <td>{{ $detail->borrowing->borrower->name }}</td>
                <td>{{ $detail->borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $detail->borrowing->expected_return_date->format('d/m/Y') }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>
                    <span class="status status-{{ $detail->borrowing->status }}">
                        {{ ucfirst($detail->borrowing->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum pernah dipinjam</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection