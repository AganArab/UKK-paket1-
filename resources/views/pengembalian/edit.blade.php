@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Edit Pengembalian Alat</h1>
        <a href="{{ route('pengembalian.show', $borrowing->id) }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    @if($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('pengembalian.update', $borrowing->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group" style="background:#f5f1ea; padding:15px; border-radius:6px; margin-bottom:20px;">
            <h4 style="margin-bottom:15px; color:#8b7355;">Informasi Peminjaman</h4>
            <div style="margin-bottom:10px;">
                <strong>Peminjam:</strong> {{ $borrowing->borrower->name }}
            </div>
            <div style="margin-bottom:10px;">
                <strong>Tanggal Peminjaman:</strong> {{ $borrowing->borrow_date->format('d/m/Y') }}
            </div>
            <div>
                <strong>Alat yang Dipinjam:</strong>
                <ul style="margin:10px 0 0 20px;">
                    @foreach($borrowing->borrowingDetails as $detail)
                    <li>{{ $detail->equipment->name }} ({{ $detail->quantity }})</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="form-group">
            <label for="return_date">Tanggal Pengembalian *</label>
            <input type="date" id="return_date" name="return_date" 
                   value="{{ old('return_date', $borrowing->returnRecord->return_date->format('Y-m-d')) }}" 
                   class="form-control" required>
        </div>

        <div class="form-group">
            <label for="condition_returned">Kondisi Pengembalian *</label>
            <select id="condition_returned" name="condition_returned" class="form-control" required>
                <option value="">-- Pilih Kondisi --</option>
                <option value="baik" {{ old('condition_returned', $borrowing->returnRecord->condition_returned) === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak" {{ old('condition_returned', $borrowing->returnRecord->condition_returned) === 'rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
            <a href="{{ route('pengembalian.show', $borrowing->id) }}" class="btn btn-secondary"><i class="fa-solid fa-times"></i> Batal</a>
        </div>
    </form>
</div>
@endsection
