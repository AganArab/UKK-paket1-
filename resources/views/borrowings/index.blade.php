@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Daftar Peminjaman</h1>
        <div style="display:flex; gap: 8px; flex-wrap: wrap;">
            @if(Auth::user()->role === 'peminjam')
            <a href="{{ route('borrowings.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Pinjam Alat</a>
            @endif
            @if(in_array(Auth::user()->role, ['petugas']))
            <a href="{{ route('reports.borrowings') }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-print"></i> Cetak Laporan</a>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->role !== 'peminjam')
<div class="filters">
    <form method="GET" action="{{ route('borrowings.index') }}" class="filter-form">
        <div class="filter-group">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
        </div>
        <div class="filter-group">
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('borrowings.index') }}" class="btn btn-outline">Reset</a>
        </div>
    </form>
</div>
@endif

<div class="table-wrapper">
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                @if(Auth::user()->role !== 'peminjam')
                <th>Peminjam</th>
                @endif
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->id }}</td>
                @if(Auth::user()->role !== 'peminjam')
                <td>{{ $borrowing->borrower->name }}</td>
                @endif
                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                <td>
                    <span class="status status-{{ $borrowing->status }}">
                        @if($borrowing->status === 'pending')
                            <i class="fa-solid fa-clock"></i> Pending
                        @elseif($borrowing->status === 'approved')
                            <i class="fa-solid fa-check"></i> Disetujui
                        @elseif($borrowing->status === 'rejected')
                            <i class="fa-solid fa-xmark"></i> Ditolak
                        @elseif($borrowing->status === 'returned')
                            <i class="fa-solid fa-rotate-right"></i> Dikembalikan
                        @else
                            {{ ucfirst($borrowing->status) }}
                        @endif
                    </span>
                </td>
                <td>
                    <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i> Lihat</a>
                    @if(Auth::user()->role === 'peminjam' && $borrowing->status === 'pending')
                    <a href="{{ route('borrowings.edit', $borrowing) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i> Edit</a>
                    <form method="POST" action="{{ route('borrowings.destroy', $borrowing) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Batal</button>
                    </form>
                    @endif
                    @if(in_array(Auth::user()->role, ['petugas']) && $borrowing->status === 'pending')
                    <form method="POST" action="{{ route('borrowings.approve', $borrowing) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve peminjaman ini?')"><i class="fa-solid fa-circle-check"></i> Approve</button>
                    </form>
                    <form method="POST" action="{{ route('borrowings.reject', $borrowing) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject peminjaman ini?')"><i class="fa-solid fa-circle-xmark"></i> Reject</button>
                    </form>
                    @endif
                    @php
                        $canReturn = in_array(Auth::user()->role, ['admin', 'petugas'])
                            && $borrowing->status === 'approved'
                            && !$borrowing->returnRecord;
                    @endphp

                    @if($canReturn)
                    <button type="button" class="btn btn-sm btn-primary btn-return" data-borrowing-id="{{ $borrowing->id }}">Return</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ Auth::user()->role !== 'peminjam' ? 6 : 5 }}" class="text-center">Tidak ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $borrowings->links() }}

<!-- Return Modal -->
<div id="returnModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Return Equipment</h3>
            <span class="close" onclick="closeReturnModal()">&times;</span>
        </div>
        <form id="returnForm" method="POST">
            @csrf
            <div class="form-group">
                <label for="return_date">Return Date *</label>
                <input type="date" id="return_date" name="return_date" required>
            </div>
            <div class="form-group">
                <label for="condition_returned">Condition Returned *</label>
                <select id="condition_returned" name="condition_returned" required>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Return Equipment</button>
                <button type="button" class="btn btn-secondary" onclick="closeReturnModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReturnModal(borrowingId) {
    document.getElementById('returnForm').setAttribute('action', '/borrowings/' + borrowingId + '/return');
    document.getElementById('returnModal').style.display = 'block';
}

function closeReturnModal() {
    document.getElementById('returnModal').style.display = 'none';
}

document.addEventListener('click', function (event) {
    var button = event.target.closest('.btn-return');
    if (!button) {
        return;
    }

    var borrowingId = button.getAttribute('data-borrowing-id');
    if (!borrowingId) {
        return;
    }

    openReturnModal(borrowingId);
});
</script>
@endsection