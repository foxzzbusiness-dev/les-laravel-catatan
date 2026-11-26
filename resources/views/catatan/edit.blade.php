
@extends('layouts.latihan')

@section('title', 'Edit Catatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Edit Catatan</div>

                <div class="card-body">
                    <form method="POST"
                        action="/catatan/{{ $catatan->id }}"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        {{-- Field Judul --}}
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" name="judul" id="judul"
                                class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $catatan->judul) }}">

                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Field Isi Catatan --}}
                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi Catatan</label>
                            <textarea name="isi" id="isi" rows="5"
                                class="form-control @error('isi') is-invalid @enderror">{{ old('isi', $catatan->isi) }}</textarea>

                            @error('isi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Gambar Lama --}}
                        <div class="mb-3">
                            <label class="form-label">Gambar Saat Ini</label>

                            @if ($catatan->lampiran)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $catatan->lampiran) }}"
                                        alt="Gambar Catatan"
                                        class="img-thumbnail"
                                        style="max-width: 200px;">
                                </div>
                            @else
                                <p class="text-muted">Belum ada gambar.</p>
                            @endif
                        </div>

                        {{-- Upload Gambar Baru --}}
                        <div class="mb-3">
                            <label for="lampiran" class="form-label">
                                Ganti Gambar (opsional)
                            </label>

                            <input type="file" name="lampiran" id="lampiran"
                                class="form-control @error('lampiran') is-invalid @enderror">

                            <div class="form-text">
                                Pilih gambar baru jika ingin mengganti gambar lama. Maksimal 2MB.
                            </div>

                            @error('lampiran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>

                        <a href="/catatan/{{ $catatan->id }}"
                            class="mt-2 d-block text-center">
                            ← Kembali ke Detail
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
