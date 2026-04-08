@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="page-header">
    <h1>Tambah Kategori</h1>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection