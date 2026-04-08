@extends('layouts.app')

@section('content')
<div class="form-card">
    <h2>{{ $book ? 'Edit Buku' : 'Tambah Buku' }}</h2>
    <form action="{{ $book ? '/admin/books/' . $book->id . '/update' : '/admin/books/store' }}" method="POST" class="form-grid">
        @csrf
        @if($book)
            @method('PUT')
        @endif

        <label>
            <span>Judul</span>
            <input type="text" name="title" value="{{ old('title', $book?->title) }}" required>
        </label>
        <label>
            <span>Pengarang</span>
            <input type="text" name="author" value="{{ old('author', $book?->author) }}" required>
        </label>
        <label>
            <span>Penerbit</span>
            <input type="text" name="publisher" value="{{ old('publisher', $book?->publisher) }}" required>
        </label>
        <label>
            <span>Tahun</span>
            <input type="number" name="year" value="{{ old('year', $book?->year) }}" required>
        </label>
        <label>
            <span>Stok</span>
            <input type="number" name="stock" value="{{ old('stock', $book?->stock) }}" required>
        </label>

        <div class="form-actions">
            <button type="submit" class="button primary">Simpan</button>
            <a href="/admin/books" class="button secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
