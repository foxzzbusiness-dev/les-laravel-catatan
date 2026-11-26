@extends('layouts.latihan')

@section('title', 'Tentang Kami')

@section('content')
    <div class="card">
        <div class="card-header">{{ $judul }}</div>
        <div class="card-body">
            <p>{{ $deskripsi }}</p>
            <p>Dibuat untuk pembelajaran 30 hari.</p>
            <a href="/" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
