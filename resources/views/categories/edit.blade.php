@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="page-header">
    <h1>Edit Kategori</h1>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
            @error('name')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
            @error('description')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection