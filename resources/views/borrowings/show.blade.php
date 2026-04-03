@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="page-header">
    <h1>Detail Peminjaman #{{ $borrowing->id }}</h1>
    <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="detail-grid">
    <div class="detail-card">
        <h3>Informasi Peminjaman</h3>
        <div class="detail-row">
            <strong>ID:</strong> {{ $borrowing->id }}
        </div>
        <div class="detail-row">
            <strong>Peminjam:</strong> {{ $borrowing->borrower->name }}
        </div>
        <div class="detail-row">
            <strong>Email:</strong> {{ $borrowing->borrower->email }}
        </div>
        <div class="detail-row">
            <strong>Tanggal Pinjam:</strong> {{ $borrowing->borrow_date->format('d/m/Y') }}
        </div>
        <div class="detail-row">
            <strong>Tanggal Kembali (Diharapkan):</strong> {{ $borrowing->expected_return_date->format('d/m/Y') }}
        </div>
        <div class="detail-row">
            <strong>Status:</strong>
            <span class="status status-{{ $borrowing->status }}">
                {{ ucfirst($borrowing->status) }}
            </span>
        </div>
        @if($borrowing->approver)
        <div class="detail-row">
            <strong>Disetujui Oleh:</strong> {{ $borrowing->approver->name }}
        </div>
        @endif
        <div class="detail-row">
            <strong>Dibuat:</strong> {{ $borrowing->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    @if($borrowing->returnRecord)
    <div class="detail-card">
        <h3>Informasi Pengembalian</h3>
        <div class="detail-row">
            <strong>Tanggal Kembali:</strong> {{ $borrowing->returnRecord->return_date->format('d/m/Y') }}
        </div>
        <div class="detail-row">
            <strong>Kondisi Saat Kembali:</strong> {{ ucfirst($borrowing->returnRecord->condition_returned) }}
        </div>
        <div class="detail-row">
            <strong>Diproses Oleh:</strong> {{ $borrowing->returnRecord->processor->name }}
        </div>
    </div>
    @endif
</div>

<div class="page-header">
    <h2>Alat yang Dipinjam</h2>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowing->borrowingDetails as $detail)
            <tr>
                <td>{{ $detail->equipment->name }}</td>
                <td>{{ $detail->equipment->category->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->equipment->description ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($borrowing->fines->count() > 0)
<div class="page-header">
    <h2>Denda</h2>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Jumlah</th>
                <th>Alasan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowing->fines as $fine)
            <tr>
                <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                <td>{{ $fine->reason }}</td>
                <td>
                    @if($fine->paid)
                    <span class="status status-success">Lunas</span>
                    @else
                    <span class="status status-error">Belum Lunas</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection