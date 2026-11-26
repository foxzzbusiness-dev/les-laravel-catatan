@extends('layouts.latihan')

@section('title', 'kontak Kami')

@section('content')
    <div class="card">
        <div class="card-header">kontak Kami</div>
        <div class="card-body">
            @foreach ($kontak as $item => $value)
                <p>{{ $item }} : {{ $value }}</p>
            @endforeach
            <a href="/" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
