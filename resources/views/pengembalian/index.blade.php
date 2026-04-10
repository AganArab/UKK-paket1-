@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

@section('content')
<div class="page-header card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap: 12px; flex-wrap: wrap;">
        <h1>Daftar Pengembalian</h1>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('pengembalian.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Catat Pengembalian</a>
            <a href="{{ route('reports.returns') }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-print"></i> Cetak Laporan</a>
        </div>
    </div>
</div>

<div class="table-wrapper">
    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Tanggal Dikembalikan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->id }}</td>
                <td>{{ $borrowing->borrower->name }}</td>
                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->returnRecord ? $borrowing->returnRecord->return_date->format('d/m/Y') : '-' }}</td>
                <td>
                    <span class="status status-returned">
                        <i class="fa-solid fa-check-circle"></i> Dikembalikan
                    </span>
                </td>
                <td>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a href="{{ route('pengembalian.show', $borrowing->id) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i> Lihat</a>
                        <a href="{{ route('pengembalian.edit', $borrowing->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i> Edit</a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Yakin ingin menghapus?')) { document.getElementById('delete-form-{{ $borrowing->id }}').submit(); }"><i class="fa-solid fa-trash"></i> Hapus</button>
                        <form id="delete-form-{{ $borrowing->id }}" action="{{ route('pengembalian.destroy', $borrowing->id) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $borrowings->links() }}
@endsection