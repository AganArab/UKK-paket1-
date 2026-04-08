@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Daftar Transaksi</h2>
        <p class="subtitle">Kelola peminjaman dan pengembalian buku.</p>
    </div>
    <a href="/admin/transactions/create" class="button primary">Tambah Transaksi</a>
</div>

<div class="toolbar">
    <form method="GET" action="/admin/transactions" class="form-inline">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
        </select>
        <button type="submit" class="button secondary">Filter</button>
    </form>
</div>

<div class="table-card">
    <table class="table-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
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
                    <td>{{ $transaction->user_name }}</td>
                    <td>{{ $transaction->book_title }}</td>
                    <td>{{ $transaction->borrow_date }}</td>
                    <td>{{ $transaction->return_date ?? '-' }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td class="table-actions">
                        <a href="/admin/transactions/{{ $transaction->id }}/edit" class="button small">Edit</a>
                        <form action="/admin/transactions/{{ $transaction->id }}/delete" method="POST">
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
