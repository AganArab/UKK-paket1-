@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Detail Pengguna</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    <div class="detail-list">
        <div class="detail-item">
            <strong>Nama</strong>
            <span>{{ $user->name }}</span>
        </div>
        <div class="detail-item">
            <strong>Email</strong>
            <span>{{ $user->email }}</span>
        </div>
        <div class="detail-item">
            <strong>Role</strong>
            <span>{{ ucfirst($user->role) }}</span>
        </div>
        <div class="detail-item">
            <strong>Dibuat pada</strong>
            <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>
</div>

@if($borrowings->count())
<div class="page-header card">
    <h2>Riwayat Peminjaman</h2>
</div>

<div class="table-wrapper">
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->id }}</td>
                <td>
                    @foreach($borrowing->borrowingDetails as $detail)
                        <div>{{ $detail->equipment->name }} ({{ $detail->quantity }})</div>
                    @endforeach
                </td>
                <td>{{ $borrowing->borrowingDetails->sum('quantity') }}</td>
                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                <td>{{ ucfirst($borrowing->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $borrowings->links() }}
@endif
@endsection