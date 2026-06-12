@extends('layouts.app')
@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard Dokter')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning text-white rounded p-3"><i class="fa fa-clock fa-lg"></i></div>
                <div><div class="fw-bold fs-4">{{ $antrian->count() }}</div><small class="text-muted">Menunggu Diperiksa</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded p-3"><i class="fa fa-check fa-lg"></i></div>
                <div><div class="fw-bold fs-4">{{ $selesaiHariIni }}</div><small class="text-muted">Selesai Hari Ini</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-info text-white rounded p-3"><i class="fa fa-bed fa-lg"></i></div>
                <div><div class="fw-bold fs-4">{{ $rawatInapAktif->count() }}</div><small class="text-muted">Rawat Inap Saya</small></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Antrian Pasien - {{ now()->isoFormat('D MMMM Y') }}</strong></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Pasien</th>
                            <th>Keluhan</th>
                            <th>Tanda Vital</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrian as $a)
                        <tr class="{{ $a->status === 'sedang_diperiksa' ? 'table-primary' : '' }}">
                            <td class="text-center fw-bold fs-5">{{ $a->nomor_antrian }}</td>
                            <td>
                                <div class="fw-semibold">{{ $a->pasien->nama }}</div>
                                <small class="text-muted">{{ $a->pasien->no_rm }} • {{ $a->pasien->umur }} thn • {{ $a->pasien->jenis_kelamin }}</small>
                            </td>
                            <td><small>{{ $a->keluhan_utama }}</small></td>
                            <td>
                                <small>
                                    @if($a->tekanan_darah) TD: {{ $a->tekanan_darah }}<br> @endif
                                    @if($a->suhu_tubuh) S: {{ $a->suhu_tubuh }}°C<br> @endif
                                    @if($a->berat_badan) BB: {{ $a->berat_badan }}kg @endif
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('dokter.periksa', $a) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-stethoscope"></i> Periksa
                                </a>
                                <a href="{{ route('dokter.riwayat', $a->pasien) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-history"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">
                            <i class="fa fa-check-circle fa-2x text-success d-block mb-2"></i>
                            Tidak ada antrian saat ini
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($rawatInapAktif->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Pasien Rawat Inap</strong></div>
            <div class="list-group list-group-flush">
                @foreach($rawatInapAktif as $ri)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">{{ $ri->pasien->nama }}</div>
                            <small class="text-muted">Kamar: {{ $ri->kamar->nama_kamar }} ({{ $ri->kamar->kelas }})</small><br>
                            <small class="text-muted">Masuk: {{ $ri->tanggal_masuk->isoFormat('D MMM Y') }}</small>
                        </div>
                        <form action="{{ route('dokter.rawat_inap.keluar', $ri) }}" method="POST"
                              onsubmit="return confirm('Keluarkan pasien ini?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-success">Keluar</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
