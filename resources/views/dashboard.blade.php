@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard">
    <h1>Dashboard</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <div class="stat-content">
                <h3>Total Alat</h3>
                <div class="stat-number">{{ $data['total_equipment'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div class="stat-content">
                <h3>Total Kategori</h3>
                <div class="stat-number">{{ $data['total_categories'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>Total Pengguna</h3>
                <div class="stat-number">{{ $data['total_users'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-hand-holding-box"></i>
            </div>
            <div class="stat-content">
                <h3>Peminjaman Pending</h3>
                <div class="stat-number">{{ $data['pending_borrowings'] }}</div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
    <div class="dashboard-section card">
        <h2>Peminjaman Terbaru</h2>
        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['recent_borrowings'] as $borrowing)
                    <tr>
                        <td>{{ $borrowing->borrower->name }}</td>
                        <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                        <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="status status-{{ $borrowing->status }}">
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i> Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($data['low_stock_equipment']->count() > 0)
    <div class="dashboard-section card">
        <h2>Alat Stok Rendah</h2>
        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['low_stock_equipment'] as $equipment)
                    <tr>
                        <td>{{ $equipment->name }}</td>
                        <td>{{ $equipment->category->name }}</td>
                        <td>{{ $equipment->stock }}</td>
                        <td>
                            <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-sm btn-warning">Update Stok</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @elseif(Auth::user()->role === 'petugas')
    <div class="dashboard-section">
        <h2>Persetujuan Pending</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['pending_approvals'] as $borrowing)
                    <tr>
                        <td>{{ $borrowing->borrower->name }}</td>
                        <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                        <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada persetujuan pending</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else
    <div class="dashboard-section">
        <h2>Peminjaman Saya</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['my_borrowings'] as $borrowing)
                    <tr>
                        <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                        <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="status status-{{ $borrowing->status }}">
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('borrowings.create') }}" class="btn btn-primary">Pinjam Alat</a>
    </div>
    @endif
</div>
@endsection