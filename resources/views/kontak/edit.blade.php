
@extends('layouts.app')

@section('title', 'Edit Kontak')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Edit Kontak</div>

                <div class="card-body">
                    <form method="POST" action="/kontak/{{ $kontak->id }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $kontak->nama) }}">

                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $kontak->email) }}">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea name="pesan"
                                      id="pesan"
                                      class="form-control @error('pesan') is-invalid @enderror"
                                      rows="5">{{ old('pesan', $kontak->pesan) }}</textarea>

                            @error('pesan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                Simpan Perubahan
                            </button>
                        </div>

                        <a href="/kontak/{{ $kontak->id }}"
                           class="mt-2 d-block text-center">
                            ← Kembali ke Detail
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
