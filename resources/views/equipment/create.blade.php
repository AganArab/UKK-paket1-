@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')
<div class="page-header">
    <h1>Tambah Alat</h1>
    <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('equipment.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Alat *</label>
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

        <div class="form-group">
            <label for="category_id">Kategori *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock">Stok *</label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
            @error('stock')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="condition">Kondisi *</label>
            <select id="condition" name="condition" required>
                <option value="baik" {{ old('condition') == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak" {{ old('condition') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="perlu_perbaikan" {{ old('condition') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
            </select>
            @error('condition')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection