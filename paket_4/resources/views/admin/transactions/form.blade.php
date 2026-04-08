@extends('layouts.app')

@section('content')
<div class="form-card">
    <h2>{{ isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h2>
    <form action="{{ isset($transaction) ? '/admin/transactions/' . $transaction->id . '/update' : '/admin/transactions/store' }}" method="POST" class="form-grid">
        @csrf
        @if(isset($transaction))
            @method('PUT')
        @endif

        <label>
            <span>User</span>
            <select name="user_id" required>
                <option value="">Pilih User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $transaction?->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </label>

        <label>
            <span>Buku</span>
            <select name="book_id" required>
                <option value="">Pilih Buku</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id', $transaction?->book_id) == $book->id ? 'selected' : '' }}>{{ $book->title }} (Stok: {{ $book->stock }})</option>
                @endforeach
            </select>
        </label>

        <label>
            <span>Tanggal Pinjam</span>
            <input type="date" name="borrow_date" value="{{ old('borrow_date', $transaction?->borrow_date ?? now()->toDateString()) }}" required>
        </label>

        <label>
            <span>Status</span>
            <select name="status" required>
                <option value="dipinjam" {{ old('status', $transaction?->status) === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ old('status', $transaction?->status) === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </label>

        <div class="form-actions">
            <button type="submit" class="button primary">Simpan</button>
            <a href="/admin/transactions" class="button secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
