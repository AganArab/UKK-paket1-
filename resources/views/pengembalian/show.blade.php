@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Detail Pengembalian Alat</h1>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('pengembalian.edit', $borrowing->id) }}" class="btn btn-warning"><i class="fa-solid fa-edit"></i> Edit</a>
            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<div class="card">
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div>
            <h4 style="color:#8b7355; margin-bottom:15px; border-bottom:2px solid #c99a7a; padding-bottom:10px;">
                <i class="fa-solid fa-user"></i> Informasi Peminjam
            </h4>
            <div style="margin-bottom:12px;">
                <strong>Nama:</strong><br>{{ $borrowing->borrower->name }}
            </div>
            <div style="margin-bottom:12px;">
                <strong>No. Identitas:</strong><br>{{ $borrowing->borrower->identity_number ?? '-' }}
            </div>
            <div>
                <strong>Email:</strong><br>{{ $borrowing->borrower->email }}
            </div>
        </div>

        <div>
            <h4 style="color:#8b7355; margin-bottom:15px; border-bottom:2px solid #c99a7a; padding-bottom:10px;">
                <i class="fa-solid fa-calendar"></i> Tanggal
            </h4>
            <div style="margin-bottom:12px;">
                <strong>Peminjaman:</strong><br>{{ $borrowing->borrow_date->format('d/m/Y') }}
            </div>
            <div style="margin-bottom:12px;">
                <strong>Target Kembali:</strong><br>{{ $borrowing->return_date->format('d/m/Y') }}
            </div>
            <div>
                <strong>Pengembalian:</strong><br>
                @if($borrowing->returnRecord)
                    {{ $borrowing->returnRecord->return_date->format('d/m/Y') }}
                @else
                    <span style="color:#666;">Belum dikembalikan</span>
                @endif
            </div>
        </div>

        <div>
            <h4 style="color:#8b7355; margin-bottom:15px; border-bottom:2px solid #c99a7a; padding-bottom:10px;">
                <i class="fa-solid fa-info-circle"></i> Status
            </h4>
            <div style="margin-bottom:12px;">
                <strong>Status Peminjaman:</strong><br>
                <span class="badge" style="background-color: {{ $borrowing->status === 'approved' ? '#28a745' : ($borrowing->status === 'returned' ? '#17a2b8' : '#ffc107') }};">
                    {{ ucfirst($borrowing->status) }}
                </span>
            </div>
            @if($borrowing->returnRecord)
            <div>
                <strong>Kondisi Pengembalian:</strong><br>
                <span class="badge" style="background-color: {{ $borrowing->returnRecord->condition_returned === 'baik' ? '#28a745' : '#dc3545' }};">
                    {{ ucfirst($borrowing->returnRecord->condition_returned) }}
                </span>
            </div>
            @endif
        </div>
    </div>

    <hr style="border:none; border-top:2px solid #e0e0e0; margin:30px 0;">

    <h4 style="color:#8b7355; margin-bottom:20px; border-bottom:2px solid #c99a7a; padding-bottom:10px;">
        <i class="fa-solid fa-tools"></i> Alat yang Dipinjam
    </h4>
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowing->borrowingDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->equipment->name }}</td>
                    <td>{{ $detail->equipment->category->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#999;">Tidak ada alat yang dipinjam</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($borrowing->returnRecord)
    <hr style="border:none; border-top:2px solid #e0e0e0; margin:30px 0;">

    <h4 style="color:#8b7355; margin-bottom:20px; border-bottom:2px solid #c99a7a; padding-bottom:10px;">
        <i class="fa-solid fa-receipt"></i> Informasi Pengembalian
    </h4>
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom:30px;">
        <div>
            <strong>Tanggal Pengembalian:</strong><br>{{ $borrowing->returnRecord->return_date->format('d/m/Y H:i') }}
        </div>
        <div>
            <strong>Kondisi Kembali:</strong><br>
            <span class="badge" style="background-color: {{ $borrowing->returnRecord->condition_returned === 'baik' ? '#28a745' : '#dc3545' }};">
                {{ ucfirst($borrowing->returnRecord->condition_returned) }}
            </span>
        </div>
        <div>
            <strong>Diproses Oleh:</strong><br>{{ $borrowing->returnRecord->processor->name ?? 'N/A' }}
        </div>
        @if($borrowing->fines->count() > 0)
        <div>
            <strong>Denda:</strong><br>
            <span style="color:#dc3545; font-size:18px; font-weight:bold;">Rp {{ number_format($borrowing->fines->sum('amount'), 0, ',', '.') }}</span>
            <ul style="margin:10px 0 0 20px; font-size:14px; color:#666;">
                @foreach($borrowing->fines as $fine)
                <li>{{ $fine->reason }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    @if($borrowing->returnRecord)
    <div class="form-actions">
        <button type="button" onclick="if(confirm('Yakin ingin menghapus pengembalian ini?')) { document.getElementById('delete-form').submit(); }" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> Hapus Pengembalian
        </button>
    </div>
    <form id="delete-form" action="{{ route('pengembalian.destroy', $borrowing->id) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>
@endsection
