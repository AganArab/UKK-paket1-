@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <div class="card-box">
        <div class="card-label">Total Buku</div>
        <div class="card-value">{{ $booksCount }}</div>
    </div>
    <div class="card-box">
        <div class="card-label">Total User</div>
        <div class="card-value">{{ $usersCount }}</div>
    </div>
    <div class="card-box">
        <div class="card-label">Total Transaksi</div>
        <div class="card-value">{{ $transactionsCount }}</div>
    </div>
</div>
@endsection
