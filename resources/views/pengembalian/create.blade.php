@extends('layouts.app')

@section('title', 'Catat Pengembalian')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Catat Pengembalian Alat</h1>
        <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
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

    <form method="POST" action="{{ route('pengembalian.store') }}">
        @csrf

        <div class="form-group">
            <label for="borrowing_id">Peminjaman *</label>
            <select id="borrowing_id" name="borrowing_id" class="form-control" required onchange="updateBorrowingDetails()">
                <option value="">-- Pilih Peminjaman --</option>
                @foreach($borrowings as $borrowing)
                <option value="{{ $borrowing->id }}" data-borrower="{{ $borrowing->borrower->name }}" data-equipment="{{ json_encode($borrowing->borrowingDetails->map(fn($d) => $d->equipment->name)->toArray()) }}">
                    #{{ $borrowing->id }} - {{ $borrowing->borrower->name }} ({{ $borrowing->borrow_date->format('d/m/Y') }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" id="borrowing-details" style="display:none; background:#f5f1ea; padding:15px; border-radius:6px; margin:15px 0;">
            <h4 style="margin-bottom:15px; color:#8b7355;">Detail Peminjaman</h4>
            <div style="margin-bottom:10px;">
                <strong>Peminjam:</strong> <span id="detail-borrower">-</span>
            </div>
            <div style="margin-bottom:10px;">
                <strong>Alat yang Dipinjam:</strong> <span id="detail-equipment">-</span>
            </div>
        </div>

        <div class="form-group">
            <label for="return_date">Tanggal Pengembalian *</label>
            <input type="date" id="return_date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="condition_returned">Kondisi Pengembalian *</label>
            <select id="condition_returned" name="condition_returned" class="form-control" required>
                <option value="">-- Pilih Kondisi --</option>
                <option value="baik" {{ old('condition_returned') === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak" {{ old('condition_returned') === 'rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Catat Pengembalian</button>
            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary"><i class="fa-solid fa-times"></i> Batal</a>
        </div>
    </form>
</div>

<script>
function updateBorrowingDetails() {
    const select = document.getElementById('borrowing_id');
    const option = select.options[select.selectedIndex];
    const detailsDiv = document.getElementById('borrowing-details');
    
    if (select.value) {
        document.getElementById('detail-borrower').textContent = option.dataset.borrower;
        const equipment = JSON.parse(option.dataset.equipment);
        document.getElementById('detail-equipment').textContent = equipment.join(', ');
        detailsDiv.style.display = 'block';
    } else {
        detailsDiv.style.display = 'none';
    }
}
</script>
@endsection
