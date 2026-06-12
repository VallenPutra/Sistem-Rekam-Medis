@extends('layouts.app')
@section('title', 'Riwayat Medis')
@section('page-title', 'Riwayat Medis Pasien')

@section('content')
<div class="row g-3">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Info Pasien</strong></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">No RM</td><td><strong>{{ $pasien->no_rm }}</strong></td></tr>
                    <tr><td class="text-muted">Nama</td><td>{{ $pasien->nama }}</td></tr>
                    <tr><td class="text-muted">Umur</td><td>{{ $pasien->umur }} thn</td></tr>
                    <tr><td class="text-muted">JK</td><td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="text-muted">Gol. Darah</td><td>{{ $pasien->golongan_darah ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Pembayaran</td>
                        <td><span class="badge bg-{{ $pasien->jenis_pembayaran=='bpjs'?'success':'secondary' }}">
                            {{ strtoupper($pasien->jenis_pembayaran) }}</span></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer bg-white">
                <small class="text-muted">Total kunjungan: <strong>{{ $riwayat->count() }}</strong></small>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>Riwayat Kunjungan</strong>
                <a href="{{ route('dokter.dashboard') }}" class="btn btn-sm btn-secondary">← Kembali</a>
            </div>
            <div class="card-body p-0">
                @forelse($riwayat as $k)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-secondary">{{ $k->no_kunjungan }}</span>
                            <span class="badge bg-{{ $k->jenis_kunjungan=='rawat_inap'?'info':'primary' }} ms-1">
                                {{ $k->jenis_kunjungan=='rawat_inap' ? '🏥 Rawat Inap' : '🩺 Rawat Jalan' }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $k->tanggal_kunjungan->isoFormat('D MMMM Y') }}</small>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <small class="text-muted">Dokter</small>
                            <div class="small">{{ $k->dokter->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanda Vital</small>
                            <div class="small">
                                @if($k->tekanan_darah) TD: {{ $k->tekanan_darah }} @endif
                                @if($k->suhu_tubuh) | Suhu: {{ $k->suhu_tubuh }}°C @endif
                                @if($k->berat_badan) | BB: {{ $k->berat_badan }}kg @endif
                            </div>
                        </div>
                        @if($k->anamnesis)
                        <div class="col-md-6">
                            <small class="text-muted">Keluhan / Anamnesis</small>
                            <div class="small">{{ $k->anamnesis }}</div>
                        </div>
                        @endif
                        @if($k->pemeriksaan_fisik)
                        <div class="col-md-6">
                            <small class="text-muted">Pemeriksaan Fisik</small>
                            <div class="small">{{ $k->pemeriksaan_fisik }}</div>
                        </div>
                        @endif
                        @if($k->diagnosis)
                        <div class="col-12">
                            <small class="text-muted">Diagnosis</small>
                            <div class="small fw-semibold">{{ $k->diagnosis }} {{ $k->kode_icd10 ? "({$k->kode_icd10})" : '' }}</div>
                        </div>
                        @endif
                        @if($k->catatan_dokter)
                        <div class="col-12">
                            <small class="text-muted">Catatan Dokter</small>
                            <div class="small">{{ $k->catatan_dokter }}</div>
                        </div>
                        @endif
                        @if($k->tindakan->count())
                        <div class="col-md-6">
                            <small class="text-muted">Tindakan</small>
                            <div class="small">{{ $k->tindakan->map(fn($t) => $t->tindakan->nama_tindakan)->join(', ') }}</div>
                        </div>
                        @endif
                        @if($k->resep && $k->resep->detail->count())
                        <div class="col-md-6">
                            <small class="text-muted">Obat</small>
                            <div class="small">
                                @foreach($k->resep->detail as $d)
                                {{ $d->obat->nama_obat }} {{ $d->jumlah }}x ({{ $d->aturan_pakai }})<br>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if($k->rontgen->count())
                        <div class="col-12">
                            <small class="text-muted">Foto Rontgen</small>
                            <div class="small">
                                @foreach($k->rontgen as $r)
                                <span class="me-3">📷 {{ $r->bagian_tubuh }}
                                    <a href="{{ Storage::url($r->file_path) }}" target="_blank">[Lihat]</a>
                                    @if($r->hasil_analisis) — {{ $r->hasil_analisis }} @endif
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-5 text-center text-muted">Belum ada riwayat kunjungan</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
