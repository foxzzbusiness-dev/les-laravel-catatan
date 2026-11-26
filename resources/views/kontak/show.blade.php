@extends('layouts.app')

@section('title', $kontak->nama)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ $catatan->judul }}</span>
                    <div>
                        <small class="text-muted d-block">{{ $catatan->created_at->format('d F Y, H:i') }}</small>
                        <small class="text-success">{{ $catatan->user->name }}</small>
                    </div>
                </div>

                <div class="card-body">
                    <p class="mb-2">
                        <strong>Nama:</strong> {{ $kontak->nama }}
                    </p>

                    <p class="mb-3">
                        <strong>Email:</strong> {{ $kontak->email }}
                        
                    </p>

                    <hr>

                    <p class="card-text">
                        <strong>Pesan:</strong><br>
                        {!! nl2br(e($kontak->pesan)) !!}
                    </p>
                </div>

                <div class="card-footer text-end">
                    <a href="/kontak" class="btn btn-secondary">← Kembali</a>

                    <a href="/kontak/{{ $kontak->id }}/edit" class="btn btn-warning">Edit</a>

                    <form action="/kontak/{{ $kontak->id }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Hapus kontak ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
