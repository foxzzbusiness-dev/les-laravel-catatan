
@extends('layouts.app')

@section('title', 'Tambah Kontak')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Tambah Kontak Baru</div>

                <div class="card-body">
                    <form method="POST" action="/kontak">
                        @csrf

                        {{-- Field Nama --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}">

                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Field Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Field Pesan --}}
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Isi Pesan</label>
                            <textarea name="pesan"
                                      id="pesan"
                                      rows="5"
                                      class="form-control @error('pesan') is-invalid @enderror">{{ old('pesan') }}</textarea>

                            @error('pesan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Simpan Kontak
                            </button>
                        </div>

                        <a href="/kontak" class="mt-2 d-block text-center">
                            ← Kembali ke Daftar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
