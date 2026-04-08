@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Daftar Buku</h2>
        <p class="subtitle">Kelola koleksi buku perpustakaan.</p>
    </div>
    <a href="/admin/books/create" class="button primary">Tambah Buku</a>
</div>

<div class="toolbar">
    <form method="GET" action="/admin/books" class="form-inline">
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
                    <td class="table-actions">
                        <a href="/admin/books/{{ $book->id }}/edit" class="button small">Edit</a>
                        <form action="/admin/books/{{ $book->id }}/delete" method="POST">
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
