@extends('layouts.app')
@section('title', 'Admisi Rawat Inap')
@section('page-title', 'Admisi Rawat Inap')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-warning">
        <strong>🏥 Pindahkan ke Rawat Inap</strong>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <strong>Pasien:</strong> {{ $kunjungan->pasien->nama }} ({{ $kunjungan->pasien->no_rm }})<br>
            <strong>Diagnosis:</strong> {{ $kunjungan->diagnosis ?? 'Belum diisi' }}
        </div>
        <form action="{{ route('dokter.rawat_inap.admisi.store', $kunjungan) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Kamar</label>
                <div class="row g-2">
                    @forelse($kamar as $k)
                    <div class="col-md-6">
                        <div class="form-check border rounded p-3">
                            <input class="form-check-input" type="radio" name="kamar_id" value="{{ $k->id }}" id="kamar{{ $k->id }}" required>
                            <label class="form-check-label w-100" for="kamar{{ $k->id }}">
                                <strong>{{ $k->nama_kamar }}</strong> — {{ $k->kelas }}<br>
                                <small class="text-success">Rp {{ number_format($k->tarif_per_hari) }}/hari</small>
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="col-12"><div class="alert alert-warning">Tidak ada kamar tersedia.</div></div>
                    @endforelse
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Instruksi Perawatan</label>
                <textarea name="instruksi_dokter" class="form-control" rows="3" placeholder="Instruksi untuk tenaga medis selama rawat inap..."></textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="fa fa-bed me-1"></i>Admisi Rawat Inap</button>
            <a href="{{ route('dokter.periksa', $kunjungan) }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>
</div>
</div>
@endsection
