@extends('layouts.app')

@section('title', 'Form Aspirasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Form Pengaduan Sarana Sekolah</h4>
            </div>
            <div class="card-body">
                <form action="/aspirasi" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="isi_aspirasi" class="form-label">Isi Aspirasi</label>
                        <textarea class="form-control" id="isi_aspirasi" name="isi_aspirasi" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Aspirasi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection