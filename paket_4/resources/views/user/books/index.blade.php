@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Daftar Buku</h2>
        <p class="subtitle">Temukan buku yang ingin kamu pinjam.</p>
    </div>
    <a href="/user/dashboard" class="button secondary">Kembali</a>
</div>

<div class="toolbar">
    <form method="GET" action="/user/books" class="form-inline">
        <input type="text" name="search" placeholder="Cari judul buku" value="{{ request('search') }}">
        <button type="submit" class="button secondary">Cari</button>
    </form>
</div>

<div class="table-card">
    <table class="table-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->publisher }}</td>
                    <td>{{ $book->year }}</td>
                    <td>{{ $book->stock }}</td>
                    <td>
                        <form action="/user/borrow" method="POST" class="inline-form">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button class="button primary small" type="submit" {{ $book->stock <= 0 ? 'disabled' : '' }}>Pinjam</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
