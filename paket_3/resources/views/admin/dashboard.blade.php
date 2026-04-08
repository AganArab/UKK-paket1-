@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    @include('partials.sidebar')
    <div class="col-md-9">
        <h2>Dashboard</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-list"></i> Total Aspirasi</h5>
                        <h2>{{ $totalAspirasi }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tags"></i> Total Kategori</h5>
                        <h2>{{ $totalKategori }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Total Selesai</h5>
                        <h2>{{ $totalSelesai }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection