@extends('layouts.latihan')

@section('title', $catatan->judul)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                {{-- Header --}}
                <div class="card-header d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-1">{{ $catatan->judul }}</h4>
                        <p class="text-muted mb-0">Dibuat pada: {{ $catatan->created_at->format('d F Y \p\u\k\u\l H.i') }}
                        </p>
                    </div>
                    @if ($catatan->lampiran)
                        <span class="badge bg-info">Ada lampiran</span>
                    @endif
                </div>

                {{-- Isi Catatan --}}
                <div class="card-body">
                    <p class="card-text">
                        {!! nl2br(e($catatan->isi)) !!}
                    </p>

                    {{-- Lampiran --}}
                    @if ($catatan->lampiran)
                        <hr>

                        <p class="mb-2">
                            <strong>Lampiran:</strong>
                        </p>

                        @php
                            $ext = strtolower(pathinfo($catatan->lampiran, PATHINFO_EXTENSION));
                        @endphp

                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                            <img src="{{ asset('storage/' . $catatan->lampiran) }}" alt="Lampiran Catatan"
                                class="img-fluid rounded mt-2" style="max-height: 300px;">
                        @else
                            <a href="{{ asset('storage/' . $catatan->lampiran) }}" target="_blank"
                                class="btn btn-outline-secondary btn-sm mt-2">

                                📎 Lihat Lampiran
                            </a>
                        @endif
                    @endif
                </div>

                {{-- Footer --}}
                <div class="card-footer text-end">
                    <a href="/catatan" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>

                    @if ($catatan->is_archived)
                        <form action="/catatan/{{ $catatan->id }}/unarchive" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="fas fa-undo"></i> Pulihkan</button>
                        </form>
                    @else
                        <form action="/catatan/{{ $catatan->id }}/archive" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary"
                                onclick="return confirm('Arsipkan catatan ini?')">
                                <i class="fas fa-archive"></i> Arsipkan
                            </button>
                        </form>
                    @endif

                    <a href="/catatan/{{ $catatan->id }}/edit" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>

                    <form action="/catatan/{{ $catatan->id }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger"
                            onclick="bukaModalHapus('/catatan/{{ $catatan->id }}', '{{ addslashes($catatan->judul) }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
