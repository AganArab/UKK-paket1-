@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <div class="card-box">
        <div class="card-label">Total Buku</div>
        <div class="card-value">{{ $booksCount }}</div>
    </div>
    <div class="card-box">
        <div class="card-label">Sedang Dipinjam</div>
        <div class="card-value">{{ $activeBorrows }}</div>
    </div>
</div>

<div class="card-box full-width">
    <div class="card-header">Riwayat Transaksi</div>
    <div class="card-body">
        <form method="GET" action="/user/dashboard" class="form-inline">
            <select name="status">
                <option value="">Semua</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
            <button type="submit" class="button secondary">Filter</button>
        </form>

        <div class="table-card">
            <table class="table-list">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->book_title }}</td>
                            <td>{{ $transaction->borrow_date }}</td>
                            <td>{{ $transaction->return_date ?? '-' }}</td>
                            <td>{{ $transaction->status }}</td>
                            <td>
                                @if($transaction->status === 'dipinjam')
                                    <form action="/user/return" method="POST" class="inline-form">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                        <button type="submit" class="button danger small">Kembalikan</button>
                                    </form>
                                @else
                                    <span class="status-tag returned">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
