@extends('layouts.latihan')

@section('title', 'Tambah Catatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Tambah Catatan Baru</div>

                <div class="card-body">
                    <form method="POST" action="/catatan" enctype="multipart/form-data">
                        @csrf

                        {{-- Field Judul --}}
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" name="judul" id="judul"
                                class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}">

                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Field Isi Catatan --}}
                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi Catatan</label>
                            <textarea name="isi" id="isi" rows="5" class="form-control @error('isi') is-invalid @enderror">{{ old('isi') }}</textarea>
                            <div class="mb-3">
                                <label for="lampiran" class="form-label">Lampiran (opsional)</label>
                                <input type="file" name="lampiran" id="lampiran" class="form-control">
                                <div class="form-text">Format: PDF, gambar, atau teks. Maks 2MB.</div>
                            </div>

                            @error('isi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Simpan Catatan
                            </button>
                        </div>

                        <a href="/catatan" class="mt-2 d-block text-center">
                            ← Kembali ke Daftar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
