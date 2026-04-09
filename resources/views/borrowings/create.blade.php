@extends('layouts.app')

@section('title', 'Pinjam Alat')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Pinjam Alat</h1>
        <a href="{{ route('borrowings.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
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

    <form method="POST" action="{{ route('borrowings.store') }}" id="borrowingForm">
        @csrf

        @if(Auth::user()->role === 'admin')
        <div class="form-group">
            <label for="borrower_id">Peminjam *</label>
            <select id="borrower_id" name="borrower_id" class="form-control" required>
                <option value="">-- Pilih Peminjam --</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('borrower_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="form-group">
            <label for="expected_return_date">Tanggal Pengembalian yang Diharapkan *</label>
            <input type="date" id="expected_return_date" name="expected_return_date"
                   value="{{ old('expected_return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
        </div>

        <div class="form-section">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 15px;">
                <h3 style="margin: 0;">Pilih Alat untuk Dipinjam</h3>
                <button type="button" id="addEquipmentBtn" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Alat</button>
            </div>

            <div id="equipmentContainer">
                <div class="equipment-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Alat *</label>
                        <select name="equipment[0][id]" class="form-control equipment-select" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach($equipment as $item)
                            <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-category="{{ $item->category->name }}">
                                {{ $item->name }} ({{ $item->category->name }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Stok Tersedia</label>
                        <input type="text" class="form-control stock-display" readonly placeholder="0">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Jumlah Pinjam *</label>
                        <input type="number" name="equipment[0][quantity]" class="form-control quantity-input" 
                               min="1" placeholder="0" required>
                    </div>

                    <div class="form-group" style="flex: 0.3; display: flex; align-items: flex-end;">
                        <button type="button" class="btn btn-danger btn-sm remove-equipment" style="display:none;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Ajukan Peminjaman</button>
            <a href="{{ route('borrowings.index') }}" class="btn btn-secondary"><i class="fa-solid fa-times"></i> Batal</a>
        </div>
    </form>
</div>

<style>
.equipment-row {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
    padding: 12px;
    background: #f9f9f9;
    border-radius: 6px;
    border: 1px solid #e8e0d5;
}

.equipment-row .form-group {
    margin-bottom: 0;
}

.equipment-row .form-group label {
    font-size: 0.85rem;
    margin-bottom: 4px;
    display: block;
}

.equipment-row .form-group input,
.equipment-row .form-group select {
    height: 36px;
}

.stock-display {
    background-color: #fff8f0 !important;
    color: #8b7355;
    font-weight: bold;
}

.form-section {
    margin: 20px 0;
    padding: 15px;
    background: #f5f1ea;
    border-radius: 6px;
}

.form-section h3 {
    margin-top: 0;
    color: #8b7355;
}
</style>

<script>
let rowCount = 1;

function updateStockDisplay(select) {
    const row = select.closest('.equipment-row');
    const stockDisplay = row.querySelector('.stock-display');
    const quantityInput = row.querySelector('.quantity-input');
    
    if (select.value) {
        const stock = parseInt(select.options[select.selectedIndex].dataset.stock);
        stockDisplay.value = stock;
        quantityInput.max = stock;
        quantityInput.setAttribute('data-max', stock);
    } else {
        stockDisplay.value = '';
        quantityInput.max = '';
        quantityInput.removeAttribute('data-max');
    }
}

function removeEquipmentRow(btn) {
    btn.closest('.equipment-row').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.equipment-row');
    rows.forEach(row => {
        const removeBtn = row.querySelector('.remove-equipment');
        if (rows.length > 1) {
            removeBtn.style.display = 'block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}

function addEquipmentRow() {
    const container = document.getElementById('equipmentContainer');
    const newRow = document.createElement('div');
    newRow.className = 'equipment-row';
    newRow.innerHTML = `
        <div class="form-group" style="flex: 2;">
            <label>Alat *</label>
            <select name="equipment[${rowCount}][id]" class="form-control equipment-select" required>
                <option value="">-- Pilih Alat --</option>
                @foreach($equipment as $item)
                <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-category="{{ $item->category->name }}">
                    {{ $item->name }} ({{ $item->category->name }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="flex: 1;">
            <label>Stok Tersedia</label>
            <input type="text" class="form-control stock-display" readonly placeholder="0">
        </div>

        <div class="form-group" style="flex: 1;">
            <label>Jumlah Pinjam *</label>
            <input type="number" name="equipment[${rowCount}][quantity]" class="form-control quantity-input" 
                   min="1" placeholder="0" required>
        </div>

        <div class="form-group" style="flex: 0.3; display: flex; align-items: flex-end;">
            <button type="button" class="btn btn-danger btn-sm remove-equipment">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newRow);
    rowCount++;
    
    const newSelect = newRow.querySelector('.equipment-select');
    const newRemoveBtn = newRow.querySelector('.remove-equipment');
    const newQuantityInput = newRow.querySelector('.quantity-input');
    
    newSelect.addEventListener('change', function() {
        updateStockDisplay(this);
    });
    
    newRemoveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        removeEquipmentRow(this);
    });
    
    newQuantityInput.addEventListener('input', function() {
        validateQuantity(this);
    });
    
    updateRemoveButtons();
}

function validateQuantity(input) {
    const max = parseInt(input.getAttribute('data-max')) || 0;
    const value = parseInt(input.value) || 0;
    
    if (max > 0 && value > max) {
        input.value = max;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addEquipmentBtn').addEventListener('click', function(e) {
        e.preventDefault();
        addEquipmentRow();
    });

    document.querySelectorAll('.equipment-select').forEach(select => {
        select.addEventListener('change', function() {
            updateStockDisplay(this);
        });
    });

    document.querySelectorAll('.remove-equipment').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            removeEquipmentRow(this);
        });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            validateQuantity(this);
        });
    });

    document.getElementById('borrowingForm').addEventListener('submit', function(e) {
        const rows = document.querySelectorAll('.equipment-row');
        let hasValidSelection = false;

        rows.forEach(row => {
            const select = row.querySelector('.equipment-select');
            const quantity = row.querySelector('.quantity-input');
            
            if (select.value && quantity.value && parseInt(quantity.value) > 0) {
                hasValidSelection = true;
            }
        });

        if (!hasValidSelection) {
            e.preventDefault();
            alert('Pilih setidaknya satu alat dengan jumlah yang valid untuk dipinjam');
        }
    });

    updateRemoveButtons();
});
</script>
@endsection