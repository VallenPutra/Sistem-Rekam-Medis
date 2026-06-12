@extends('layouts.app')
@section('title', 'Detail Tagihan')
@section('page-title', 'Detail Tagihan')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Rincian Biaya — {{ $kunjungan->no_kunjungan }}</strong></div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <small class="text-muted">Pasien</small>
                <div class="fw-bold">{{ $kunjungan->pasien->nama }}</div>
                <small class="text-muted">{{ $kunjungan->pasien->no_rm }}</small>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Dokter</small>
                <div>{{ $kunjungan->dokter->name }}</div>
                <small class="text-muted">{{ $kunjungan->tanggal_kunjungan->isoFormat('D MMMM Y') }}</small>
            </div>
            <div class="col-md-4">
                <small class="text-muted">Diagnosis</small>
                <div>{{ $kunjungan->diagnosis ?? '-' }}</div>
                <small class="text-muted">{{ $kunjungan->kode_icd10 }}</small>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr><th>Keterangan</th><th class="text-end">Jumlah</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jasa Dokter</td>
                    <td class="text-end">Rp {{ number_format($kunjungan->jasa_dokter) }}</td>
                </tr>

                @if($kunjungan->tindakan->count())
                <tr><td colspan="2" class="bg-light fw-semibold">Tindakan Medis</td></tr>
                @foreach($kunjungan->tindakan as $t)
                <tr>
                    <td class="ps-4">{{ $t->tindakan->nama_tindakan }} x{{ $t->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($t->subtotal) }}</td>
                </tr>
                @endforeach
                @endif

                @if($kunjungan->resep && $kunjungan->resep->detail->count())
                <tr><td colspan="2" class="bg-light fw-semibold">Obat</td></tr>
                @foreach($kunjungan->resep->detail as $d)
                <tr>
                    <td class="ps-4">{{ $d->obat->nama_obat }} x{{ $d->jumlah }} ({{ $d->aturan_pakai }})</td>
                    <td class="text-end">Rp {{ number_format($d->subtotal) }}</td>
                </tr>
                @endforeach
                @endif

                @if($kunjungan->rawatInap)
                <tr><td colspan="2" class="bg-light fw-semibold">Rawat Inap</td></tr>
                <tr>
                    <td class="ps-4">Kamar {{ $kunjungan->rawatInap->kamar->nama_kamar }}
                        ({{ $kunjungan->rawatInap->lama_hari }} hari × Rp {{ number_format($kunjungan->rawatInap->kamar->tarif_per_hari) }})
                    </td>
                    <td class="text-end">Rp {{ number_format($kunjungan->rawatInap->total_biaya_kamar) }}</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="table-primary fw-bold fs-5">
                    <td>TOTAL BIAYA</td>
                    <td class="text-end">Rp {{ number_format($total) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex gap-2 mt-3">
            <form action="{{ route('admin.kasir.bayar', $kunjungan) }}" method="POST"
                  onsubmit="return confirm('Konfirmasi pembayaran Rp {{ number_format($total) }}?')">
                @csrf
                <button class="btn btn-success btn-lg">
                    <i class="fa fa-check me-1"></i>Konfirmasi Bayar — Rp {{ number_format($total) }}
                </button>
            </form>
            <a href="{{ route('admin.kasir.index') }}" class="btn btn-secondary btn-lg">Batal</a>
        </div>
    </div>
</div>
</div>
</div>
@endsection
