@extends('layouts.app')

@section('title', 'Pinjam Alat')

@section('content')
<div class="page-header">
    <h1>Pinjam Alat</h1>
    <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('borrowings.store') }}" id="borrowingForm">
        @csrf

        <div class="form-group">
            <label for="expected_return_date">Tanggal Pengembalian yang Diharapkan *</label>
            <input type="date" id="expected_return_date" name="expected_return_date"
                   value="{{ old('expected_return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
            @error('expected_return_date')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="equipment-selection">
            <h3>Pilih Alat</h3>
            <div id="equipmentList">
                @foreach($equipment as $item)
                <div class="equipment-item">
                    <div class="equipment-info">
                        <strong>{{ $item->name }}</strong>
                        <span class="category">{{ $item->category->name }}</span>
                        <span class="stock">Stok: {{ $item->stock }}</span>
                    </div>
                    <div class="equipment-actions">
                        <input type="number" name="equipment[{{ $item->id }}][quantity]"
                               min="1" max="{{ $item->stock }}" placeholder="Jumlah" class="quantity-input">
                        <input type="hidden" name="equipment[{{ $item->id }}][id]" value="{{ $item->id }}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
            <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('borrowingForm').addEventListener('submit', function(e) {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    let hasSelection = false;

    quantityInputs.forEach(input => {
        if (input.value && parseInt(input.value) > 0) {
            hasSelection = true;
        }
    });

    if (!hasSelection) {
        e.preventDefault();
        alert('Pilih setidaknya satu alat untuk dipinjam');
        return false;
    }
});
</script>
@endsection